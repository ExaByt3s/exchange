<?php

    namespace Controllers {

        use Dictionary;
        use Sessions;
        use Tokens;
        use TokensQuery;
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
             * Create new user account.
             */
            public function create() {
                $sessions = new Sessions();
                $users = new Users();

                $validationEmail = json_decode($users->validateEmail($_POST['email'], true));
                $validationPassword = json_decode($users->validatePassword($_POST['password'], $_POST['password2']));
                $validationTerms = json_decode($users->validateTerms($_POST['terms'] ?? null));

                if (! $validationEmail->success) {
                    exit(json_encode($validationEmail));
                }

                if (! $validationPassword->success) {
                    exit(json_encode($validationPassword));
                }

                if (! $validationTerms->success) {
                    exit(json_encode($validationTerms));
                }

                $userId = $users->createAccount(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), $_POST['password']);
                $tokens = new Tokens();
                $token  = $tokens->generateToken($userId, 1);

                $email = $this->app->sendEmail(
                    $_POST['email'],
                    'Exchange online - registration',
                    $this->app->twig->render('email-registration.twig', array(
                        'link' => BASE_URL . $this->app->getRouteUrl('register.activation', ['token' => $token]))
                    )
                );

                if ($email) {
                    echo json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['created_account_email_send']
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['created_account_email_not_send']
                    ]);
                }

                exit();
            }


            /**
             * Account activation form.
             *
             * @param string $urlToken
             */
            public function activationForm($urlToken) {
                $success = false;

                $token = new Tokens();
                $userId = $token->getUserIdByToken(1, $urlToken);

                if ($userId) {
                    $user = UsersQuery::create()->findOneById($userId)->toArray();

                    $success = true;
                    $this->app->template->variables['website_page'] = $this->app->twig->render('activation.twig', [
                        'email' => $user['Email'],
                        'form_url' => $this->app->getRouteUrl('register.activation', ['token' => $urlToken]),
                        'script_nonce' => $this->app->getScriptNonce()
                    ]);
                }

                if (! $success) {
                    $this->app->template->variables['website_page'] = $this->app->twig->render('error.twig', [
                        'message' => Dictionary::init()['activation_link_invalid']
                    ]);
                }
            }


            /**
             * Activation user account
             *
             * @param string $urlToken
             */
            public function activation($urlToken) {
                $users  = new Users();
                $token  = new Tokens();
                $userId = $token->getUserIdByToken(1, $urlToken);

                if ($userId) {
                    $user = UsersQuery::create()->findOneById($userId);

                    $validationLogin = json_decode($users->validateUsername($_POST['login']));
                    $validationFirstname = json_decode($users->validateFirstname($_POST['firstname']));
                    $validationSurname = json_decode($users->validateSurname($_POST['surname']));
                    $validationTerms = json_decode($users->validateTerms($_POST['terms'] ?? null));

                    if (!$validationLogin->success) {
                        exit(json_encode($validationLogin));
                    }

                    if (!$validationFirstname->success) {
                        exit(json_encode($validationFirstname));
                    }

                    if (!$validationSurname->success) {
                        exit(json_encode($validationSurname));
                    }

                    if (! $validationTerms->success) {
                        exit(json_encode($validationTerms));
                    }

                    $user->setLogin(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
                    $user->setFirstname(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING));
                    $user->setSurname(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
                    $user->setStatus(1);
                    $user->save();

                    TokensQuery::create()->filterByValue($urlToken)->delete();

                    exit(json_encode([
                        'success' => true,
                        'message' => Dictionary::init()['success']
                    ]));
                }

                exit(json_encode([
                    'success' => true,
                    'message' => Dictionary::init()['activation_link_invalid']
                ]));
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
