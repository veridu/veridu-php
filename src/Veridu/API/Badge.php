<?php

namespace Veridu\API;

use Veridu\Exception;

final class Badge extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_assign_a_badge_to_a_given_user
	*
	* @param string $username User identification
	*
	* @return self
	*/
	public function create($username, $badge, $timestamp, $failed = false, array $attributes = array()) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_retrieve_a_list_of_badges_for_a_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function listAll($username = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Badge_Resource#How_to_retrieve_the_current_status_for_the_given_badge
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($badge, $username = null) {
	}

}
