<?php

    class Sessions {

        public function create($userId) {
            $token = new Tokens();

            $_SESSION['userId'] = $userId;
            $_SESSION['token'] = $token->generateToken($userId, 2);
        }


        public function check() {
            $token = new Tokens();

            if (isset($_SESSION['token']) && isset($_SESSION['userId'])) {
                if (! $token->getUserIdByToken(2, $_SESSION['token'], $_SESSION['userId'])) {
                    $this->remove();
                } else {
                    return $_SESSION['userId'];
                }
            }

            return false;
        }


        public function remove() {
            if (isset($_SESSION['token'])) {
                \TokensQuery::create()->filterByValue($_SESSION['token'])->delete();
            }

            session_destroy();
        }

    }
