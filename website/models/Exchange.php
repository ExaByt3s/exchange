<?php

    class Exchange {

        /**
         * Gets current exchange rate from external API.
         *
         * @param string $apiUrl
         * @return bool|mixed
         */
        public function getCurrentExchangeRate($apiUrl) {
            $headers = ['Accept: application/json'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $responseData = curl_exec($ch);
            $responseCode = curl_getinfo($ch)['http_code'];

            curl_close($ch);

            return ($responseCode === 200) ? $responseData : false;
        }


        /**
         * Validates data before purchase.
         *
         * @param int $amount
         * @param float $unitPrice
         * @param float $currencySellPrice
         * @param int $currencyUnit
         * @param string $currencyCode
         * @return string
         */
        public function purchaseValidation($amount, $unitPrice, $currencySellPrice, $currencyUnit, $currencyCode) {
            if ($amount <= 0) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_unit_error']
                ]);
            }

            if ($unitPrice != round($currencySellPrice, 2)) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_rate_too_old']
                ]);
            }

            if ($_POST['amount'] % $currencyUnit !== 0) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_unit_error']
                ]);
            }

            $config = ConfigQuery::create()->findOneByName(strtoupper($currencyCode))->toArray();

            if ($_POST['amount'] > $config['Value']) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['too_little_in_exchange']
                ]);
            }

            $wallet = new Wallets();
            $currencies = json_decode($wallet->getWallet());

            if (($_POST['amount'] * ($unitPrice / $currencyUnit)) > $currencies->pln) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['too_little_pln_in_wallet']
                ]);
            }


            return json_encode([
                'success' => true
            ]);
        }


        /**
         * Validates data before sale.
         *
         * @param int $amount
         * @param float $unitPrice
         * @param float $purchasePrice
         * @param int $currencyUnit
         * @param string $currencyCode
         * @return string
         */
        public function saleValidation($amount, $unitPrice, $purchasePrice, $currencyUnit, $currencyCode) {
            if ($amount <= 0) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_unit_error']
                ]);
            }

            if ($unitPrice != round($purchasePrice, 2)) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_rate_too_old']
                ]);
            }

            if ($_POST['amount'] % $currencyUnit !== 0) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['exchange_unit_error']
                ]);
            }

            $wallet = new Wallets();
            $currencies = json_decode($wallet->getWallet());

            if ($_POST['amount'] > $currencies->$currencyCode) {
                return json_encode([
                    'success' => false,
                    'message' => Dictionary::init()['too_little_in_wallet']
                ]);
            }

            return json_encode([
                'success' => true
            ]);
        }


        /**
         * Exchange one currency to other.
         *
         * @param string $operationType
         * @param string $currency
         * @param int $amount
         * @param float $unitPrice
         * @param int $currencyUnit
         * @return bool
         */
        public function exchangeMoney($operationType, $currency, $amount, $unitPrice, $currencyUnit) {
            $sessions = new Sessions();

            $walletId   = UsersQuery::create()->findOneById($sessions->getUserId())->toArray();
            $wallet     = WalletsQuery::create()->findOneById($walletId['WalletId']);
            $userWallet = $wallet->toArray();

            if ($currency === 'usd') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Usd'] - $amount) : ($userWallet['Usd'] + $amount);
                $wallet->setUsd($newValue);
            }
            if ($currency === 'eur') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Eur'] - $amount) : ($userWallet['Eur'] + $amount);
                $wallet->setEur($newValue);
            }
            if ($currency === 'chf') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Chf'] - $amount) : ($userWallet['Chf'] + $amount);
                $wallet->setChf($newValue);
            }
            if ($currency === 'rub') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Rub'] - $amount) : ($userWallet['Rub'] + $amount);
                $wallet->setRub($newValue);
            }
            if ($currency === 'czk') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Czk'] - $amount) : ($userWallet['Czk'] + $amount);
                $wallet->setCzk($newValue);
            }
            if ($currency === 'gbp') {
                $newValue = ($operationType === 'sale') ? ($userWallet['Gbp'] - $amount) : ($userWallet['Gbp'] + $amount);
                $wallet->setGbp($newValue);
            }

            $newPlnValue = ($operationType === 'sale') ?
                ($userWallet['Pln'] + ($amount * ($unitPrice /$currencyUnit ))) :
                ($userWallet['Pln'] - ($amount * ($unitPrice /$currencyUnit )));

            $wallet->setPln($newPlnValue);

            $wallet->save();

            return true;
        }
    }
