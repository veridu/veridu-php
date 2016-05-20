<?php

namespace Veridu\API;

use Veridu\Exception;

 class Backplane extends AbstractEndpoint {

	/**
	* Setup a blackplane profile
	*
	* @link https://veridu.com/wiki/Backplane_Resource#How_to_setup_a_client_as_a_Backplane_Profile
	*
	* @return self
	*/
	public function setup($channel) {
		$this->validateNotEmptySessionOrFail();
		$username = $this->storage->getUsername() ;
		self::validateUsernameOrFail($username);
		$this->fetch(
			'POST',
			sprintf(
				'backplane/%s',
				$username
			),
			array(
				'channel' => $channel
			)
		);
		return $this;
	}

}
