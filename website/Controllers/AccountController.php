<?php

    namespace Controllers {

        use Dictionary;
        use Sessions;
        use UsersQuery;
        use Wallets;

        class AccountController {

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

                $wallet = new Wallets();
                $currencies = json_decode($wallet->getWallet());

                $user = UsersQuery::create()->findOneById($userId)->toArray();

                $this->app->template->variables['website_page'] = $this->app->twig->render('account.twig', array(
                    'full_name' => $user['Firstname'] . ' ' . $user['Surname'],
                    'dashboard_url' => $this->app->route->getContainer()->get('router')->pathFor('dashboard.index'),
                    'logout_url' => $this->app->route->getContainer()->get('router')->pathFor('logout'),
                    'transfer_form_url' => $this->app->route->getContainer()->get('router')->pathFor('account.transfer'),
                    'currencies' => $currencies
                ));
            }

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
                        'success' => true,
                        'message' => Dictionary::init()['cannot_transfer_money']
                    ]));
                }
            }

        }

    }
