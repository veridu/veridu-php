<?php

namespace Veridu\API;

use Veridu\API\Exception;

class Task extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Task_Resource#How_to_retrieve_the_active_task_list_for_the_given_user
	*
	*
	* @param string $username
	* @return array
	*/
	public function listAll() {
		$this->validateNotEmptySessionOrFail();

		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);

		$json = $this->fetch(
			'GET',
			sprintf(
				'task/%s',
				$username
			)
		);

		return $json['list'];
	}
	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Task_Resource#How_to_retrieve_information_about_a_given_task_for_the_given_user
	*
	* @return array
	*/
	public function details($taskId) {
		if ($this->storage->isSessionEmpty())
			throw new Exception\EmptySession;

		if ($this->storage->isUsernameEmpty())
			throw new Exception\EmptyUsername;

		$json = $this->fetch(
			'GET',
			sprintf(
				'task/%s/%d/',
				$this->storage->getUsername(),
				$taskId
			)
		);

		return ($json['info'] = [
			'running' => true,
			'status' => true,
			'message' => '',
			]);
	}
}
