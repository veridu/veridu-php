<?php

namespace Veridu\API;

use Veridu\Exception;

final class Application extends AbstractEndpoint {

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Application_Resource#How_to_create_a_new_hosted_application
	*
	* @return self
	*/
	public function create($provider, $key, $secret) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Application_Resource#How_to_retrieve_a_list_of_all_hosted_applications
	*
	* @return array
	*/
	public function listAll() {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Application_Resource#How_to_get_a_detailed_information_about_a_hosted_application
	*
	* @return array
	*/
	public function details($appId) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Application_Resource#How_to_enable.2Fdisabled_a_hosted_application
	*
	* @return self
	*/
	public function enable($appId) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Application_Resource#How_to_enable.2Fdisabled_a_hosted_application
	*
	* @return self
	*/
	public function disable($appId) {
	}

}
