<?php

    namespace Controllers {

        class HomeController {

            public function __construct($app) {
                $this->app = $app;
            }

            public function index() {
                $this->app->template->variables['website_page'] = $this->app->twig->render('home.twig', array(
                    'login_form' => BASE_URL . $this->app->route->getContainer()->get('router')->pathFor('login'),
                    'registration_form' => BASE_URL . $this->app->route->getContainer()->get('router')->pathFor('register.register')
                ));
            }

        }

    }
