<?php

use Base\Tokens as BaseTokens;

class Tokens extends BaseTokens {

    /**
     * Creates token for user.
     * Token types: 1-activation, 2-session
     *
     * @param int $userId
     * @param int $tokenType
     * @return string
     */
    public function generateToken($userId, $tokenType) {
        $randomToken = hash('sha256', rand(0, time()) . SALT . rand(0, time()));

        $token = new Tokens();
        $token->setUserId($userId);
        $token->setType($tokenType);
        $token->setValue($randomToken);
        $token->save();

        return $randomToken;
    }


    /**
     * Returns user id related with specified token.
     *
     * @param {int} $tokenType
     * @param {string} $tokenValue
     * @param {int|null} $userId
     * @return bool
     */
    public function getUserIdByToken($tokenType, $tokenValue, $userId = null) {
        $token = TokensQuery::create()->findOneByValue($tokenValue);

        if (isset($token)) {
            $token = $token->toArray();
            if ($token['Type'] === $tokenType) {
                $user = UsersQuery::create()->findOneById($token['UserId'])->toArray();

                if ($userId === null || $user['Id'] === $userId) {
                    return $user['Id'];
                }
            }
        }

        return false;
    }

}
