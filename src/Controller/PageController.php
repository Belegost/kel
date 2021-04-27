<?php

namespace App\Controller;


use App\Entity\Integrity\Account;
use App\Entity\MRM\AssetType;
use App\Entity\MRM\Document;
use App\Entity\MRM\DocumentType;
use App\Form\ChangePasswordType;
use App\Form\ConverterType;
use App\Form\Data\ChangePasswordData;
use App\Form\Data\ConverterData;
use App\Form\Data\FillProfileData;
use App\Form\Data\SettingsData;
use App\Form\FillProfileType;
use App\Form\UserSettingsType;
use App\Lib\JSONHelper;
use App\Lib\MCModule;
use App\Helper\ProductHelper;
use App\Model\Product;
use App\Service\Alerts;
use App\Service\Messenger\MRM;
use App\Service\TokenManager;
use App\Service\Messenger\MRM as MRMMessenger;
use App\Service\Messenger\Zoho as ZohoMessenger;
use App\Service\MRMClient;
use App\Service\BitFlowClient;
use App\Service\SumSubClient;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Desk\RequestApi;
use App\Form\Data\ContactData;
use App\Form\ContactType;
use App\Service\AjaxValidator;
use function Symfony\Component\String\b;

class PageController extends IFTController
{

    /**
     * @Route("/", name="route_home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('controller/page/home.html.twig', $this->makeRenderData('route_home'));
    }


    /**
     * @Route("/convert/{action}", name="route_convert")
     *
     * @param Request $request
     * @param AjaxValidator $validator
     * @param MRMMessenger $messenger
     * @param string $action
     * @return Response
     */
    public function routeConvert(Request $request, AjaxValidator $validator, MRMMessenger $messenger, string $action = ''): Response
    {
        if ( ! $this->getAuth()->isLogged() ) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        $formConverter = $this->createForm(ConverterType::class);

        if ( $request->isXmlHttpRequest() ) {
            switch ($action) {
                case 'submit':
                    $formConverter->handleRequest($request);
                    $errors = $validator->validate($formConverter);

                    if ( !is_null($errors) ) {
                        return $this->json($errors);
                    }

                    if ( $formConverter->isSubmitted() && $formConverter->isValid() ) {
                        /** @var ConverterData $formConverterData */
                        $formConverterData = $formConverter->getData();

                        $messenger->convertCurrency(
                            $this->getAuth()->getBinanceSubAccount()->getApiKey(),
                            $this->getAuth()->getBinanceSubAccount()->getApiSecret(),
                            $formConverterData->getFrom(),
                            $formConverterData->getTo(),
                            $formConverterData->getAmount()
                        );

                        return $this->json(true, 200);
                    }
                    break;
            }
        }

        return $this->render('controller/page/converter.html.twig', $this->makeRenderData('route_convert', [
            'converter_form' => $formConverter->createView(),
        ]));
    }

    /**
     * @Route("/settings/{do}", name="route_user_settings")
     *
     * @param Request $request
     * @param AjaxValidator $validator
     * @param string $do
     * @return RedirectResponse|Response
     */
    public function routeUserSettings(Request $request, AjaxValidator $validator, string $do = ''): Response
    {

        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        $settingsData = new SettingsData($this->getAuth()->getZohoContact());
        $settingsData->setAvatar($this->getAuth()->getAccount()->getAvatar());

        $formSettings = $this->createForm(
            UserSettingsType::class,
            $settingsData
        );

        $passwordDataClass = new ChangePasswordData();
        $formPassword = $this->createForm(
            ChangePasswordType::class,
            $passwordDataClass,
            ['action' => $this->generateUrl('route_user_settings', ['do' => 'save-password'])]
        );

        if ($request->isXmlHttpRequest()) {
            switch ($do) {
                case 'save-changes':
                    $formSettings->handleRequest($request);
                    $errors = $validator->validate($settingsData);

                    if (!is_null($errors)) {
                        return $this->json($errors);
                    }

                    if ($formSettings->isSubmitted() && $formSettings->isValid()) {
                        /** @var SettingsData $settingsData */
                        $settingsData = $formSettings->getData();

                        $this->handleAvatarFile($settingsData);
                        $this->getAuth()->updateSettings($settingsData);

                        return $this->json(true, 200);
                    }
                    break;
                case 'save-password':
                    $formPassword->handleRequest($request);
                    $errors = $validator->validate($passwordDataClass);

                    if (!is_null($errors)) {
                        return $this->json($errors);
                    }

                    if ($formPassword->isSubmitted() && $formPassword->isValid()) {
                        /** @var ChangePasswordData $passwordData */
                        $passwordData = $formPassword->getData();
                        $this->getAuth()->changePassword($passwordData->getPassword());

                        return $this->json(true, 200);
                    }
                    break;
            }

        }

        return $this->render('controller/page/settings.html.twig', $this->makeRenderData('route_user_settings', [
            'form_settings' => $formSettings->createView(),
            'form_password' => $formPassword->createView(),
        ]));
    }

    /**
     * @Route("/verification", name="route_user_verification")
     *
     * @param SumSubClient $sumSubClient
     * @return Response
     */
    public function routeVerification(SumSubClient $sumSubClient): Response
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        return $this->render('controller/page/verification.html.twig', $this->makeRenderData('route_user_verification', [
            'user_email' => $this->getAuth()->getAccount()->getEmail(),
            'user_phone' => $this->getAuth()->getAccount()->getPhoneNumber(),
            'sumsub_api_token' => $sumSubClient->getAccessToken($this->getAuth()->getAccount()->getId() . $this->getAuth()->getAccount()->getEmail()),
            'sumsub_api_url' => getenv('SUMSUB_API_URL'),
        ]));
    }

    /**
     * @Route("/dashboard", name="route_user_dashboard")
     *
     * @param Product $product
     * @param JSONHelper $json
     * @param MCModule $MCModule
     * @return RedirectResponse|Response
     */
    public function routeDashboard(Product $product, JSONHelper $json, MCModule $MCModule)
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        try {
            $marketNews = $this->container->get('app.rss')->getMarketWatchNews(3);
        } catch (\Throwable $t) {
            $marketNews = [];
        }

        try {
            $products = $product->getProductsList();
            $renderData = $MCModule->generateProductTypesRenderData($products);
            foreach ($this->generateProductTypesEnvData($renderData, $json, $product) as $item => $data) {
                $this->addEnvVar($item, $data);
            }

        } catch (\Throwable $t) {
            dd($t);
            $this->addEnvVar('productPrices', [])
                ->addEnvVar('productPrices', [])
                ->addEnvVar('productReturns', [])
                ->addEnvVar('productPie', [])
                ->addEnvVar('totalHistogramm', []);

            $message = "Failed render product list data: [{$t->getCode()}] {$t->getMessage()}";
            $this->getLogger()->error($message, $t->getTrace());
        }

        return $this->render(
            'controller/page/dashboard.html.twig',
            $this->makeRenderData('route_user_dashboard', [
                'market_news' => $marketNews
            ])
        );
    }

    private function generateProductTypesEnvData(array $products, JSONHelper $jsonHelper, Product $productModel): \Generator
    {
        $map = [];
        foreach ($products as $code => $product) {
            $map[$code] = $product['type']['type'];
        }

        $productPeriods = $productModel->getProductPeriodsList();
        $productTypes = $productModel->getProductTypesList();
        $productReturns = [];
        usort($productPeriods, function ($a, $b) {
            return $a['days'] > $b['days'] ? 1 : -1;
        });

        $productPeriods = array_reverse($productPeriods);
        $systemProductTypes = array_filter($productTypes, function ($row) {
            return $row['system'] === 1;
        });

        $totalHistogram = [];
        foreach ( $productPeriods AS $period ) {
            $totalHistogram["d{$period['days']}"] = [
                'datasets' => [],
                'labels' => [],
            ];
            foreach ($systemProductTypes AS $type) {
                $productReturns["d{$period['days']}"][$type['type']] = null;
                $totalHistogram["d{$period['days']}"]['datasets'][] = [
                    'label' => ' ' . $type['name'],
                    'data' => [],
                ];
            }
        }

        $productPrices = $productIds = $productReturns;
        $colorList = $jsonHelper->parseFile('data/_json/color-list.json', []);

        foreach ($products as $code => $product) {
            if (isset($map[$code])) {
                $productPrices["d{$product['period']['days']}"][$map[$code]] = $product['price'];
                $productReturns["d{$product['period']['days']}"][$map[$code]] = $product['returns'];
                $productIds["d{$product['period']['days']}"][$map[$code]] = $product['id'];
            }
        }
        yield 'productPrices' => $productPrices;

        yield 'productReturns' => $productReturns;

        yield 'productIds' => $productIds;


        $productPie = [];

        foreach ($products as $code => $product) {
            $productPie[$code] = [
                'datasets' => [
                    [
                        'data' => array_values($product['assets']),
                        'backgroundColor' => array_slice($colorList, 0, count($product['assets'])),
                    ],
                ],
                'labels' => array_keys($product['assets']),
            ];
        }

        yield 'productPie' => $productPie;

        foreach ($products as $code => $product) {
            if (isset($map[$code])) {
                foreach ($product['historyEquity'] as $ts => $val) {
                    if (!is_numeric($ts)) {
                        continue;
                    }

                    switch ($map[$code]) {
                        case 'classic':
                            $totalHistogram["d{$product['period']['days']}"]['datasets'][1]['data'][] = round($val, 2);
                            break;
                        case 'conservative':
                            $totalHistogram["d{$product['period']['days']}"]['datasets'][0]['data'][] = round($val, 2);
                            break;
                        case 'confident':
                            $totalHistogram["d{$product['period']['days']}"]['datasets'][2]['data'][] = round($val, 2);
                            break;
                        case 'individual':
                            $totalHistogram["d{$product['period']['days']}"]['datasets'][3]['data'][] = round($val, 2);
                            break;
                    }

                    $DateTime = (new \DateTime())->setTimestamp($ts);
                    $label = $DateTime instanceof \DateTime ? $DateTime->format("Y, M.d") : null;

                    if (!in_array($label, $totalHistogram["d{$product['period']['days']}"]['labels'])) {
                        $totalHistogram["d{$product['period']['days']}"]['labels'][] = $label;
                    }
                }
            }
        }
        yield 'totalHistogramm' => $totalHistogram;
    }

    /**
     * @Route("/product/list", name="route_product_list")
     *
     * @return Response
     */
    public function products(): Response
    {
        $contact = new ContactData();
        $formContact = $this->createForm(ContactType::class, $contact);
        return $this->render('controller/page/product-list.html.twig', $this->makeRenderData('route_product_list', [
            'contact_form' => $formContact->createView()
        ]));
    }

    /**
     * @Route("/product/{name}", name="route_product_item")
     *
     * @param string $name
     * @param MRMClient $MRMClient
     *
     * @return Response
     */
    public function routeProduct(string $name, MRMClient $MRMClient): Response
    {
        $productPricing = [];
        try {
            $response = $MRMClient->loadProductsList();
            if (isset($response['data'])) {
                $productPricing = ProductHelper::getProductPricing($name, $response['data']);
            }
        } catch (\Exception $e) {
            dd($e);
            $this->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        $contact = new ContactData();
        $formContact = $this->createForm(ContactType::class, $contact);

        return $this->render("controller/page/product-{$name}.html.twig", $this->makeRenderData("route_product_item_{$name}", [
            'contact_form' => $formContact->createView(),
            'popup_target' => $this->getAuth()->isLogged() ? 'popup-product-order' : 'popup-login',
            'redirect_to' => $this->generateUrl('route_product_item', ['name' => $name]),
            'productPricing' => $productPricing,
            'productType' => $name
        ]));
    }

    /**
     * @Route("/contacts", name="route_contacts")
     *
     * @param Request $request
     * @param AjaxValidator $validator
     * @return Response
     */
    public function routeContacts(Request $request, AjaxValidator $validator): Response
    {
        $token = $this->getParameter('zoho_desk_token');
        $organizationId = $this->getParameter('zoho_organization_id');

        $contact = new ContactData();
        $formContact = $this->createForm(ContactType::class, $contact);

        if ($request->isXmlHttpRequest()) {
            $formContact->handleRequest($request);
            $errors = $validator->validate($contact);

            if (!is_null($errors)) {
                return $this->json($errors);
            }

            if ($formContact->isSubmitted() && $formContact->isValid()) {
                $data = $formContact->getData();
                $deskTicket = new RequestApi($token, $organizationId, $data);
                $response = $deskTicket->createTicket();
                return $this->json($response);
            }
        }

        return $this->render("controller/page/contacts.html.twig", $this->makeRenderData('route_contacts', [
            'contact_form' => $formContact->createView()
        ]));
    }

    /**
     * @Route("/aboutus", name="route_aboutus")
     *
     * @return Response
     */
    public function routeAboutUs(): Response
    {
        return $this->render("controller/page/aboutus.html.twig", $this->makeRenderData('route_aboutus'));
    }

    /**
     * @Route("/faq", name="route_faq")
     *
     * @return Response
     */
    public function routeFaq():Response
    {
        $contact = new ContactData();
        $formContact = $this->createForm(ContactType::class, $contact);
        return $this->render("controller/page/faq.html.twig", $this->makeRenderData('route_faq', [
            'contact_form' => $formContact->createView()
        ]));
    }

    /**
     * @Route("/account/fill-account-data")
     *
     * @param Request $request
     * @param AjaxValidator $validator
     * @param ZohoMessenger $zoho
     * @return Response
     */
    public function routeFillProfileData(Request $request, AjaxValidator $validator, ZohoMessenger $zoho): Response
    {
        $fillProfileForm = $this->createForm(FillProfileType::class);
        $fillProfileForm->handleRequest($request);

        $errors = $validator->validate($fillProfileForm);
        if ( ! is_null($errors) ) {
            return $this->json($errors);
        }

        if ( $fillProfileForm->isSubmitted() && $fillProfileForm->isValid() ) {
            /** @var FillProfileData $fillProfileFormData */

            $fillProfileFormData = $fillProfileForm->getData();
            $this->getAuth()->saveRequiredProfileFields(
                $fillProfileFormData->getFirstname(),
                $fillProfileFormData->getLastname()
            );

            $zoho->updateContact($this->getAuth()->getAccount());
//            $nextStep = '';
//            if ( $this->getAuth()->getAccount()->isUnconfirmed() ) {
//                $nextStep = 'sumsubverification';
//            }
//            if ( $this->getAuth()->getAccount()->isApproved() ) {
//                $nextStep = 'makedeposit';
//            }
            $nextStep = 'makedeposit';

            return $this->json([
                'success' => true,
                'next_step' => $nextStep,
            ], 200);
        }

        return $this->json([''], 500);
    }


    /**
     * @Route("/deposit/{action}", name="route_deposit")
     *
     * @param Request $request
     * @param MailerInterface $mailer
     * @param TokenManager $token
     * @param MRMMessenger $messenger
     * @param SumSubClient $sumSubClient
     * @param string $action
     *
     * @return RedirectResponse|Response
     */
    public function routeDeposit(
        Request $request,
        MailerInterface $mailer,
        TokenManager $token,
        MRMMessenger $messenger,
        SumSubClient $sumSubClient,
        string $action = 'view'
    ): Response {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            $this->addAlert(Alerts::TYPE_WARNING, 'You are not authorized to this action. Please Sign Up before it.');
            return $this->redirectToRoute('route_home');
        }

        if ( $request->isXmlHttpRequest() ) {
            switch ($action) {
                case 'make':
                    $verifyingCode = substr(str_shuffle("0123456789"), 0, 6);
                    $token->generateToken(['VERIFYING_CODE' => $verifyingCode], new \DateTime('+2 min'));

                    $message = (new TemplatedEmail())
                        ->from($this->getContacts('email'))
                        ->to($this->getAuth()->getAccount()->getEmail())
                        ->subject('INTEGRITY Crypto Trust | Make Deposit')
                        ->htmlTemplate('email/make-deposit.html.twig')
                        ->context([
                            'NAME' => $this->getAuth()->getAccount()->getFullName(),
                            'VERIFYING_CODE' => $verifyingCode
                        ]);

                    $mailer->send($message);

                    return $this->createJSONRSuccess('Message with verifying code was sent success', [
                        "verifying_token" => $token->getTokenHash()
                    ]);

                case 'address':
                    $verifyingToken = $request->get('verifying_token');
                    $verifyingCode = $request->get('verifying_code');

                    if ($verifyingToken && $verifyingCode) {
                        if ($token->loadToken($verifyingToken)->isTokenValid() && $token->getTokenData()['VERIFYING_CODE'] == $verifyingCode) {
                            try {
                                if ( ($subAccountId = $this->getAuth()->getAccount()->getBinanceSubAccountId()) === null ) {
                                    $result = $messenger->createSubAccount();
                                    $this->getAuth()
                                        ->saveBinanceSubAccountId($result['subAccountId']);
                                    $this->getAuth()
                                        ->saveBinanceSubAccountAPIData(
                                            $result['apiKey'],
                                            $result['secretKey']
                                        );
                                }

                                $balances = $this->getAuth()->getBinanceSubAccount()
                                    ->getBalances();

                                $response = $this->getAuth()
                                    ->getBinanceSubAccount()
                                    ->getDepositAddress();

                                $addresses = [];

                                $color = new Color(1, 149, 232);
                                $qrCodeWriter = new PngWriter();
                                $logo = Logo::create($this->getParameter('kernel.project_dir') . '/public/images/logo_black1.png')
                                    ->setResizeToHeight(50)
                                ;

                                $label = Label::create('Integrity Fund')
                                    ->setTextColor($color)
                                    ->setBackgroundColor(new Color(255, 255, 255))
                                ;

                                foreach ($response AS $row) {
                                    $coin = $row['coin'];
                                    $asset = $this->getDoctrine()->getRepository('MRM:AssetType')
                                        ->findOneBy([
                                            'code' => $coin,
                                        ]);
                                    if ( ! array_key_exists($coin, $addresses) ) {
                                        $addresses[$coin] = [
                                            'balance' => number_format((array_key_exists($coin, $balances) ? $balances[$coin]['free'] : 0), ($coin === 'USDT' ? 2 : 8), '.', ''),
                                            'code' => $asset->getCode(),
                                            'label' => $asset->getLabel(),
                                            'logo' => $asset->getLogo(),
                                            'networks' => [],
                                        ];
                                    }

                                    $qrCode = QrCode::create($row['address'])
                                        ->setEncoding(new Encoding('UTF-8'))
                                        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                                        ->setSize(160)
                                        ->setMargin(5)
                                        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                                        ->setForegroundColor($color)
                                        ->setBackgroundColor(new Color(255, 255, 255))
                                    ;

                                    $row['qr'] = $qrCodeWriter->write($qrCode, null, $label)->getDataUri();
                                    if ( $row['tag'] ) {
                                        $qrTag = QrCode::create($row['tag'])
                                            ->setEncoding(new Encoding('UTF-8'))
                                            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                                            ->setSize(100)
                                            ->setMargin(5)
                                            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                                            ->setForegroundColor($color)
                                            ->setBackgroundColor(new Color(255, 255, 255))
                                        ;

                                        $row['qr_tag'] = $qrCodeWriter->write($qrTag)->getDataUri();

                                    }

                                    $addresses[$coin]['networks'][] = $row;
                                }
                                $data['addresses'] = $addresses;
                                $data['body'] = $this->renderView('blocks/deposit.addresses.html.twig', ['addresses' => $addresses]);
                            } catch (\Exception $e) {
                                $this->getLogger()->critical($e->getMessage());
                                return $this->createJSONRError('Something went wrong. Please try again later. ' . $e->getMessage());
                            }

                            return $this->createJSONRSuccess("", $data);

                        }
                    }

                    return $this->createJSONRError('Code is not valid');
            }
        }

        $renderData = [
            'step' => 'makedeposit',
        ];

        $account = $this->getAuth()->getAccount();

        if ( $account->isUnconfirmed() ) {
            $renderData['sum_sub_verification_form'] = [
                'api_url' => getenv('SUMSUB_API_URL'),
                'api_token' => $sumSubClient->getAccessToken($this->getAuth()->getAccount()->getId() . $this->getAuth()->getAccount()->getEmail()),
                'user_email' => $account->getEmail(),
                'user_phone' => $account->getPhoneNumber(),
            ];
            $renderData['step'] = 'makedeposit';
        }
        if ( !$account->getFirstName() || !$account->getLastName() ) {
            $fillProfileData = new FillProfileData();
            $fillProfileData->setFirstname($account->getFirstName());
            $fillProfileData->setLastname($account->getLastName());

            $fillProfileForm = $this->createForm(FillProfileType::class, $fillProfileData);

            $renderData['fill_profile_form'] = $fillProfileForm->createView();
            $renderData['step'] = 'fill-profile';

        }

        if ( $account->isApproved() ) {
            $renderData['step'] = 'makedeposit';
        }

        $fundsList = $this->getAuth()->getBinanceSubAccount()->getDepositHistory();
        $renderData['money_movements'] = array_map(function ($item) {
            $precision = $item['coin'] === 'USDT' ? 2 : 8;
            $item['amount'] = number_format($item['amount'], $precision, '.', ' ');
            return $item;
        }, $fundsList);

        return $this->render("controller/page/deposit.html.twig", $this->makeRenderData('route_deposit', $renderData));
    }

    /**
     * @Route("/documents", name="route_documents")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function routeDocuments(Request $request)
    {
        $reformatDocuments = $reformatTypes = [];

        if (($account = $this->getAuth()->getAccount()) instanceof Account) {
            $mrmManager = $this->getDoctrine()->getManager('mrm');
            $documents = $mrmManager->getRepository(Document::class)
                ->findByAccountId($this->getAuth()->getAccount()->getId());
            $documentTypes = $mrmManager->getRepository(DocumentType::class)
                ->findAll();

            $reformatDocuments = [];
            foreach ($documents as $document) {
                $reformatDocuments[$document->getType()->getId()] = $document;
            }

            $reformatTypes = [];
            /** @var DocumentType $documentType */
            foreach ($documentTypes as $documentType) {
                $reformatTypes[$documentType->getId()] = $documentType;
            }
        }

        return $this->render("controller/page/documents.html.twig", $this->makeRenderData('route_documents', [
            'documents' => $reformatDocuments,
            'document_types' => $reformatTypes,
            'account' => $account,
        ]));
    }

    /**
     * @Route("/myproducts", name="route_myproducts")
     *
     * @param MRMClient $MRMClient
     *
     * @return RedirectResponse|Response
     */
    public function routeMyProducts(Product $productModel, JSONHelper $jsonHelper, MCModule $MCModule)
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        $products = $envData = [];
        try {
            $products = $productModel->getClientProducts($this->getAuth()->getAccount()->getId());

            $products = $MCModule->generateClientProductsRenderData($products);
            $imgMap = $jsonHelper->parseFile('data/_json/product-imgs-map.json');
            $products = array_map(function ($item) use ($imgMap) {
                $item['img'] = $imgMap[$item['type']];
                return $item;
            }, $products);


            foreach ($this->generateProductsEnvData($products) as $id => $data) {
                $envData[$id] = $data;
            }

        } catch (\Throwable $t) {
            dd($t);
            //todo: log error
        }

        $this->addRenderVar('myProducts', $products)
            ->addEnvVar('productList', $envData);

        return $this->render("controller/page/myproducts.html.twig", $this->makeRenderData('route_myproducts'));
    }

    private function generateProductsEnvData(array $products)
    {
        foreach ($products as $id => $product) {
            if ($product['status'] != 'Active') continue;

            $productData = [
                'productCharts' => [
                    'pie' => [
                        'data' => [],
                        'labels' => []
                    ],
                    'horizontalBar' => [
                        'data' => [],
                        'labels' => []
                    ],
                    'line' => [
                        'datasets' => [
                            [
                                'label' => 'Total Equity',
                                'data' => []
                            ]
                        ],
                        'labels' => []
                    ]
                ]
            ];

            $lastHistoryData = end($product['historyEquity']);
            reset($product['historyEquity']);
            foreach ($lastHistoryData['assets'] as $code => $data) {
                $productData['productCharts']['pie']['data'][] = intval($data['weight']);
                $productData['productCharts']['pie']['labels'][] = $code;
                $productData['productCharts']['horizontalBar']['data'][] = intval($data['value']);
                $productData['productCharts']['horizontalBar']['labels'][] = $code;
            }

            foreach ($product['historyEquity'] as $ts => $item) {
                $productData['productCharts']['line']['datasets'][0]['data'][] = $item['totalValue'];
                $productData['productCharts']['line']['labels'][] = (new \DateTime())->setTimestamp($ts)->format('Y, M.d');
            }

            yield $id => $productData;
        }
    }

    /**
     * @Route("/portfolio", name="route_portfolio")
     *
     * @return RedirectResponse|Response
     */
    public function routePortfolio(MRMClient $MRMClient, JSONHelper $jsonHelper, MCModule $MCModule): Response
    {
        if (!$this->getAuth()->isLogged()) {
            $this->container->get('session')->getFlashBag()->set('autoload_popup_login', true);
        }

        $instruments = $products = [];
        try {
            /** @var AssetType $usdtAsset */
            $usdtAsset = $this->getDoctrine()->getRepository('MRM:AssetType')
                ->findOneBy(['code' => 'USDT']);

            $coins = $this->getDoctrine()
                ->getRepository('MRM:AssetType')
                ->fetchVisibleAssets();
            $coins = array_map(function ($intstrument) use ($usdtAsset) {

                return [
                    'code' => $intstrument['code'],
                    'pair' => $intstrument['code'] . ' / ' . $usdtAsset->getCode(),
                    'fullname' => $intstrument['label'] . ' / ' . $usdtAsset->getLabel(),
                    'logo' => $intstrument['logo'],
                    'price' => $intstrument['price'],
                ];
            }, $coins);

            $instruments = $MCModule->generateFullCoinsInfo($coins);
        } catch (\Throwable $t) {
            dd($t);
            $this->addRenderVar('freeFundsBTC', 0)
                ->addRenderVar('fundsInInstrumentsBTC', 0)
                ->addRenderVar('fundsAfterInvestBTC', 0);
            //todo: should be logged
        }

        $products = [];

//        try {
//            $response = $MRMClient->loadProductsList();
//            if (isset($response['status']) && $response['status'] == 'success') {
//                $codesMap = $jsonHelper->parseFile('data/_json/product-codes-map.json', []);
//                $namesMap = $jsonHelper->parseFile('data/_json/product-names-map.json', []);
//                $imgsMap = $jsonHelper->parseFile('data/_json/portfolio-product-imgs-map.json', []);
//                $productTypeList = [];
//
//                foreach ($response['data'] as $product) {
//                    $code = $product['code'];
//                    if (isset($codesMap[$code])) {
//                        $mappedCode = $codesMap[$code];
//
//                        if (!isset($productTypeList[$mappedCode])) {
//                            $product['name'] = $namesMap[$code];
//                            $product['imgUrl'] = $imgsMap[$code];
//                            $productTypeList[$mappedCode] = $product;
//                        }
//                    }
//                }
//
//                $products = $MCModule->generateProductsPortfolioData($productTypeList);
//            }
//        } catch (\Throwable $t) {
//            //todo: should be logged
//        }

        $this->addRenderVar('instruments', $instruments)
            ->addRenderVar('products', $products)
            ->addEnvVar('instruments', array_merge($instruments, $products));

        return $this->render("controller/page/portfolio.html.twig", $this->makeRenderData('route_portfolio', [
            'freeze_popup' => true
        ]));
    }

    /**
     * @Route("/full-pricing", name="route_full_pricing")
     *
     * @param Request $request
     * @param MRMClient $MRMClient
     *
     * @return RedirectResponse|Response
     */
    public function routeFullPricing(Request $request, Product $product):Response
    {
        $pricingTable = [];
        try {
            $products = $product->getProductsList();
            $pricingTable = ProductHelper::getPricingTable($products);
        } catch (\Exception $e) {
            dd($e);
            $this->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        return $this->render(
            "controller/page/full-pricing-tables.html.twig",
            $this->makeRenderData(
                'route_full_pricing',
                [
                    'popup_target' => $this->getAuth()->isLogged() ? 'popup-product-order' : 'popup-login',
                    'referer_link' => $request->headers->get('referer'),
                    'pricingTable' => $pricingTable,
                ]
            )
        );
    }

    /**
     * @Route("/get-currency-rates", name="route_get_currency_rates")
     *
     * @param Request $request
     * @param BitFlowClient $papaFeed
     *
     * @return RedirectResponse|Response
     */
//    public function routeGetCurrencyRates(Request $request, BitFlowClient $papaFeed) {
//        /** @var Rates $ratesRequest */
//        $ratesRequest = $this->createXMLHttpRequest(Rates::class);
//        $ratesRequest->handleRequest($request);
//
//        if ($ratesRequest->isPost() && $ratesRequest->isValid()) {
//            $pairs = array_map(function ($c) use ($papaFeed) {
//                return $papaFeed->makeCurrencyPair($c);
//            }, $ratesRequest->getCurrencies());
//
//            $result = $papaFeed->getBitfinex1MRate(...$pairs);
//
//            return $this->createJSONRSuccess('', $result);
//        }
//        return $this->createJSONRError('Error Rates');
//    }

    /**
     * @Route("/pricing", name="route_pricing")
     *
     * @param MRMClient $MRMClient
     *
     * @return RedirectResponse|Response
     */
    public function routePricing(MRMClient $MRMClient)
    {
        $productsPricing = [];
        try {
            $response = $MRMClient->loadProductsList();
            if (isset($response['data'])) {
                $productsPricing = ProductHelper::getProductsPricing($response['data']);
            }
        } catch (\Exception $e) {
            dd($e);
            $this->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        $contact = new ContactData();
        $formContact = $this->createForm(ContactType::class, $contact);

        return $this->render(
            "controller/page/pricing.html.twig",
            $this->makeRenderData(
                'route_pricing',
                [
                    'contact_form' => $formContact->createView(),
                    'popup_target' => $this->getAuth()->isLogged() ? 'popup-product-order' : 'popup-login',
                    'productsPricing' => json_encode($productsPricing),
                ]
            )
        );
    }

    /**
     * @Route("/rates", name="route_rates")
     *
     * @return RedirectResponse|Response
     */
    public function routeRates()
    {
//        $fileName = getenv('TRADE_HOST') . 'widget/ticker';
//        var_dump($fileName);
//        die();

//        $ticker = file_get_contents(getenv('TRADE_HOST') . 'widget/ticker');
        $ticker = '';
        $marketData = $this->container->get('app.rss')->getMarketWatchNews(3);

        return $this->render("controller/page/rates.html.twig", $this->makeRenderData('route_rates', [
            'ticker' => $ticker,
            'market_data' => $marketData,
        ]));
    }

    /**
     * @Route("/news-market", name="route_newsfeed")
     *
     * @return RedirectResponse|Response
     */
    public function routeNewsFeed(Request $request)
    {
        $topNews = [];
        $marketNews = $this->container->get('app.rss')->getMarketWatchNews();
        $integrityNews = $this->container->get('app.rss')->getIntegrityNews();

        $topNews[] = $marketNews[0];
        $topNews[] = $this->container->get('app.rss')->getCryptoNews(1)[0];
        if ( !empty($integrityNews) ) {
            $topNews[] = $integrityNews[0];
        }


        return $this->render("controller/page/news-feed.html.twig", $this->makeRenderData('route_newsfeed', [
            'market_news' => $marketNews,
            'top_news' => $topNews,
        ]));
    }

    /**
     * @Route("/news-crypto", name="route_cryptofeed")
     *
     * @return RedirectResponse|Response
     */
    public function routeCryptoFeed()
    {
        $topNews = [];
        $cryptoNews = $this->container->get('app.rss')->getCryptoNews();
        $integrityNews = $this->get('app.rss')->getIntegrityNews();

        $topNews[] = $cryptoNews[0];
        $topNews[] = $this->container->get('app.rss')->getMarketWatchNews(1)[0];
        if ( !empty($integrityNews) ) {
            $topNews[] = $integrityNews[0];
        }

        return $this->render("controller/page/crypto-feed.html.twig", $this->makeRenderData('route_cryptofeed', [
            'crypto_news' => $cryptoNews,
            'top_news' => $topNews,
        ]));
    }

    /**
     * @Route("/news-integrity", name="route_integrityfeed")
     *
     * @return RedirectResponse|Response
     */
    public function routeIntegrityFeed()
    {
        $topNews = [];
        $integrityNews = $this->get('app.rss')->getIntegrityNews();

        if ( ! empty($integrityNews) ) {
            $topNews[] = $integrityNews[0];
        }
        $topNews[] = $this->container->get('app.rss')->getCryptoNews(1)[0];
        $topNews[] = $this->container->get('app.rss')->getMarketWatchNews(1)[0];

        return $this->render("controller/page/news-integrity.html.twig", $this->makeRenderData('route_integrityfeed', [
            'integrity_news' => $integrityNews,
            'top_news' => $topNews,
        ]));
    }

    /**
     * @Route("/privacy", name="route_privacy")
     *
     * @return RedirectResponse|Response
     */
    public function routePrivacy()
    {
        return $this->render("controller/page/privacy.html.twig", $this->makeRenderData('route_privacy'));
    }
}
