<?php

use Base\Users as BaseUsers;

class Users extends BaseUsers {

    /**
     * Validates username.
     *
     * @param string $username
     * @param bool $login
     * @return string
     */
    public function validateUsername ($username, $login = false) {
        if (strlen(filter_var($username, FILTER_SANITIZE_STRING)) < 3) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_username']
            ]);
        }

        if (strlen(filter_var($username, FILTER_SANITIZE_STRING)) > 32) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['username_too_long']
            ]);
        }

        $usernameExist = $this->checkIfUserExist($username, null);

        if ($login && ! $usernameExist) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['username_not_exists']
            ]);
        }

        if (! $login && $usernameExist) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['username_exists']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates email address.
     *
     * @param string $email
     * @param bool $registration
     * @return string
     */
    public function validateEmail ($email, $registration = false) {
        if (strlen(filter_var($email, FILTER_SANITIZE_EMAIL)) < 3) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_email']
            ]);
        }

        if (strlen(filter_var($email, FILTER_SANITIZE_EMAIL)) > 64) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['email_too_long']
            ]);
        }

        if ($registration && $this->checkIfUserExist(null, $email)) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['email_exists']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates passwords.
     *
     * @param string $password
     * @param string $password2
     * @param bool $login
     * @return string
     */
    public function validatePassword ($password, $password2, $login = false) {
        if (strlen($password) <= 6) {
            $message = (($login) ? 'give_password' : 'too_short_password');

            return json_encode([
                'success' => false,
                'message' => Dictionary::init()[$message]
            ]);
        }

        $password = ($login) ? hash('sha256', SALT . $password) : $password;

        if ($password !== $password2) {
            $message = (($login) ? 'password_incorrect' : 'different_password');

            return json_encode([
                'success' => false,
                'message' => Dictionary::init()[$message]
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates terms acceptation.
     *
     * @param string $terms
     * @return string
     */
    public function validateTerms ($terms) {
        if ($terms !== 'on') {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['accept_terms']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates first name.
     *
     * @param string $firstname
     * @return string
     */
    public function validateFirstname ($firstname) {
        if (strlen(filter_var($firstname, FILTER_SANITIZE_STRING)) < 1) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_firstname']
            ]);
        }

        if (strlen(filter_var($firstname, FILTER_SANITIZE_STRING)) > 32) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['firstname_too_long']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates surname.
     *
     * @param string $surname
     * @return string
     */
    public function validateSurname ($surname) {
        if (strlen(filter_var($surname, FILTER_SANITIZE_STRING)) < 1) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_surname']
            ]);
        }

        if (strlen(filter_var($surname, FILTER_SANITIZE_STRING)) > 32) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['surname_too_long']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates user account status.
     *
     * @param integer $userId
     * @return string
     */
    public function validateAccountStatus($userId) {
        $user = UsersQuery::create()->findOneById($userId)->toArray();

        if ($user['Status'] <= 0) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['inactive_account']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Validates via username or email that user exists.
     *
     * @param string $login
     * @param string|null $email
     * @return bool|int
     */
    public function checkIfUserExist($login, $email = null) {
        if ($login !== null) {
            return count(UsersQuery::create()->findByLogin($login));
        }

        if ($email !== null) {
            return count(UsersQuery::create()->findByEmail($email));
        }

        return false;
    }


    public function registerValidate() {
        if (! isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return json_encode([
                'success' => false,
                'message' => 'Give your real email address!'
            ]);
        }

        $exist = $this->checkIfUserExist(null, filter_var($_POST['email'], FILTER_SANITIZE_STRING));

        if ($exist) {
            return json_encode([
                'success' => false,
                'message' => 'Given email already exists!'
            ]);
        }

        if (! isset($_POST['password']) || empty($_POST['password'])) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_password']
            ]);
        }

        if (strlen($_POST['password']) <= 6) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['too_short_password']
            ]);
        }

        if (! isset($_POST['password2']) || empty($_POST['password2']) || $_POST['password'] !== $_POST['password2']) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['different_password']
            ]);
        }

        if (! isset($_POST['terms']) || $_POST['terms'] !== 'on') {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['accept_terms']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }


    /**
     * Creates new, inactive user account.
     *
     * @param string $email
     * @param string $password
     * @return int
     */
    public function createAccount($email, $password) {
        $wallet = new Wallets();
        $wallet->save();

        $user = new Users();
        $user->setEmail(filter_var($email, FILTER_SANITIZE_STRING));
        $user->setPassword(hash('sha256', SALT . $password));
        $user->setWalletId($wallet->getId());
        $user->setStatus(0);
        $user->save();

        return $user->getId();
    }


    public function activationValidate() {
        if (! isset($_POST['login']) || empty($_POST['login'])) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_username']
            ]);
        }

        $exist = $this->checkIfUserExist(filter_var($_POST['login'], FILTER_SANITIZE_STRING), null);

        if ($exist) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['username_exists']
            ]);
        }

        if (! isset($_POST['firstname']) || empty(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING))) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_firstname']
            ]);
        }

        if (! isset($_POST['surname']) || empty(filter_var($_POST['surname'], FILTER_SANITIZE_STRING))) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_surname']
            ]);
        }

        if (! isset($_POST['terms']) || $_POST['terms'] !== 'on') {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['accept_terms']
            ]);
        }

        return json_encode([
            'success' => true
        ]);
    }
}
