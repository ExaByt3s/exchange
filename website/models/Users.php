<?php

use Base\Users as BaseUsers;

class Users extends BaseUsers {

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


    public function createAccount() {
        $wallet = new Wallets();
        //$wallet->setPln(100);
        $wallet->save();

        $user = new Users();
        $user->setEmail(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $user->setPassword(hash('sha256', SALT . $_POST['password']));
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
                'message' => Dictionary::init()['username_exist']
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


    public function loginValidate() {
        if (! isset($_POST['login']) || empty($_POST['login'])) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_username']
            ]);
        }

        $exist = $this->checkIfUserExist($_POST['login'], null);

        if (! $exist) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['username_not_exist']
            ]);
        }

        if (! isset($_POST['password']) || empty($_POST['password'])) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['give_password']
            ]);
        }

        $user = \UsersQuery::create()->findOneByLogin($_POST['login'])->toArray();

        if (hash('sha256', SALT . $_POST['password']) !== $user['Password']) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['password_incorrect']
            ]);
        }

        if ($user['Status'] === 0) {
            return json_encode([
                'success' => false,
                'message' => Dictionary::init()['inactive_account']
            ]);
        }

        $session = new Sessions();
        $session->create($user['Id']);

        return json_encode([
            'success' => true
        ]);
    }

}
