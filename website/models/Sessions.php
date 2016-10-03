<?php

    class Sessions {

        private $userId;


        /**
         * Creates new session for user by ID.
         *
         * @param int $userId
         */
        public function create($userId) {
            $token = new Tokens();

            $_SESSION['userId'] = $userId;
            $_SESSION['token'] = $token->generateToken($userId, 2);
        }


        /**
         * Checks if user is logged and returns user ID.
         *
         * @return int|bool
         */
        public function getUserId() {
            if (! empty($this->userId)) return $this->userId;

            $token = new Tokens();

            if (isset($_SESSION['token']) && isset($_SESSION['userId'])) {
                if (! $token->getUserIdByToken(2, $_SESSION['token'], $_SESSION['userId'])) {
                    $this->remove();
                } else {
                    $this->userId = $_SESSION['userId'];
                    return $this->userId;
                }
            }

            return false;
        }


        /**
         * Remove user session and logs out him.
         */
        public function remove() {
            if (isset($_SESSION['token'])) {
                TokensQuery::create()->filterByValue($_SESSION['token'])->delete();
            }

            session_destroy();
        }

    }
