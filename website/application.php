<?php

    use Slim\App;
    use Slim\Container;

    class Application {
        public $route;
        public $routeContainer;
        public $twig;
        public $template;
        public $nonce;
        public $exchangeRateAPIUrl = 'http://webtask.future-processing.com:8068/currencies';


        public function __construct() {
            $this->initSlim();
            $this->initTwig();
        }


        /**
         * Initializes slim routing and bind custom 404 pages.
         */
        private function initSlim() {
            $this->routeContainer = new Container();

            $routeContainer = $this->routeContainer;

            $routeContainer['notFoundHandler'] = function ($routeContainer) {
                return function() use ($routeContainer) {
                    $this->template->variables['website_page'] = $this->twig->render('error.twig', ['message' => Dictionary::init()['error404']]);

                    return $routeContainer['response']
                        ->withStatus(404)
                        ->withHeader('Content-Type', 'text/html');
                };
            };

            $this->route = new App($this->routeContainer);
        }


        /**
         * Returns path for specified route name.
         *
         * @param string $routeName
         * @param array $params
         * @return string
         */
        public function getRouteUrl($routeName, $params = []) {
            return $this->route->getContainer()->get('router')->pathFor($routeName, $params);
        }


        /**
         * Initializez Twig.
         */
        private function initTwig() {
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/');
            $this->twig = new Twig_Environment($loader, array('autoescape'=>false));

            $this->template = $this->twig->loadTemplate('index.twig');
        }


        /**
         * Sends email via Gmail.
         *
         * @param string $recipient
         * @param string $subject
         * @param string $content
         * @return bool
         */
        public function sendEmail($recipient, $subject, $content) {
            $mail = new PHPMailer(true);

            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->Username = "exchangeonlinefp@gmail.com";
            $mail->Password = "exchangeonlinefppass";
            $mail->isHTML(true);

            $mail->AddAddress($recipient, $recipient);
            $mail->SetFrom('exchangeonlinefp@from.com', 'Exchange Online');
            $mail->Subject = $subject;
            $mail->Body = $content;

            try{
                $mail->Send();
                return true;
            } catch(Exception $e){
                return false;
            }
        }


        /**
         * Generates and returns nonce string for inline scripts in templates.
         *
         * @return string
         */
        public function getScriptNonce() {
            if (empty($this->nonce)) {
                $this->nonce = hash('sha256', SALT . rand(0, time()));
            }

            return $this->nonce;
        }
    }
