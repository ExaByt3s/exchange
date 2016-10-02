<?php

    namespace Controllers {

        class HomeController {

            public function __construct($app) {
                $this->app = $app;
            }

            public function index() {
                $this->app->template->variables['website_page'] = $this->app->twig->render('home.twig');
            }

        }

    }
