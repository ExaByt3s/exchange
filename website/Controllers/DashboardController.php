<?php

    namespace Controllers {

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
                $userId = $session->check();

                if (! $userId) {
                    header('Location: ' . $this->app->route->getContainer()->get('router')->pathFor('home.index'));
                    exit();
                }

                $user = UsersQuery::create()->findOneById($userId)->toArray();
                $wallet = new Wallets();
                $currencies = json_decode($wallet->getWallet());


                $this->app->template->variables['website_page'] = $this->app->twig->render('dashboard.twig', array(
                    'full_name' => $user['Firstname'] . ' ' . $user['Surname'],
                    'account_url' => $this->app->route->getContainer()->get('router')->pathFor('account.index'),
                    'logout_url' => $this->app->route->getContainer()->get('router')->pathFor('logout'),
                    'currencies' => $currencies
                ));
            }


            public function purchaseModal($currencyCode) {
                $exchangeRate = new Exchange();
                $currentExchangeRate = json_decode($exchangeRate->getCurrent());

                foreach ($currentExchangeRate->items as $key => $value) {
                    if ($value->code === $currencyCode) {
                        $currencyName = $value->name;
                        $currencySellPrice = $value->sellPrice;
                        break;
                    }
                }


                echo $this->app->twig->render('modalboxes/purchase.twig', array(
                    'currency_name' => $currencyName,
                    'currency_price' => $currencySellPrice
                ));

                exit();
            }

        }

    }
