<?php

namespace Veridu\API;

use Veridu\Exception;

final class Hook extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Hook_Resource#How_to_create_a_new_Hook
	*
	* @return self
	*/
	public function create($trigger, $callbackURL) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Hook_Resource#How_to_retrieve_a_list_of_hooks
	*
	* @return array
	*/
	public function listAll() {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Hook_Resource#How_to_retrieve_detailed_information_about_a_hook
	*
	* @return array
	*/
	public function details($hookId) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Hook_Resource#How_to_delete_a_hook
	*
	* @return array
	*/
	public function delete($hookId) {
	}

}
