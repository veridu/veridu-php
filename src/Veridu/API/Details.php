<?php

namespace Veridu\API;

use Veridu\Exception;

class Details extends AbstractEndpoint
{

    /**
    * Retrieve details of a given user
    *
    * @link https://veridu.com/wiki/Details_Resource#How_to_retrieve_the_details_of_a_given_user
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function retrieve($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $json = $this->fetch(
            'GET',
            sprintf(
                'details/%s',
                $username)
        );

        return $json['list'];
    }
}
