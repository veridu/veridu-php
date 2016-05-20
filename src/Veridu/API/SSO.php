<?php

namespace Veridu\API;

use Veridu\Exception;

 class SSO extends AbstractEndpoint {

	/**
	* Creates a Single sign on under oAuth1
	*
	* @link https://veridu.com/wiki/SSO_Resource#How_to_do_a_social_single_sign_on
	*
	* @return string User identification
	*/
	public function createOAuth1($provider, $token, $secret, $mergeHash = null) {
		$this->validateNotEmptySessionOrFail();
		if ($mergeHash)
			$data = array(
					'token' => $token,
					'secret' => $secret,
					'merge' => $mergeHash
				);
		else
			$data = array (
					'token' => $token,
					'secret' => $secret
				);

		$json = $this->signedFetch(
				'POST',
				sprintf(
					'sso/%s',
					$provider
				),
				$data
		);
		return $json['veridu_id'];
	}

	/**
	* Creates a Single sign on under oAuth2
	*
	* @link https://veridu.com/wiki/SSO_Resource#How_to_do_a_social_single_sign_on
	*
	* @return string User identification
	*/
	public function createOAuth2($provider, $token, $refresh = null, $mergeHash = null) {
		$this->validateNotEmptySessionOrFail();

		$data = array(
				'token' => $token
		);
		if ($refresh)
			array_push($data, 'refresh', $refresh);
		if ($mergeHash)
			array_push($data, 'merge', $mergeHash);

		$json = $this->signedFetch(
				'POST',
				sprintf(
					'sso/%s',
					$provider
				),
				$data
		);
		return $json['veridu_id'];
	}
}
