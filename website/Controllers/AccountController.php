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
                $userId = $session->getUserId();

                if (! $userId) {
                    header('Location: ' . $this->app->getRouteUrl('home.index'));
                    exit();
                }

                $wallet = new Wallets();
                $currencies = json_decode($wallet->getWallet());

                $user = UsersQuery::create()->findOneById($userId)->toArray();

                $this->app->template->variables['website_page'] = $this->app->twig->render('account.twig', array(
                    'full_name' => $user['Firstname'] . ' ' . $user['Surname'],
                    'dashboard_url' => $this->app->getRouteUrl('dashboard.index'),
                    'logout_url' => $this->app->getRouteUrl('logout'),
                    'transfer_form_url' => $this->app->getRouteUrl('account.transfer'),
                    'change_password_form_url' => $this->app->getRouteUrl('account.change.password'),
                    'change_data_form_url' => $this->app->getRouteUrl('account.change.data'),
                    'currencies' => $currencies,
                    'email' => $user['Email'],
                    'login' => $user['Login'],
                    'firstname' => $user['Firstname'],
                    'surname' => $user['Surname'],
                    'script_nonce' => $this->app->getScriptNonce()
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

                exit(json_encode([
                    'success' => true,
                    'message' => Dictionary::init()['success']
                ]));
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
                $sessions = new Sessions();
                $users = new Users();
                $validation = json_decode($users->validatePassword($_POST['password'], $_POST['password2'], false));

                if ($validation->success) {
                    $user = UsersQuery::create()->findOneById($sessions->getUserId());
                    $user->setPassword(hash('sha256', SALT . $_POST['password']));
                    $user->save();

                    exit(json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['password_changed']
                    ]));
                }

                exit(json_encode($validation));
            }


            /**
             * Change user data.
             */
            public function changeData() {
                $sessions = new Sessions();
                $users = new Users();
                $user = UsersQuery::create()->findOneById($sessions->getUserId());
                $userData = $user->toArray();

                $validationEmail = json_decode($users->validateEmail($_POST['email'], true));
                $validationLogin = json_decode($users->validateUsername($_POST['login']));
                $validationFirstname = json_decode($users->validateFirstname($_POST['firstname']));
                $validationSurname = json_decode($users->validateSurname($_POST['surname']));

                if (! $validationEmail->success && $_POST['email'] !== $userData['Email']) {
                    exit(json_encode($validationEmail));
                }

                if (! $validationLogin->success && $_POST['login'] !== $userData['Login']) {
                    exit(json_encode($validationLogin));
                }

                if (! $validationFirstname->success) {
                    exit(json_encode($validationFirstname));
                }

                if (! $validationSurname->success) {
                    exit(json_encode($validationSurname));
                }

                $user->setFirstname(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING));
                $user->setSurname(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
                $user->save();

                exit(json_encode([
                    'success' => true,
                    'message' => Dictionary::init()['data_changed']
                ]));
            }


            /**
             * Logout user.
             */
            public function logout() {
                $session = new Sessions();
                $session->remove();

                header('Location: ' . $this->app->getRouteUrl('home.index'));

                exit();
            }
        }

    }
