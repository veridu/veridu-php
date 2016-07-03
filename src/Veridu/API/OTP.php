<?php

namespace Veridu\API;

use Veridu\API\Exception;

class OTP extends AbstractEndpoint
{

    /**
    * Lists all OTP methods a given user has ued to verify himself
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_retrieve_a_list_of_all_OTP_methods_a_give_user_has_used_to_verify_himself
    *
    * @param string $username User identification
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function listAll($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $json = $this->fetch(
            'GET',
            sprintf(
                'otp/%s',
                $username
            )
        );

        return $json['list'];
    }

    /**
    * Creates email under a given user 
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_create_an_OTP_method_under_a_given_user
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function createEmail($email, $extended = false, $callbackURL = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
            'POST',
            sprintf(
                'otp/%s/email',
                $username
            ),
            array(
                'email' => $email,
                'extended' => $extended,
                'url' => $callbackURL
            )
        );

        return $json;
    }

    /**
    * Creates SMS under a given user
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_create_an_OTP_method_under_a_given_user
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function createSMS($phone)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
            'POST',
            sprintf(
                'otp/%s/sms',
                $username
            ),
            array(
                'phone' => $phone
            )
        );

        return $json;
    }

    /**
    * Resends email under a given user
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_create_an_OTP_method_under_a_given_user
    *
    * @return self
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function resendEmail($email)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->fetch(
            'POST',
            sprintf(
                'otp/%s/email',
                $username
            ),
            array(
                'email' => $email,
                'resend' => true
            )
        );

        return $this;
    }

    /**
    * Resends sms under a given user
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_create_an_OTP_method_under_a_given_user
    *
    * @return self
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function resendSMS($phone)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
            'POST',
            sprintf(
                'otp/%s/sms',
                $username
            ),
            array(
                'phone' => $phone,
                'resend' => true
            )
        );

        return $this;
    }

    /**
    * Check if the given user has verified the given email
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_retrieve_if_the_given_user_has_verified_the_given_value_using_the_given_OTP_method_name
    *
    * @return boolean
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function checkEmail($email)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'otp/%s/email',
                $username
            ),
            $email
        );

        return ($json['state'] === true);
    }

    /**
    * Check if the given user has verified the given SMS
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_retrieve_if_the_given_user_has_verified_the_given_value_using_the_given_OTP_method_name
    *
    * @return boolean
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function checkSMS($phone)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->fetch(
            'GET',
            sprintf(
                'otp/%s/sms',
                $username
            ),
            $phone
        );

        return ($json['state'] === true);
    }

    /**
    * Verifies email under a given user
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_verify_the_OTP_method_under_a_given_user
    *
    * @return self
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function verifyEmail($email, $code)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $this->fetch(
            'PUT',
            sprintf(
                'otp/%s/email',
                $username
            ),
            array(
                'email' => $email,
                'code' => $code
            )
        );

        return $this;
    }

    /**
    * Verifies SMS under a given user
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_verify_the_OTP_method_under_a_given_user
    *
    * @return self
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function verifySMS($phone, $code)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $this->fetch(
            'PUT',
            sprintf(
                'otp/%s/sms',
                $username
            ),
            array(
                'phone' => $phone,
                'code' => $code
            )
        );

        return $this;
    }

    /**
    * Checks if the given user has used email to verify himself
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_retrieve_if_the_given_user_has_used_the_given_OTP_method_to_verify_himself
    *
    * @param string $username User identification
    *
    * @return boolean
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function verifiedEmail($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $json = $this->fetch(
            'GET',
            sprintf(
                'otp/%s/email',
                $username
            )
        );

        return ($json['state'] === true);
    }

    /**
    * Checks if the given user has used SMS to verify himself
    *
    * @link https://veridu.com/wiki/OTP_Resource#How_to_retrieve_if_the_given_user_has_used_the_given_OTP_method_to_verify_himself
    *
    * @param string $username User identification
    *
    * @return boolean
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function verifiedSMS($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $json = $this->fetch(
            'GET',
            sprintf(
                'otp/%s/sms',
                $username
            )
        );

        return ($json['state'] === true);
    }
}
