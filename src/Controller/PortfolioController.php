<?php

namespace App\Controller;

use App\Lib\JSONHelper;
use App\Message\MRM\BuyProductMessage;
use App\Model\Product;
use App\Request\Portfolio;
use App\Service\MRMClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PortfolioController extends IFTController {
    /**
     * @Route("/order/create", name="route_order_create")
     *
     * @param Request $request
     * @param MRMClient $MRMClient
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function createOrder(Request $request, MRMClient $MRMClient): JsonResponse
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.', [], -1001);
        }
        if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        try {
            $result = $MRMClient->createOrder(
                $this->getAuth()->getAccount()->getZohoContactId(),
                $request->get('product_code'),
                $request->get('product_quantity')
            );

            if ($result['status'] == 'success') {
                return $this->createJSONRSuccess();
            } else {
                return $this->createJSONRError($result['message'], $result['data'], $result['code']);
            }
        } catch (\Throwable $t) {
            return $this->createJSONRError('MRM Client error: ' . $t->getMessage());
        }
    }

    /**
     * @deprecated
     * @Route("/product-type/buy", name="route_pt_buy")
     *
     * @param Request $request
     * @param MRMClient $MRMClient
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function routeProductTypeBuy(Request $request, MRMClient $MRMClient, Product $product): Response
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.', [], -1001);
        }

        if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        try {
            $days = (int)preg_replace("/[^\d]/", "", $request->get('period') );

            $type = (string)$request->get('name');

            $product = $product->getProductByTypeAndDays($type, $days);
            if ( empty($product) ) {
                throw new \Exception("Unknown product with type `{$type}` and period {$days} days.");
            }

            $binanceSubAccount = $this->getAuth()->getBinanceSubAccount();
            $balanceUSDT = $binanceSubAccount->getCurrentBalance()['USDT'];
            $balanceTotal = $binanceSubAccount->getCurrentBalance(true);
            if ( $balanceUSDT <= $product['price'] ) {
                if ( $balanceTotal >= $product['price'] ) {
                    throw new \Exception('Not enough money in USDT, but you may convert BTC to USDT and try again later.');
                }
                throw new \Exception('Not enough money! You should to deposit money into your wallet.');
            }
//
//            $result = $MRMClient->createOrder(
//                $this->getAuth()->getBinanceSubAccount()->getSubAccountId(),
//                $code,
//                $request->get('qty')
//            );
//
//            if ($result['status'] == 'success') {
//                return $this->createJSONRSuccess();
//            } else {
//                return $this->createJSONRError($result['message'], $result['data'], $result['code']);
//            }
        } catch (\Throwable $t) {
            return $this->createJSONRError($t->getMessage());
        }
    }

    /**
     * @deprecated
     * @Route("/product-custom-type/buy", name="route_pct_buy")
     *
     * @param Request $request
     * @param MRMClient $MRMClient
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function routeProductCustomTypeBuy(Request $request, MRMClient $MRMClient, JSONHelper $jsonHelper): JsonResponse
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.', [], -1001);
        }
        if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        try {
            if ($request->get('assets')) {

                $binanceSubAccount = $this->getAuth()->getBinanceSubAccount();
                $balances = $binanceSubAccount->getCurrentBalance();
                if ( array_key_exists('USDT', $balances) ) {
                    $balanceUSDT = $binanceSubAccount->getCurrentBalance()['USDT']['freeUsdt'];
                } else {
                    $balanceUSDT = 0;
                }

                $balanceTotal = $binanceSubAccount->getCurrentBalance(true);
                if ( $balanceUSDT <= 20000 ) {
                    if ( $balanceTotal >= 20000 ) {
                        throw new \Exception('Not enough money in USDT, but you may convert BTC to USDT and try again later.');
                    }
                    throw new \Exception('Not enough money! You should to deposit money into your wallet.');
                }

                $result = $MRMClient->createIndividualProduct(
                    $this->getAuth()->getAccountID(),
                    $request->get('assets')
                );

                if ( array_key_exists('status', $result) && $result['status'] === 'success' ) {
                    $productId = $result['data']['product_id'];
                    $message = new BuyProductMessage();
                    $message->setAccountId($this->getAuth()->getAccount()->getId());
                    $message->setProductId($productId);
                    $message->setQuantity(1);
                    $this->container->get('messenger.default_bus')->dispatch($message);

                    return $this->createJSONRSuccess();
                }
            }

        } catch (\Throwable $t) {
            return $this->createJSONRError('Error: ' . $t->getMessage());
        }

        return $this->createJSONRError('MRM Client error: The field assets is required.');
    }
}
