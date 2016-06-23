<?php

namespace Veridu\API;

use Veridu\Exception;

 class Cloned extends AbstractEndpoint {

	/**
	* Details about user clones
	*
	* @link https://veridu.com/wiki/Clone_Resource#How_to_retrieve_details_about_user.27s_clones
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);
		$json = $this->signedFetch(
				'GET',
				sprintf(
					'clone/%s',
					$username
				)
		);
		return $json['clones'];
	}

}
