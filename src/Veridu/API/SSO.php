<?php

namespace Veridu\API;

use Veridu\Exception;

final class SSO extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/SSO_Resource#How_to_do_a_social_single_sign_on
	*
	* @return string User identification
	*/
	public function createOAuth1($provider, $token, $secret, $mergeHash = null) {
	}

	/**
	*
	* @link https://veridu.com/wiki/SSO_Resource#How_to_do_a_social_single_sign_on
	*
	* @return string User identification
	*/
	public function createOAuth2($provider, $token, $refresh = null, $mergeHash = null) {
	}

}
