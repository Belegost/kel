<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.06.2018
 * Time: 11:34
 */

namespace App\Controller;

use App\Entity\Integrity\Wallet;
use App\Lib\DBClient;
use App\Lib\MCModule;
use App\Service\CryptCompare;
use App\Service\MRMClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Withdrawal extends IFTController {
    const OPERATION_FEE = 0.002;

    /**
     * @deprecated
     * @Route("/withdrawal", name="route_withdrawal")
     *
     * @return RedirectResponse|Response
     */
    public function routeMain(CryptCompare $cryptCompare, DBClient $DBClient, MRMClient $MRMClient) {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->redirectToRoute('route_home');
        }

        $currencies = [];
//        $coins = $cryptCompare->getCoinList();
//        if ($coins['Data']) {
//            foreach ($coins['Data'] as $index => $coin) {
//                $currencies[$index] = [
//                    'Symbol' => $coin['Symbol'],
//                    'FullName' => $coin['FullName'],
//                    'ImageUrl' => ""
//                ];
//
//                if (isset($coin['ImageUrl'])) {
//                    $currencies[$index]['ImageUrl'] = "{$coins['BaseImageUrl']}{$coin['ImageUrl']}";
//                }
//            }
//
//            usort($currencies, function ($fItem, $sItem) {
//                return $fItem['FullName'] <=> $sItem['FullName'];
//            });
//        }
//
        $this->addRenderVar('currencies', $currencies);
        $wallets = [];
//
//        $wallets = $DBClient->getWalletRepository()->findDebitWallets($this->getAuth()->getAccount()->getZohoContactId());
//        $wallets = array_map(function ($wlt) use ($cryptCompare, $currencies) {
//            /** @var Wallet $wlt */
//            $price = $cryptCompare->getSingleSymbolPrice($wlt->getCurrency(), 'BTC');
//            if (!isset($price['BTC'])) {
//                $price = ['BTC' => 0];
//            }
//
//            $img = '';
//            foreach ($currencies as $info) {
//                if ($info['Symbol'] == $wlt->getCurrency()) {
//                    $img = $info['ImageUrl'];
//                    break;
//                }
//            }
//
//            return [
//                'id' => $wlt->getId(),
//                'name' => $wlt->getName(),
//                'address' => $wlt->getAddress(),
//                'currency' => $wlt->getCurrency(),
//                'rate' => $price['BTC'],
//                'image' => $img,
//                'fee' => self::OPERATION_FEE
//            ];
//        }, $wallets);
//
        $this->addRenderVar('wallets', $wallets);

        $withdrawal_list = [];
//        try {
//            $result = $MRMClient->getWithdrawalsList($this->getAuth()->getAccount()->getZohoContactId());
//
//            if ($result['status'] == 'success') {
//                $withdrawal_list = array_map(function ($wtl) use ($DBClient) {
//                    /** @var Wallet $wallet */
//                    $wallet = $DBClient->getWalletRepository()->find(intval($wtl['wallet_id']));
//                    if ($wallet instanceof Wallet) {
//                        $dateTime = (new \DateTime())->setTimestamp($wtl['ts']);
//
//                        $wtl['wallet_name'] = $wallet->getName();
//                        $wtl['transaction_date'] = $dateTime->format('d M, Y');
//                        $wtl['transaction_time'] = $dateTime->format('H:i');
//                        $wtl['status'] = $wtl['status'] == 'Pending' ? 'Pending' : 'Complete';
//                    }
//
//                    return $wtl;
//                }, $result['data']);
//            }
//        } catch (\Throwable $t) {
//            $this->getLogger()->error($t->getMessage(), $t->getTrace());
//            return $this->createJSONRError('MRM Client error: ' . $t->getMessage());
//        }
        $this->addRenderVar('withdrawal_list', $withdrawal_list);

        return $this->render("controller/page/withdrawal.html.twig", $this->makeRenderData('route_withdrawal'));
    }

    /**
     * @Route("/add-wallet", name="route_add_wallet")
     *
     * @return RedirectResponse|Response
     */
    public function routeAddWallet(Request $request, DBClient $DBClient) {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('Access denied', ['errors' => ['Access denied. Please login before.']]);
        } else if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

        $walletData = $request->get('wallet');
        if (!empty($walletData)) {
            $wallet = new Wallet();
            $wallet->setAccountId($this->getAuth()->getAccount()->getZohoContactId())
                ->setName($walletData['name'])
                ->setAddress($walletData['address'])
                ->setCurrency(strtoupper($walletData['currency']))
                ->setType(Wallet::TYPE_DEBIT_WALLET)
                ->setStatus(Wallet::STATUS_ACTIVE);
            try {
                $DBClient->flushEntityObject($wallet);
                return $this->createJSONRSuccess();
            } catch (\Throwable $t) {
                $message = "Failed add new wallet: [{$t->getCode()}] {$t->getMessage()}";
                $this->getLogger()->error($message, $t->getTrace());
                return $this->createJSONRError('Access denied', ['errors' => [$message]]);
            }
        }

        return $this->createJSONRError('Unexpected error', ['errors' => ["Failed add new wallet. Please try again later."]]);
    }

    /**
     * @deprecated
     * @Route("/withdrawal-order/create", name="route_withdrawal_create_order")
     *
     * @return RedirectResponse|Response
     * //todo: complete
     */
    public function routeCreateOrder(Request $request, MRMClient $MRMClient, DBClient $DBClient) {
        if (!$this->getAuth()->isLogged()) {
            $this->getAuth()->logout();
            return $this->createJSONRError('Access denied', ['errors' => ['Access denied. Please login before.']]);
        } else if (!$request->isXmlHttpRequest()) {
            return $this->createJSONRBadRequest();
        }

//        $withdrawal = $request->get('withdrawal');
//        if (!empty($withdrawal)) {
//            try {
//                $wallet = $DBClient->getWalletRepository()->find($withdrawal['wallet_id']);
//                if ($wallet instanceof Wallet) {
//                    $result = $MRMClient->createWithdrawal(
//                        $this->getAuth()->getAccount()->getZohoContactId(),
//                        $withdrawal['amount'],
//                        $withdrawal['currency'],
//                        self::OPERATION_FEE,
//                        $withdrawal['wallet_id'],
//                        $wallet->getAddress()
//                    );
//
//                    if ($result['status'] == 'success') {
//                        return $this->createJSONRSuccess();
//                    } else {
//                        return $this->createJSONRError($result['message'], $result['data'], $result['code']);
//                    }
//                }
//                throw new \ErrorException("Wallet #{$withdrawal['wallet_id']} not found.");
//            } catch (\Throwable $t) {
//                $this->getLogger()->error($t->getMessage(), $t->getTrace());
//                return $this->createJSONRError('MRM Client error: ' . $t->getMessage());
//            }
//        }

        return $this->createJSONRError('Incomplete amount', ['errors' => ["Wrong amount. Please check and try again."]]);
    }
}
