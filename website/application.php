<?php

    class Application {
        public $route;
        public $routeContainer;
        public $twig;
        public $template;
        public $dictionary;


        public function __construct() {
            $this->initSlim();
            $this->initTwig();
            //$this->dictionary = new Dictionary();
        }


        private function initSlim() {
            $this->routeContainer = new \Slim\Container();

            $routeContainer = $this->routeContainer;

            $routeContainer['notFoundHandler'] = function ($routeContainer) {
                return function ($request, $response) use ($routeContainer) {
                    $this->template->variables['website_page'] = $this->twig->render('error.twig', ['message' => Dictionary::init()['error404']]);

                    return $routeContainer['response']
                        ->withStatus(404)
                        ->withHeader('Content-Type', 'text/html');
                };
            };


            $this->route = new \Slim\App($this->routeContainer);
        }


        private function initTwig() {
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/');
            $this->twig = new Twig_Environment($loader, array('autoescape'=>false));

            $this->template = $this->twig->loadTemplate('index.twig');
        }


        public function renderTwig() {
            echo $this->template->render($this->template->variables);
        }


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
    }
