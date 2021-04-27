<?php

namespace App\Controller;

use App\Entity\Integrity\Analytics\Account;
use App\Entity\Integrity\Analytics\Asset;
use App\Entity\Integrity\Analytics\Capitalization;
use App\Entity\Integrity\Analytics\Exchange;
use App\Entity\Integrity\Analytics\Instrument;
use App\Exception;
use App\Form\Analytics\AccountType;
use App\Form\Analytics\InstrumentsType;
use App\Form\Data\AnalyticsData;
use App\Form\Data\InstrumentsData;
use App\Service\Analytics\BitfinexV2;
use App\Service\Analytics\Stats;
use App\Service\CryptCompare;
use App\Service\BitFlowClient;
use Predis\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Analytics\Bitfinex;

/**
 * Class AnalyticsController
 * @package App\Controller
 */
class AnalyticsController extends IFTController
{
    /**
     *
     */
    const USE_CACHE = true;

    /**
     * @Route("/analytics/test", name="route_analytics_test")
     */
    public function test()
    {
        $cryptCompare = new CryptCompare();
        $price = $cryptCompare->getSingleSymbolPrice('ZCN', 'USD');
        var_dump(round($price['USD'], 4));
        die();



        $account = $this->getDoctrine()->getRepository(\App\Entity\Integrity\Analytics\Account::class)
            ->find(159);
//        var_dump($account->getId());
//        die();
        $this->analytics = $account;
//        $bitfinex = new BitfinexV2($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
        $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());

        $curDate = new \DateTime('now');
        $pastTrades = $bitfinex
            ->getPastTrades(strtotime('-5000 day'), $curDate->getTimestamp(), 50, 'ZCNUSD');

        var_dump($pastTrades);
        die();

        $movements = $bitfinex->get_trades();
    }

    /**
     * @Route("/analytics", name="route_analytics_index")
     */
    public function index()
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();

            return $this->redirectToRoute('route_home');
        }

        $accountForm = $this->createForm(AccountType::class, new AnalyticsData(), []);
        $accounts = $this->getDoctrine()->getRepository(\App\Entity\Integrity\Analytics\Account::class)
            ->findByOwner($this->getAuth()->getAccount());

        if (empty($accounts)) {
            return $this->render('analytics/empty.html.twig', $this->makeRenderData('route_analytics_index', [
                'crypto_news' => $this->container->get('app.rss')->getCryptoNews(3),
                'form_account' => $accountForm->createView(),
            ]));
        } else {
////            var_dump(current($accounts)->getId());
////            die();
            return $this->redirect($this->generateUrl('route_analytics_current', [
                'id' => current($accounts)->getId(),
                'accounts' => $accounts,
            ]));
        }
    }

    /**
     * @Route("/analytics/all", name="route_analytics_all")
     */
    public function all()
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();

            return $this->redirectToRoute('route_home');
        }

        $analyticsAccounts = $this->getDoctrine()->getRepository(Account::class)
            ->findByOwner($this->getAuth()->getAccount());

        $totalData = [];
        $predis = new Client();

        $totalBtc = $totalUsd = 0;

        /** @var Account $analytics */
        foreach ($analyticsAccounts as $analytics) {
            $statsService = $this->get('app.stats')->init($analytics);

            /**
             * Collect stats
             */
            $statsKey = 'stats_' . $analytics->getAPIkey();
            if (!$stats = json_decode($predis->get($statsKey), true)) {

                $bitfinexExchange = $this->getDoctrine()->getRepository(Exchange::class)->find(Exchange::BITFINEX);
                $instruments = $this->getDoctrine()->getRepository(Instrument::class)->findByExchange($bitfinexExchange);

                $namedInstruments = [];
                /** @var Instrument $instrument */
                foreach ($instruments as $instrument) {
                    $namedInstruments[] = $instrument->getValue();
                }
                $stats = $statsService->getStats($namedInstruments);

                $predis->set($statsKey, json_encode($stats));
            }

            $totalData[] = $stats;

            /**
             * Portfolio total
             */
            $portfolioKey = 'portfolio_' . $analytics->getAPIkey();
            if (!$portfolioTotal = json_decode($predis->get($portfolioKey), true)) {
                $portfolioTotal = $statsService->getPortfolioTotal();

                $predis->set($portfolioKey, json_encode($portfolioTotal));
            }

            $totalBtc += $portfolioTotal["total_btc"];
            $totalUsd += $portfolioTotal["total_usd"];
        }

        $summary = [];
        $sumMiddlePrice = $sumAmount = [];
        foreach ($totalData as $accountData) {
            foreach ($accountData as $pair => $data) {

                @$sumMiddlePrice[$pair]['middle_price'][] = $data['middle_price'];
                @$sumMiddlePrice[$pair]['current_price'] = $data['current_price'];

                @$sumMiddlePrice[$pair]['amount'] += $data['amount'];
            }
        }

        foreach ($sumMiddlePrice as $pair => $middleData) {
            $middlePrice = array_sum($middleData['middle_price']) / count($middleData['middle_price']);

            @$summary[$pair]['middle_price'] = $middlePrice;
            @$summary[$pair]['current_price'] = $middleData['current_price'];
            @$summary[$pair]['amount'] = $middleData['amount'];

            @$summary[$pair]['delta'] = $middleData['current_price'] - $middlePrice;
            @$summary[$pair]['delta_percent'] = $summary[$pair]['delta'] / $middleData['current_price'] * 100;
            @$summary[$pair]['arrow'] = $summary[$pair]['delta'] < 0 ? 'down' : 'up';
//
            $parts = explode('.', $middleData['current_price']);
            @$summary[$pair]['pow'] = strlen((string)$parts[1]);
        }

//        echo "New attar";
//
//        echo "<pre>";
//        var_dump($summary);
//        echo "</pre>";
//        die();

        $accountForm = $this->createForm(AccountType::class, new AnalyticsData(), []);

        return $this->render('analytics/all.html.twig', $this->makeRenderData('route_analytics_all', [
            'crypto_news' => $this->container->get('app.rss')->getCryptoNews(3),
            'data' => $summary,
            'total_btc' => $totalBtc,
            'total_usd' => $totalUsd,
            'analytics' => $analyticsAccounts,
//            'data' => [],
            'form_account' => $accountForm->createView(),
        ]));
    }

    /**
     * @param int $id
     *
     * @Route("/analytics/account/{id}", name="route_analytics_current")
     */
    public function current(int $id, array $accounts = [])
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();

            return $this->redirectToRoute('route_home');
        }

        $accountForm = $this->createForm(AccountType::class, new AnalyticsData(), []);

        $analytics = $this->getDoctrine()->getRepository(Account::class)->find($id);
        /** @todo add check for account owner */
//        $movements = $this->get('app.stats')->init($analytics)->getMovements();


        $analyticsAccounts = $this->getDoctrine()->getRepository(Account::class)
            ->findByOwner($this->getAuth()->getAccount());
        $data = $this->getData($analytics);


//        var_dump($data['candles']);
//        die();

//        foreach ($data['candles'] as $inst => $candle) {
//            $data['candles'][$inst]
//        }

        return $this->render('analytics/current.html.twig', $this->makeRenderData('route_analytics_current', [
            'data' => $data['stats'],
//            'data' => [],
            'candles' => $data['candles'],
//            'candles' => [],
            'crypto_news' => $this->container->get('app.rss')->getCryptoNews(3),
            'histogram' => $data['pie'],
            'form_account' => $accountForm->createView(),
            'account' => $analytics,
            'analytics' => $analyticsAccounts,
            'total_btc' => $data['portfolio']['total_btc'],
            'total_usd' => $data['portfolio']['total_usd'],
            'market_cap' => $this->getMarketCap(),
        ]));
    }

    /**
     * @param Account $analytics
     * @return array
     * @throws Exception\AnalyticsNotExistsException
     * @throws Exception\OverLimitException
     *
     * @todo handle exception for limit exceeded
     */
    private function getData(Account $analytics)
    {
        $predis = new Client([
//            'scheme' => 'tcp',
//            'host'   => '18.194.139.141',
//            'port'   => 6379,
        ]);
        $statsService = $this->get('app.stats')->init($analytics);
        $bitfinexExchange = $this->getDoctrine()->getRepository(Exchange::class)->find(Exchange::BITFINEX);
        $instruments = $this->getDoctrine()->getRepository(Instrument::class)->findByExchange($bitfinexExchange);

        /**
         * Collect crypto balance
         */
        if (self::USE_CACHE) {
            $balanceKey = 'balance_' . $analytics->getAPIkey();

            if (!$balance = json_decode($predis->get($balanceKey), true)) {
                $balance = $statsService->getBalanceCrypto();

                $predis->set($balanceKey, json_encode($balance));
            }
        } else {
            $balance = $statsService->getBalanceCrypto();
        }

//        var_dump($balance);
//        die();

        $namedInstruments = [];
        /** @var Instrument $instrument */
        foreach ($instruments as $instrument) {
            $parts = explode("_", $instrument->getValue());
            $from = $parts[0];

            if (in_array(strtolower($from), array_keys($balance))) {
                $namedInstruments[] = $instrument->getValue();
            }
        }

//        var_dump($namedInstruments);
//        die();

        /**
         * Collect percent balance
         */
        if (self::USE_CACHE) {
            $pieKey = 'pie_' . $analytics->getAPIkey();

            if (!$pie = json_decode($predis->get($pieKey), true)) {
                $pie = $statsService->getBalance();

                $predis->set($pieKey, json_encode($pie));
            }
        } else {
            $pie = $statsService->getBalance();
        }

        /**
         * Collect stats
         */
        if (self::USE_CACHE) {
            $statsKey = 'stats_' . $analytics->getAPIkey();

            if (!$stats = json_decode($predis->get($statsKey), true)) {
                $stats = $statsService->getStats($namedInstruments);
                $predis->set($statsKey, json_encode($stats));
            }
        } else {
            $stats = $statsService->getStats($namedInstruments);
        }

        /**
         * Portfolio total
         */
        if (self::USE_CACHE) {
            $portfolioKey = 'portfolio_' . $analytics->getAPIkey();

            if (!$portfolioTotal = json_decode($predis->get($portfolioKey), true)) {
                $portfolioTotal = $statsService->getPortfolioTotal();

                $predis->set($portfolioKey, json_encode($portfolioTotal));
            }
        } else {
            $portfolioTotal = $statsService->getPortfolioTotal();
        }

        /**
         * Build candles
         */
        if (self::USE_CACHE) {
            $candlesKey = 'candles_' . $analytics->getAPIkey();

            if (!$candles = json_decode($predis->get($candlesKey), true)) {
                $candles = $statsService->getCandles($namedInstruments);

                $predis->set($candlesKey, json_encode($candles));
            }
        } else {
            $candles = $statsService->getCandles($namedInstruments);
        }

        return [
            'stats' => $stats,
            'portfolio' => $portfolioTotal,
            'pie' => $pie,
            'candles' => $candles,
        ];
    }

    /**
     * @Route("/analytics/add-account", name="route_analytics_add_acount")
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function addAccount(Request $request)
    {
        $analyticsData = new AnalyticsData();
        $accountForm = $this->createForm(AccountType::class, $analyticsData);

        if ($request->isXmlHttpRequest()) {
            $accountForm->handleRequest($request);

//            $errors = $validator->validate($analyticsData);

//            if ($accountForm->isValid()) {
            try {
                /** @var AnalyticsData $accountData */
                $accountData = $accountForm->getData();
                $analyticsAccounts = $this->getDoctrine()->getRepository(Account::class)
                    ->findByOwner($this->getAuth()->getAccount());

                $apiKeys = $apiSecrets = [];
                /** @var Account $analytics */
                foreach ($analyticsAccounts as $analytics) {
                    $apiKeys[] = $analytics->getAPIkey();
                    $apiSecrets[] = $analytics->getAPIsecret();
                }

                if (in_array($accountData->getAPIkey(), $apiKeys)
                    || in_array($accountData->getAPIsecret(), $apiSecrets)) {
                    throw new Exception\AnalyticsExistsException('API key pair has already been added');
                }

                /**
                 * Create account
                 */
                $account = new \App\Entity\Integrity\Analytics\Account();
                $accountData->handleAccount($account);

                $accountId = $this->getAuth()->getAccount()->getId();
                $owner = $this->getDoctrine()->getRepository(\App\Entity\Integrity\Account::class)->find($accountId);
                $account->setOwner($owner);

                $bitfinex = $this->getDoctrine()->getRepository(Exchange::class)->find(Exchange::BITFINEX);
                $account->setExchange($bitfinex);

//                $owner = $this->getAuth()->getAccount();
//                $owner->addAnalytics($account);
                /** @todo fix via authorized user */

                /**
                 * Get instrument list
                 */
                $instrumentsFromData = new InstrumentsData();
                $predis = new Client([
//                    'scheme' => 'tcp',
//                    'host'   => '18.194.139.141',
//                    'port'   => 6379,
                ]);
                $statsService = $this->get('app.stats')->init($account);

                $bitfinexExchange = $this->getDoctrine()->getRepository(Exchange::class)->find(Exchange::BITFINEX);
                $instruments = $this->getDoctrine()->getRepository(Instrument::class)->findByExchange($bitfinexExchange);

                /**
                 * Collect crypto balance
                 */
                if (self::USE_CACHE) {
                    $balanceKey = 'balance_' . $account->getAPIkey();

                    if (!$balance = json_decode($predis->get($balanceKey), true)) {
                        $balance = $statsService->getBalanceCrypto();

                        $predis->set($balanceKey, json_encode($balance));
                    }
                } else {
                    $balance = $statsService->getBalanceCrypto();
                }

                $namedInstruments = [];
                /** @var Instrument $instrument */
                foreach ($instruments as $instrument) {
                    $parts = explode("_", $instrument->getValue());
                    $from = $parts[0];

                    if (in_array(strtolower($from), array_keys($balance))) {
                        $namedInstruments[] = $instrument->getValue();
                    }
                }

                /**
                 * Collect stats
                 */
                $statsKey = 'stats_' . $account->getAPIkey();
//                if (!$stats = json_decode($predis->get($statsKey), true)) {
                $stats = $statsService->getStats($namedInstruments);

                $predis->set($statsKey, json_encode($stats));
//                }

//                var_dump($stats);
//                die();

                $instrumentList = array_keys($stats);
                $instrumentForm = $this->createForm(InstrumentsType::class, $instrumentsFromData, ['assets' => $instrumentList]);

//                $instrumentForm = $this->createForm()

                /**
                 * Save
                 */
                $em = $this->getDoctrine()->getManager();
                $em->persist($account);
                $em->flush();

                $response = $this->renderView('analytics/_block/assets.html.twig', [
                    'assets_form' => $instrumentForm->createView(),
//                    'analytics_id' => 123,
                    'analytics_id' => $account->getId(),
                ]);

                return $this->json(['view' => $response]);
            } catch (Exception\AnalyticsExistsException $e) {
                return new JsonResponse(['message' => $e->getMessage()]);
            } catch (Exception\AnalyticsNotExistsException $e) {
                return new JsonResponse(['message' => $e->getMessage()]);
            } catch (Exception\OverLimitException $e) {
                return new JsonResponse(['message' => $e->getMessage()]);
            }
        } else {
            return new Response();
        }
    }

    /**
     * @Route("/analytics/add-instruments", name="route_analytics_add_instruments")
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function addInstruments(Request $request)
    {
        $analyticsId = $request->query->get('analytics_id');
        $account = $this->getDoctrine()->getRepository(Account::class)->find($analyticsId);

        $predis = new Client();
        $statsService = $this->get('app.stats')->init($account);

        /**
         * Collect stats
         */
        $statsKey = 'stats_' . $account->getAPIkey();
        if (!$stats = json_decode($predis->get($statsKey), true)) {

            $bitfinexExchange = $this->getDoctrine()->getRepository(Exchange::class)->find(Exchange::BITFINEX);
            $instruments = $this->getDoctrine()->getRepository(Instrument::class)->findByExchange($bitfinexExchange);

            $namedInstruments = [];
            /** @var Instrument $instrument */
            foreach ($instruments as $instrument) {
                $namedInstruments[] = $instrument->getValue();
            }
            $stats = $statsService->getStats($namedInstruments);

            $predis->set($statsKey, json_encode($stats));
        }

        $instrumentList = array_keys($stats);
        $instrumentsFromData = new InstrumentsData();
        $instrumentForm = $this->createForm(InstrumentsType::class, $instrumentsFromData, ['assets' => $instrumentList]);

        if ($request->isXmlHttpRequest()) {
            $instrumentForm->handleRequest($request);

            /** @var InstrumentsData $instrumentData */
            $instrumentData = $instrumentForm->getData();
            $instrumentData->handleAccount($account);

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->json(['account_id' => $account->getId()]);
        } else {
            return new Response();
        }
    }

    /**
     * @return array
     */
    private function getMarketCap()
    {
        /**
         * Collect cap
         */
        $today = new \DateTime();
        $predis = new Client();

//        $timeframes = [
//            'day1' => 1,
//            'w1' => 15,
//            'm1' => 30,
//            'm3' => 60,
//            'y1' => 1440,
//        ];

        $capKey = 'market_cap_' . $today->format('d-m-Y');

        if (!$cap = json_decode($predis->get($capKey), true)) {
            $assetList = $this->getDoctrine()->getRepository(Asset::class)->findAll();
            $cap = [];

            /** @var Asset $asset */
            foreach ($assetList as $asset) {
                $values = $this->getDoctrine()->getRepository(Capitalization::class)->findByAsset($asset);
                $values = array_reverse($values);

                /** @var Capitalization $value */
                foreach ($values as $value) {
                    $cap[$asset->getName()][$value->getCreatedDate()->format('M d Y')] = $value->getPercent();
                }
            }

            $predis->set($capKey, json_encode($cap));
        }

        /**
         * Prepare for output
         */
        $stringCap = [];
        $colors = [
            '#005f89' => 'rgba(0, 95, 137, 0.05)',
            '#189ad3' => 'rgba(24, 154, 211, 0.05)',
            '#007489' => 'rgba(0, 116, 137, 0.05)',
            '#18b5d3' => 'rgba(24, 181, 211, 0.05)',
            '#268900' => 'rgba(38, 137, 0, 0.05)',
            '#4cd318' => 'rgba(76, 211, 24, 0.05)',
            '#890087' => 'rgba(137, 0, 135, 0.05)',
            '#d318d1' => 'rgba(211, 24, 209, 0.05)',
            '#89000b' => 'rgba(137, 0, 11, 0.05)',
            '#d31827' => 'rgba(211, 24, 39, 0.05)',
            '#895200' => 'rgba(137, 82, 0, 0.05)',
//            '#d38918',
        ];
        $i = 0;

        foreach ($cap as $asset => $capitalization) {
            foreach ($capitalization as $period => $value) {
                @$stringCap[$asset]['timeline'] .= "'" . $period . "', ";
                @$stringCap[$asset]['percentline'] .= "'" . round($value, 2) . "', ";
            }

//            var_dump($stringCap);
//            die();

            $stringCap[$asset]['timeline'] = substr($stringCap[$asset]['timeline'], 0, -2);
            $stringCap[$asset]['percentline'] = substr($stringCap[$asset]['percentline'], 0, -2);

            $stringCap[$asset]['color_rgba'] = current($colors);
            $stringCap[$asset]['color_hex'] = key($colors);

            next($colors);
            $i++;
        }

//        var_dump($stringCap);
//        die();

        return ['d1' => $stringCap];
    }
}