<?php

namespace Veridu\API;

use Veridu\API\Exception;

class OTP extends AbstractEndpoint {

	/**
	* Does something
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
	public function listAll($username = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new Exception\EmptyUsername;
			$username = $this->storage->getUsername();
			
		} else if (!self::validateUsername($username))
			throw new Exception\InvalidUsername;

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
	* Does something
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
	public function createEmail($email, $extended = false, $callbackURL = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'POST',
			sprintf(
				'otp/%s/email',
				$this->storage->getUsername()
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
	* Does something
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
	public function createSMS($phone) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'POST',
			sprintf(
				'otp/%s/sms',
				$this->storage->getUsername()
			),
			array(
				'phone' => $phone
			)
		);

		return $json;
	}

	/**
	* Does something
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
	public function resendEmail($email) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'POST',
			sprintf(
				'otp/%s/email',
				$this->storage->getUsername()
			),
			array(
				'email' => $email,
				'resend' => true
			)
		);

		return $this;
	}

	/**
	* Does something
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
	public function resendSMS($phone) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'POST',
			sprintf(
				'otp/%s/sms',
				$this->storage->getUsername()
			),
			array(
				'phone' => $phone,
				'resend' => true
			)
		);

		return $this;
	}

	/**
	* Does something
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
	public function checkEmail($email) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'GET',
			sprintf(
				'otp/%s/email',
				$this->storage->getUsername()
			),
			$email
		);

		return ($json['state'] === true);
	}

	/**
	* Does something
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
	public function checkSMS($phone) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'GET',
			sprintf(
				'otp/%s/sms',
				$this->storage->getUsername()
			),
			$phone
		);

		return ($json['state'] === true);
	}

	/**
	* Does something
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
	public function verifyEmail($email, $code) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$this->fetch(
			'PUT',
			sprintf(
				'otp/%s/email',
				$this->storage->getUsername()
			),
			array(
				'email' => $email,
				'code' => $code
			)
		);

		return $this;
	}

	/**
	* Does something
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
	public function verifySMS($phone, $code) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$this->fetch(
			'PUT',
			sprintf(
				'otp/%s/sms',
				$this->storage->getUsername()
			),
			array(
				'phone' => $phone,
				'code' => $code
			)
		);

		return $this;
	}

	/**
	* Does something
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
	public function verifiedEmail($username = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new Exception\EmptyUsername;
			$username = $this->storage->getUsername();
		} else if (!self::validateUsername($username))
			throw new Exception\InvalidUsername;

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
	* Does something
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
	public function verifiedSMS($username = null) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if (empty($username)) {
			if ($this->storage->isUsernameEmpty())
				throw new Exception\EmptyUsername;
			$username = $this->storage->getUsername();
		} else if (!self::validateUsername($username))
			throw new Exception\InvalidUsername;

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
