<?php

namespace Veridu\API;

use Veridu\API\Exception;

class Task extends AbstractEndpoint {

	/**
	* Retrieves the active task list for the given user
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
	* Retrieve info about a given task for the given user
	*
	* @link https://veridu.com/wiki/Task_Resource#How_to_retrieve_information_about_a_given_task_for_the_given_user
	*
	* @return array
	*/
	public function details($taskId) {
		$this->validateNotEmptySessionOrFail();
		$username = $this->storage->getUsername();
		self::validateNotEmptyUsernameOrFail($username);
		$json = $this->fetch(
			'GET',
			sprintf(
				'task/%s/%d/',
				$username,
				$taskId
			)
		);

		return ($json['info']);
	}
}