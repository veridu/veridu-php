<?php

namespace Veridu\API;

use Veridu\Exception;

final class Personal extends AbstractEndpoint {
	const FILTER_STATE = 'state';
	const FILTER_VALUES = 'values';
	const FILTER_FIELDS = 'fields';

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Personal_Resource#How_to_create_one_or_more_entries_for_the_given_user
	*
	* @return integer Number of created fields
	*/
	public function create(array $data) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Personal_Resource#How_to_retrieve_all_form_entries_from_a_given_user
	*
	* @param string $username User identification
	*
	* @return array
	*/
	public function details($filter = Personal::FILTER_STATE, $username = null) {
	}

	/**
	* Does something
	*
	* @link https://veridu.com/wiki/Personal_Resource#How_to_update_one_or_more_entries_for_the_given_user
	*
	* @return integer Number of updated fields
	*/
	public function update(array $data) {
	}

}
