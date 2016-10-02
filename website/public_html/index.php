<?php

    $csp = "default-src 'self'; script-src 'self' 'unsafe-eval'; object-src 'none'; style-src 'self'; img-src 'self'; media-src 'self'; child-src 'none'; connect-src 'self' ws://webtask.future-processing.com:8068";

    header("Content-Security-Policy: " . $csp);
    header("X-Content-Security-Policy: " . $csp);
    header("X-WebKit-CSP: " . $csp);

    session_start();

    require_once '../configuration.php';
    require_once '../vendor/autoload.php';

    $application = new Application();

    require_once '../route.php';

    $application->template->variables = array_merge(
        $application->template->variables,
        array(
            'website_title' => WEBSITE_TITLE,
            'base_url' => BASE_URL
        )
    );

    $application->renderTwig();
