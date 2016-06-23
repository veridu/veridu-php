<?php

namespace Veridu\API;

use Veridu\Exception;

 class Badge extends AbstractEndpoint {

	/**
	* Assigns a badge to a given user
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_assign_a_badge_to_a_given_user
	*
	* @param string $username User identification
	*
	* @return self
	*/
	public function create($username = null, $badge, $timestamp, $failed = false, $attributes = array()) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);
		$this->signedFetch(
				'POST',
				sprintf(
					'badge/%s/%s',
					$username,
					$badge
				),
				array_merge(
					$attributes, [
						'failed' => $failed,
						'ts' => $timestamp
					]
				)
		);
		return $this;
	}

	/**
	* Lists all badges for a given user
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_retrieve_a_list_of_badges_for_a_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);
		$json = $this->fetch(
				'GET',
				sprintf(
					'badge/%s',
					$username
				)
		);
		return $json['list'];
	}

	/**
	* Retrieves the current status for a the given badge
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_retrieve_the_current_status_for_the_given_badge
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($badge, $type = 'state', $username = null) {
		$this->validateNotEmptySessionOrFail();
		$username = empty($username) ? $this->storage->getUsername() : $username;
		self::validateUsernameOrFail($username);
		$json = $this->fetch(
				'GET',
				sprintf(
					'badge/%s/%s',
					$username,
					$badge
				),
				array (
					'type' => $type
				)
		);
		return $json;
	}

}
