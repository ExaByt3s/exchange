<?php

    namespace Controllers {

        use Sessions;
        use UsersQuery;
        use Wallets;

        class DashboardController {

            public function __construct($app) {
                $this->app = $app;
            }


            /**
             * Displays dashboard.
             */
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

        }

    }
