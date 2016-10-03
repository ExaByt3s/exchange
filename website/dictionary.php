<?php

    class Dictionary {

        public static function init(){
            return [
                'success' => 'Success!',
                'error404' => 'Error 404!<br>The page you are trying to visit does not exist! Check the address and try again.<br>If the problem persists, contact us.<br><br><a href="/" class="btn btn-primary">Homepage</a>',

                'cannot-transfer_money' => 'We cannot transfer money. Check form and try again!',
                'money_transferred' => 'Money was successfully transferred!',

                'created_account_email_send' => 'Your account was successfully created!<br>We send activation link to you via email.',
                'created_account_email_not_send' => 'Your account was successfully created!<br>We CANNOT sent activation link to you via email. Please contact with us!',

                'activation_link_invalid' => 'Your request is invalid!<br>Your link is broken or expired. Check the address and try again.',

                'give_email' => 'Give your real email address!',
                'email_too_long' => 'Email should be shorter than 65 characters!',
                'email_exists' => 'Given email already exists!',
                'too_short_password' => 'The password must be longer than 6 characters!',
                'different_password' => 'Given passwords are different!',

                'give_username' => 'Username should be longer than 3 characters!',
                'username_too_long' => 'Username should be shorter than 33 characters!',
                'username_exists' => 'Given username already exists!',
                'give_firstname' => 'Give your first name!',
                'firstname_too_long' => 'Surname cannot be longer than 32 characters!',
                'give_surname' => 'Give your surname!',
                'surname_too_long' => 'Surname cannot be longer than 32 characters!',
                'accept_terms' => 'You must accept our Terms!',

                'username_not_exists' => 'User does not exist!',
                'give_password' => 'Give the password for your account!',
                'password_incorrect' => 'Given password is incorrect!',
                'inactive_account' => 'Your account is inactive!',

                'data_changed' => 'Your account data has been changed!',
                'password_changed' => 'Your password has been changed!',

                'no_api_connection' => 'Failed! External error. We cannot update the exchange rate! Please try later.',
                'exchange_rate_too_old' => 'Failed! Exchange rate has changed! Please try again.',
                'exchange_unit_error' => 'Failed! You can replace only the amount which is a multiple of currency unit.',
                'too_little_in_wallet' => 'Failed! You do not have enough funds in the selected currency.<br>Please enter a smaller amount and try again.',
                'too_little_pln_in_wallet' => 'Failed! You do not have enough funds to buy selected currency.<br>Please enter a smaller amount and try again.',
                'too_little_in_exchange' => 'Failed! We cannot exchange now that amount.<br>Please enter a smaller amount and try again.',
            ];
        }
    }