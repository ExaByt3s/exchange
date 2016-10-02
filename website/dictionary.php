<?php

    class Dictionary {

        public static function init(){
            return [
                'error404' => 'Error 404!<br>The page you are trying to visit does not exist! Check the address and try again.<br>If the problem persists, contact us.',

                'cannot-transfer_money' => 'We cannot transfer money. Check form and try again!',
                'money_transferred' => 'Money was successfully transferred!',

                'created_account_email_send' => 'Your account was successfully created!<br>We send activation link to you via email.',
                'created_account_email_not_send' => 'Your account was successfully created!<br>We CANNOT sent activation link to you via email. Please contact with us!',

                'activation_link_invalid' => 'Your request is invalid!<br>Your link is broken or expired. Check the address and try again.',

                'too_short_password' => 'The password must be longer than 6 characters!',
                'different_password' => 'Given passwords are different!',

                'give_username' => 'Give your username',
                'username_exist' => 'Given username already exists!',
                'give_firstname' => 'Give your first name!',
                'give_surname' => 'Give your surname!',
                'accept_terms' => 'You must accept our Terms!',

                'username_not_exist' => 'User does not exist!',
                'give_password' => 'Give the password for your account!',
                'password_incorrect' => 'Given password is incorrect!',
                'inactive_account' => 'Your account is inactive!'
            ];
        }
    }