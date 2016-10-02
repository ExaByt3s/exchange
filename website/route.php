<?php

    $app = $application->route;

    // Homepage
    $app->get('/', function() use($application) {
        $controller = new controllers\HomeController($application);
        $controller->index();
    })->setName('home.index');


    // Registration and account activation
    $app->group('/register', function() use($app, $application) {
        $app->post('/register', function () use($app, $application) {
            $controller = new controllers\RegisterController($application);
            $controller->registry();
        })->setName('register.register');

         $app->get('/activation/{token}', function($request, $response, $args) use($application) {
            $controller = new controllers\RegisterController($application);
            $controller->activationForm($args['token']);
        })->setName('register.activation');

        $app->post('/activation/{token}', function($request, $response, $args) use($application) {
            $controller = new controllers\RegisterController($application);
            $controller->activation($args['token']);
        })->setName('register.activation.post');
    });


    // Dashboard
    $app->get('/dashboard', function() use($application) {
        $controller = new controllers\DashboardController($application);
        $controller->index();
    })->setName('dashboard.index');


    // Exchange operations
    $app->group('/exchange', function() use($app, $application) {
        $app->get('/purchase/{code}', function($request, $response, $args) use($application) {
            $controller = new controllers\DashboardController($application);
            $controller->purchaseModal($args['code']);
        })->setName('exchange.purchase');

        $app->post('/purchase/{code}', function($request, $response, $args) use($application) {
            $controller = new controllers\DashboardController($application);
            $controller->purchase($args['code']);
        })->setName('exchange.purchase.post');

        $app->get('/sale/{code}', function($request, $response, $args) use($application) {
            $controller = new controllers\DashboardController($application);
            $controller->saleModal($args['code']);
        })->setName('exchange.sell');

        $app->post('/sale/{code}', function($request, $response, $args) use($application) {
            $controller = new controllers\DashboardController($application);
            $controller->sale($args['code']);
        })->setName('exchange.sell.post');
    });


    // User account
    $app->group('/account', function() use($app, $application) {
        $app->get('/edit', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->index();
        })->setName('account.index');

        $app->post('/login', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->login();
        })->setName('login');

        $app->post('/transfer', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->transferMoney();
        })->setName('account.transfer');

        $app->post('/changePassword', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->changePassword();
        })->setName('account.change.password');

        $app->post('/changeData', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->changeData();
        })->setName('account.change.data');

        $app->get('/logout', function() use($application) {
            $controller = new controllers\AccountController($application);
            $controller->logout();
        })->setName('logout');
    });


    $app->run();
