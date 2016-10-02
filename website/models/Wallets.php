<?php

use Base\Wallets as BaseWallets;

class Wallets extends BaseWallets {

    public function transferMoney($currencies) {
        $currencies = json_decode($currencies);

        $walletId   = UsersQuery::create()->findOneById($_SESSION['userId'])->toArray();
        $wallet     = WalletsQuery::create()->findOneById($walletId['WalletId']);

        if (! empty($currencies->pln) && $currencies->pln >= 0 && $currencies->pln <= 2147483647) {
            $wallet->setPln($currencies->pln);
        }
        if (! empty($currencies->usd) && $currencies->usd >= 0 && $currencies->usd <= 2147483647) {
            $wallet->setUsd($currencies->usd);
        }
        if (! empty($currencies->eur) && $currencies->eur >= 0 && $currencies->eur <= 2147483647) {
            $wallet->setEur($currencies->eur);
        }
        if (! empty($currencies->chf) && $currencies->chf >= 0 && $currencies->chf <= 2147483647) {
            $wallet->setChf($currencies->chf);
        }
        if (! empty($currencies->rub) && $currencies->rub >= 0 && $currencies->rub <= 2147483647) {
            $wallet->setRub($currencies->rub);
        }
        if (! empty($currencies->czk) && $currencies->czk >= 0 && $currencies->czk <= 2147483647) {
            $wallet->setCzk($currencies->czk);
        }
        if (! empty($currencies->gbp) && $currencies->gbp >= 0 && $currencies->gbp <= 2147483647) {
            $wallet->setGbp($currencies->gbp);
        }

        $wallet->save();

        return true;
    }


    public function getWallet() {
        $walletId = UsersQuery::create()->findOneById($_SESSION['userId'])->toArray();

        $wallet = WalletsQuery::create()->findOneById($walletId['WalletId'])->toArray();

        $currencies = [
            'pln' => $wallet['Pln'],
            'usd' => $wallet['Usd'],
            'eur' => $wallet['Eur'],
            'chf' => $wallet['Chf'],
            'rub' => $wallet['Rub'],
            'czk' => $wallet['Czk'],
            'gbp' => $wallet['Gbp']
        ];

        return json_encode($currencies);
    }

}
