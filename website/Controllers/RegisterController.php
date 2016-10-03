<?php

    namespace Controllers {

        use Dictionary;
        use Tokens;
        use Users;
        use UsersQuery;

        class RegisterController {

            public function __construct($app) {
                $this->app = $app;
            }

            public function registry() {

                $users = new Users();
                $validation = json_decode($users->registerValidate());

                if (! $validation->success) {
                    echo json_encode($validation);
                } else {
                    $userId = $users->createAccount($_POST['email'], $_POST['password']);

                    $tokens = new Tokens();
                    $token = $tokens->generateToken($userId, 1);

                    $email = $this->app->sendEmail(
                        $_POST['email'],
                        'Exchange online - registration',
                        $this->app->twig->render(
                            'email-registration.twig',
                            array(
                                'link' => BASE_URL . $this->app->getRouteUrl('register.activation', ['token' => $token])
                            )
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
                }

                exit();
            }

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

                if (! $success){
                    $this->app->template->variables['website_page'] = $this->app->twig->render('error.twig', [
                        'message' => Dictionary::init()['activation_link_invalid']
                    ]);
                }
            }

            public function activation($urlToken) {
                $token = new Tokens();
                $user = new Users;
                $validation = json_decode($user->activationValidate());
                $userId = $token->getUserIdByToken(1, $urlToken);

                if ($userId) {
                    if ($validation->success) {
                        $user = UsersQuery::create()->findOneById($userId);

                        $user->setLogin(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
                        $user->setSurname(filter_var($_POST['surname'], FILTER_SANITIZE_STRING));
                        $user->setFirstname(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING));
                        $user->setStatus(1);
                        $user->save();

                        \TokensQuery::create()->filterByValue($urlToken)->delete();

                        echo json_encode([ 'success' => true ]);

                    } else {
                        echo json_encode($validation);
                    }

                    exit();

                } else {
                    $this->app->template->variables['website_page'] = $this->app->twig->render('error.twig', [
                        'message' => Dictionary::init()['activation_link_invalid']
                    ]);
                }
            }

        }

    }
