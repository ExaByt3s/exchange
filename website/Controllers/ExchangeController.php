<?php

    namespace Controllers {

        use Dictionary;
        use Exchange;
        use Wallets;

        class ExchangeController {

            public function __construct($app) {
                $this->app = $app;
            }

            /**
             * Management of user wallet.
             */
            // TODO: Connect with bank/credit card API.
            public function transferMoney() {
                $wallet = new Wallets();

                $currencies = [
                    'pln' => (str_replace(',', '.', $_POST['pln']) ?? -1),
                    'usd' => (str_replace(',', '.', $_POST['usd']) ?? -1),
                    'eur' => (str_replace(',', '.', $_POST['eur']) ?? -1),
                    'chf' => (str_replace(',', '.', $_POST['chf']) ?? -1),
                    'rub' => (str_replace(',', '.', $_POST['rub']) ?? -1),
                    'czk' => (str_replace(',', '.', $_POST['czk']) ?? -1),
                    'gbp' => (str_replace(',', '.', $_POST['gbp']) ?? -1)
                ];

                if ($wallet->transferMoney(json_encode($currencies))) {
                    exit(json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['money_transferred']
                    ]));
                } else {
                    exit(json_encode([
                        'success' => false,
                        'message' => Dictionary::init()['cannot_transfer_money']
                    ]));
                }
            }


            /**
             * Displays purchase currency modal.
             *
             * @param string $currencyCode
             */
            public function purchaseModal($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrentExchangeRate($this->app->exchangeRateAPIUrl);

                if ($currentExchangeRate) {
                    $currentExchangeRate = json_decode($currentExchangeRate);

                    foreach ($currentExchangeRate->items as $key => $value) {
                        if ($value->code === $currencyCode) {
                            $currencyName = $value->name;
                            $currencyUnit = $value->unit;
                            $currencySellPrice = $value->sellPrice;
                            break;
                        }
                    }

                    echo $this->app->twig->render('modalboxes/purchase.twig', array(
                        'currency_name' => $currencyName,
                        'currency_price' => $currencySellPrice,
                        'currency_unit' => $currencyUnit,
                        'purchase_form_url' => $this->app->getRouteUrl('exchange.purchase', ['code' => strtolower($currencyCode)]),
                    ));

                } else {
                    echo $this->app->twig->render('modalboxes/error.twig', array(
                        'message' => Dictionary::init()['no_api_connection']
                    ));
                }

                exit();
            }


            /**
             * Purchase currency.
             *
             * @param string $currencyCode
             */
            public function purchase($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrentExchangeRate($this->app->exchangeRateAPIUrl);

                if ($currentExchangeRate) {
                    $currentExchangeRate = json_decode($currentExchangeRate);

                    foreach ($currentExchangeRate->items as $key => $value) {
                        if ($value->code === strtoupper($currencyCode)) {
                            $currencyUnit = $value->unit;
                            $currencySellPrice = $value->sellPrice;
                            break;
                        }
                    }

                    $validation = json_decode($exchangeRate->purchaseValidation(
                        $_POST['amount'],
                        $_POST['unit-price'],
                        $currencySellPrice,
                        $currencyUnit,
                        $currencyCode
                    ));

                    if ($validation->success) {
                        $exchange = new Exchange();
                        $exchange->exchangeMoney('purchase', $currencyCode, $_POST['amount'], $currencySellPrice, $currencyUnit);
                    }

                    exit(json_encode($validation));

                }

                exit(json_encode([
                        'success' => false,
                        'message' => Dictionary::init()['no_api_connection']
                    ]));
            }


            /**
             * Display currency sale modal.
             *
             * @param string $currencyCode
             */
            public function saleModal($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrentExchangeRate($this->app->exchangeRateAPIUrl);

                if ($currentExchangeRate) {
                    $currentExchangeRate = json_decode($currentExchangeRate);

                    foreach ($currentExchangeRate->items as $key => $value) {
                        if ($value->code === $currencyCode) {
                            $currencyName = $value->name;
                            $currencyUnit = $value->unit;
                            $currencyBayPrice = $value->purchasePrice;
                            break;
                        }
                    }

                    echo $this->app->twig->render('modalboxes/sale.twig', array(
                        'currency_name' => $currencyName,
                        'currency_price' => $currencyBayPrice,
                        'currency_unit' => $currencyUnit,
                        'sell_form_url' => $this->app->getRouteUrl('exchange.sell', ['code' => strtolower($currencyCode)]),
                    ));

                } else {
                    echo $this->app->twig->render('modalboxes/error.twig', array(
                        'message' => Dictionary::init()['no_api_connection']
                    ));
                }

                exit();
            }


            /**
             * Currency sale.
             *
             * @param string $currencyCode
             */
            public function sale($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrentExchangeRate($this->app->exchangeRateAPIUrl);

                if ($currentExchangeRate) {
                    $currentExchangeRate = json_decode($currentExchangeRate);

                    foreach ($currentExchangeRate->items as $key => $value) {
                        if ($value->code === strtoupper($currencyCode)) {
                            $currencyUnit = $value->unit;
                            $currencyPurchasePrice = $value->purchasePrice;
                            break;
                        }
                    }

                    $validation = json_decode($exchangeRate->saleValidation(
                        $_POST['amount'],
                        $_POST['unit-price'],
                        $currencyPurchasePrice,
                        $currencyUnit,
                        $currencyCode
                    ));

                    if ($validation->success) {
                        $exchange = new Exchange();
                        $exchange->exchangeMoney('sale', $currencyCode, $_POST['amount'], $currencyPurchasePrice, $currencyUnit);
                    }

                    exit(json_encode($validation));

                }

                exit(json_encode([
                        'success' => false,
                        'message' => Dictionary::init()['no_api_connection']
                    ]));
            }

        }

    }
