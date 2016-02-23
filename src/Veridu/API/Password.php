<?php

namespace Veridu\API;

use Veridu\Exception;

final class Password extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Password_Resource#How_to_create_a_new_user_using_SSO
	*
	* @return self
	*/
	public function signup($firstName, $lastName, $email, $password, $mergeHash = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Password_Resource#How_to_login_a_user_using_SSO
	*/
	public function login($email, $password) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Password_Resource#First_Step
	*
	* @return self
	*/
	public function recover($email, $callbackURL) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Password_Resource#Second_Step
	*/
	public function reset($recoverHash, $password) {
	}

}
