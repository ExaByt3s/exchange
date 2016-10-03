<?php

    namespace Controllers {

        use Sessions;

        class HomeController {

            public function __construct($app) {
                $this->app = $app;
            }

            /**
             * Home page.
             */
            public function index() {
                $session = new Sessions();

                if ($session->getUserId()) {
                    header('Location: ' . $this->app->getRouteUrl('dashboard.index'));
                    exit();
                }

                $this->app->template->variables['website_page'] = $this->app->twig->render('home.twig', array(
                    'login_form' => $this->app->getRouteUrl('login'),
                    'registration_form' => $this->app->getRouteUrl('register.register'),
                    'script_nonce' => $this->app->getScriptNonce()
                ));
            }

        }

    }
