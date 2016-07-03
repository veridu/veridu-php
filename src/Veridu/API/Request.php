<?php

namespace Veridu\API;

use Veridu\Exception;

class Request extends AbstractEndpoint
{
    const FILTER_ALL = 'all';
    const FILTER_VERIFICATION = 'verification';

    /**
    * Creates a new request
    *
    * @link https://veridu.com/wiki/Request_Resource#How_to_create_a_new_request
    *
    * @param string $usernameTo User identification
    *
    * @return self
    */
    public function create($usernameTo, $type, $message = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $this->fetch(
            'POST',
            sprintf(
                'request/%s/%s/%s',
                $username,
                $usernameTo,
                $type
            ),
            array(
                'message' => $message
            )
        );
        return $this;
    }

    /**
    * Retrieves the number of unread and total count of requests sent to the given user
    *
    * @link https://veridu.com/wiki/Request_Resource#How_to_retrieve_the_number_of_unread_and_total_count_of_requests_sent_to_the_given_user
    *
    * @return array
    */
    public function stats()
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
                'GET',
                sprintf(
                    'request/%s',
                    $username
                )
        );
        return $json;
    }

    /**
    * Lists requests sent to the given user
    *
    * @link https://veridu.com/wiki/Request_Resource#How_to_retrieve_the_listing_of_requests_sent_to_the_given_user
    *
    * @return array
    */
    public function retrieve($filter = Request::FILTER_ALL, $maxId = null, $count = 10)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);

        $json = $this->fetch(
                'GET',
                sprintf(
                    'request/%s/%s',
                    $username,
                    $filter
                ),
                array(
                    'count' => $count,
                    'max_id' => $maxId
                )
        );
        return $json['list'];
    }
}
