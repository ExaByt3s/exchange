<?php

    namespace Controllers {

        use Dictionary;
        use Sessions;
        use Users;
        use UsersQuery;
        use Wallets;

        class AccountController {

            public function __construct($app) {
                $this->app = $app;
            }

            /**
             * Main account page.
             */
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
                    'change_password_form_url' => $this->app->route->getContainer()->get('router')->pathFor('account.change.password'),
                    'change_data_form_url' => $this->app->route->getContainer()->get('router')->pathFor('account.change.data'),
                    'currencies' => $currencies,
                    'email' => $user['Email'],
                    'login' => $user['Login'],
                    'firstname' => $user['Firstname'],
                    'surname' => $user['Surname']
                ));
            }

            /**
             * User login.
             */
            public function login() {
                $users = new Users();

                $validateUsername = json_decode($users->validateUsername($_POST['login'], true));

                if (! $validateUsername->success) {
                    exit(json_encode($validateUsername));
                }

                $user = UsersQuery::create()->findOneByLogin($_POST['login'])->toArray();
                $validatePassword = json_decode($users->validatePassword($_POST['password'], $user['Password'], true));

                if (! $validatePassword->success) {
                    exit(json_encode($validatePassword));
                }

                $validateStatus = json_decode($users->validateAccountStatus($user['Id']));
                if (! $validateStatus->success) {
                    exit(json_encode($validateStatus));
                }

                $session = new Sessions();
                $session->create($user['Id']);

                exit(json_encode(['success' => true]));
            }

            /**
             * Management of user wallet.
             */
            // TODO: Transfer service to a separate controller that supports bank API.
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
             * Change the user password
             */
            public function changePassword() {
                $users = new Users();
                $validation = json_decode($users->validatePassword($_POST['password'], $_POST['password2'], false));

                if ($validation->success) {
                    $user = UsersQuery::create()->findOneById($_SESSION['userId']);
                    $user->setPassword(hash('sha256', SALT . $_POST['password']));
                    $user->save();

                    exit(json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['password_changed']
                    ]));
                }

                exit(json_encode($validation));
            }

            public function changeData() {
                exit(json_encode([
                    'success' => false,
                    'message' => 'Feature in future... :-)'
                ]));
            }

            /**
             * Logout user.
             */
            public function logout() {
                $session = new Sessions();
                $session->remove();

                header('Location: ' . $this->app->route->getContainer()->get('router')->pathFor('home.index'));

                exit();
            }
        }

    }
