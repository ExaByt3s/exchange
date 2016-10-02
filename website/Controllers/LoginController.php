<?php

    namespace Controllers {

        use Sessions;
        use Tokens;
        use Users;

        class LoginController {

            public function __construct($app) {
                $this->app = $app;
            }

            public function login() {

                $users = new Users();
                $validation = json_decode($users->loginValidate());

                if (! $validation->success) {
                    echo json_encode($validation);
                } else {
                    echo json_encode([ 'success' => true ]);
                }

                exit();
            }

            public function logout() {
                $session = new Sessions();
                $session->remove();

                header('Location: ' . $this->app->route->getContainer()->get('router')->pathFor('home.index'));
                exit();
            }

        }

    }
