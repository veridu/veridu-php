<?php

namespace Veridu\API;

use Veridu\Exception;

final class Request extends AbstractEndpoint {
	const FILTER_ALL = 'all';
	const FILTER_VERIFICATION = 'verification';

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Request_Resource#How_to_create_a_new_request
	*
	* @param string $usernameTo User identification
	*
	* @return self
	*/
	public function create($usernameTo, $type, $message = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Request_Resource#How_to_retrieve_the_number_of_unread_and_total_count_of_requests_sent_to_the_given_user
	*
	* @return array
	*/
	public function stats() {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Request_Resource#How_to_retrieve_the_listing_of_requests_sent_to_the_given_user
	*
	* @return array
	*/
	public function retrieve($filter = Request::FILTER_ALL, $maxId = null, $count = 10) {
	}

}
