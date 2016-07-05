<?php

namespace Veridu\API;

use Veridu\Exception;

class Raw extends AbstractEndpoint
{

    /**
    * Retrieves a user's raw profile data
    *
    * @link https://veridu.com/wiki/Raw_Resource#How_to_retrieve_a_user.27s_raw_profile_data
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function retrieveData($username, $provider = null)
    {
        $this->validateNotEmptySessionOrFail();
        self::validateUsernameOrFail($username);
        $json = $this->signedFetch(
                'GET',
                sprintf(
                    'raw/%s',
                    $username
                ),
                array(
                    'provider' => $provider,
                    'type' => 'data'
                )
        );

        return $json['data'];
    }

    /**
    * Retrieves a user's credential data
    *
    * @link https://veridu.com/wiki/Raw_Resource#How_to_retrieve_a_user.27s_credential_data
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function retrieveCredentials($username, $provider = null)
    {
        $this->validateNotEmptySessionOrFail();
        self::validateUsernameOrFail($username);
        $json = $this->signedFetch(
                'GET',
                sprintf(
                    'raw/%s',
                    $username
                ),
                array(
                    'provider' => $provider,
                    'type' => 'credential'
                )
        );
        return $json['credential'];
    }
}
