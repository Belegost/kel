<?php

namespace App\Controller;

use App\Message\MRM\BuyProductMessage;
use App\Message\MRM\CloseProductMessage;
use App\Model\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends IFTController
{
    /**
     * @Route("/products/buy", name="route_product_buy", methods={"POST"})
     *
     *
     * @param Request $request
     * @param Product $productModel
     * @return Response
     */
    public function routeProductBuy(Request $request, Product $productModel): Response
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

            $product = $productModel->getProductByTypeAndDays($type, $days);
            if ( empty($product) ) {
                throw new \Exception("Unknown product with type `{$type}` and period {$days} days.");
            }

            $binanceSubAccount = $this->getAuth()->getBinanceSubAccount();
            $balances = $binanceSubAccount->getCurrentBalance();
            if ( array_key_exists('USDT', $balances) ) {
                $balanceUSDT = $binanceSubAccount->getCurrentBalance()['USDT']['freeUsdt'];
            } else {
                $balanceUSDT = 0;
            }
            $balanceTotal = $binanceSubAccount->getCurrentBalance(true);
            $price = (float)($product['price'] * (float)$request->get('qty'));

            if ( $balanceUSDT <= $price ) {
                if ( $balanceTotal >= $price ) {
                    throw new \Exception('Not enough money in USDT, but you may convert BTC to USDT and try again later.');
                }
                throw new \Exception('Not enough money! You should to deposit money into your wallet.');
            }

            $message = new BuyProductMessage();
            $message->setAccountId($this->getAuth()->getAccount()->getId());
            $message->setProductId($product['id']);
            $message->setQuantity((float)$request->get('qty'));
            $this->container->get('messenger.default_bus')->dispatch($message);

            return $this->createJSONRSuccess('Your order has been placed.');
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
     * @Route("/products/close", name="route_product_close", methods={"POST"})
     *
     * @param Request $request
     * @param Product $productModel
     * @return JsonResponse
     */
    public function routeProductClose(Request $request, Product $productModel): JsonResponse
    {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('You are not authorized to this action. Please Sign Up before it.', [], -1001);
        }

        if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        try {
            $productId = (int)$request->get('product_id');
            $accountId = $this->getAuth()->getAccountID();
            $products = $productModel->getClientProducts($accountId);

            $product = array_filter($products, function ($p) use ($productId) {
                return $productId == $p['id'];
            });

            if ( empty($product) ) {
                throw new \Exception('This product doesn\'t belongs to current user.');
            }

            $message = new CloseProductMessage();
            $message->setClientProductId($productId);

            $this->container->get('messenger.default_bus')->dispatch($message);

        } catch (\Throwable $t) {
            return $this->createJSONRError($t->getMessage());
        }

        return $this->createJSONRSuccess('Your product will be closed in the near future.');
    }

}
