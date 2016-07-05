<?php

namespace Veridu\API;

use Veridu\Exception;

class Personal extends AbstractEndpoint
{
    const FILTER_STATE = 'state';
    const FILTER_VALUES = 'values';
    const FILTER_FIELDS = 'fields';

    /**
    * Creates one or more entries for the given user
    *
    * @link https://veridu.com/wiki/Personal_Resource#How_to_create_one_or_more_entries_for_the_given_user
    *
    * @return integer Number of created fields
    */
    public function create(array $data)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
                'POST',
                sprintf(
                    'personal/%s',
                    $username
                ),
                $data
        );
        return $json['fields'];
    }

    /**
    * Retrieves all form entries from a given user
    *
    * @link https://veridu.com/wiki/Personal_Resource#How_to_retrieve_all_form_entries_from_a_given_user
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function details($filter = Personal::FILTER_STATE, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $json = $this->fetch(
                'GET',
                sprintf(
                    'personal/%s/%s',
                    $username,
                    $filter
                )
        );
        return $json[$filter];
    }

    /**
    * Updates one or more entries for the given user
    *
    * @link https://veridu.com/wiki/Personal_Resource#How_to_update_one_or_more_entries_for_the_given_user
    *
    * @return integer Number of updated fields
    */
    public function update(array $data)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->fetch(
                'PUT',
                sprintf(
                    'personal/%s',
                    $username
                ),
                $data
        );
        return $json['fields'];
    }
}
