<?php

    namespace Controllers {

        use Dictionary;
        use Exchange;
        use Sessions;
        use UsersQuery;
        use Wallets;

        class DashboardController {

            public function __construct($app) {
                $this->app = $app;
            }


            public function index() {
                $session = new Sessions();
                $userId = $session->getUserId();

                if (! $userId) {
                    header('Location: ' . $this->app->getRouteUrl('home.index'));
                    exit();
                }

                $user = UsersQuery::create()->findOneById($userId)->toArray();
                $wallet = new Wallets();
                $currencies = json_decode($wallet->getWallet());


                $this->app->template->variables['website_page'] = $this->app->twig->render('dashboard.twig', array(
                    'full_name' => $user['Firstname'] . ' ' . $user['Surname'],
                    'account_url' => $this->app->getRouteUrl('account.index'),
                    'logout_url' => $this->app->getRouteUrl('logout'),
                    'purchase_url' => $this->app->getRouteUrl('exchange.purchase', ['code'=>'']),
                    'sell_url' => $this->app->getRouteUrl('exchange.sell', ['code'=>'']),
                    'currencies' => $currencies,
                    'script_nonce' => $this->app->getScriptNonce()
                ));
            }


            public function purchaseModal($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrent();

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


            public function purchase($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrent();

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

                } else {
                    exit(json_encode([
                        'success' => false,
                        'message' => Dictionary::init()['no_api_connection']
                    ]));
                }
            }


            public function saleModal($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrent();

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


            public function sale($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = $exchangeRate->getCurrent();

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

                } else {
                    exit(json_encode([
                        'success' => false,
                        'message' => Dictionary::init()['no_api_connection']
                    ]));
                }
            }

        }

    }
