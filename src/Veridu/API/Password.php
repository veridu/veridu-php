<?php

namespace Veridu\API;

use Veridu\Exception;

class Password extends AbstractEndpoint
{

    /**
    * Creates a new user using SSO
    *
    * @link https://veridu.com/wiki/Password_Resource#How_to_create_a_new_user_using_SSO
    *
    * @return self
    */
    public function signup($firstName, $lastName, $email, $password, $mergeHash = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $data = array(
                    'fname' => $firstName,
                    'lname' => $lastName,
                    'email' => $email,
                    'password' => $password,
        );
        if ($mergeHash) {
            $data = array_merge($data, ['merge' => $mergeHash]);
        }
        $this->signedFetch(
                'POST',
                'password/signup',
                $data
        );
        return $this;
    }

    /**
    * Logs in a user using SSO
    *
    * @link https://veridu.com/wiki/Password_Resource#How_to_login_a_user_using_SSO
    *
    * @return array
    */
    public function login($email, $password)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
                'POST',
                'password/login',
                array(
                    'email' => $email,
                    'password' => $password
                )
        );
        return $json;
    }

    /**
    * Recovers password (first step)
    *
    * @link https://veridu.com/wiki/Password_Resource#First_Step
    *
    * @return self
    */
    public function recover($email, $callbackURL)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $this->signedFetch(
                'POST',
                'password/recover',
                array(
                    'email' => $email,
                    'url' => $callbackURL
                )
        );
        return $this;
    }

    /**
    * Resets password (second step)
    *
    * @link https://veridu.com/wiki/Password_Resource#Second_Step
    *
    * @return array
    */
    public function reset($recoverHash, $password)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
                'PUT',
                'password',
                array(
                    'recover_hash' => $recoverHash,
                    'password' => $password
                )
        );
        return $json;
    }
}
