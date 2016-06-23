<?php

namespace Veridu\API;

use Veridu\Exception;

 class Batch extends AbstractEndpoint {

	/**
	* Performs a batch request
	*
	* @link https://veridu.com/wiki/Batch_Resource#How_to_perform_a_batch_request
	*
	* @return array
	*/
	public function send($jobs = array()) {
		$this->validateNotEmptySessionOrFail();
		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);
		$json = $this->fetch(
				'POST',
				'batch/',
				array(
					'jobs' => json_encode($jobs)
				)
		);

		return $json['batch'];
	}

}
