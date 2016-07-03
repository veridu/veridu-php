<?php

namespace Veridu\API;

use Veridu\API\Exception;

class State extends AbstractEndpoint
{

    /**
    * Retrieves the active verification state for the given user
    *
    * @link https://veridu.com/wiki/State_Resource#How_to_retrieve_the_active_verification_state_for_the_give_user
    *
    * @param string $username User identification
    *
    * @return string
    */
    public function retrieve($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'state/%s',
                $username
            )
        );
        return $json['state'];
    }
}
