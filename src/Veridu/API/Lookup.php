<?php

namespace Veridu\API;

use Veridu\Exception;

class Lookup extends AbstractEndpoint
{

    /**
    * Retrieves full information of a postcode
    *
    * @link https://veridu.com/wiki/Lookup_Resource#How_to_retrieve_the_full_information_of_a_postcode
    *
    * @return array
    */
    public function search($region, $postcode)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->fetch(
                'GET',
                sprintf(
                    'lookup/%s?postcode=%s',
                    $region,
                    $postcode
                )
        );
        return $json['results'];
    }

    /**
    * Retrieves the full information of a known address
    *
    * @link https://veridu.com/wiki/Lookup_Resource#How_to_retrieve_the_full_information_of_a_known_address
    *
    * @return array
    */
    public function details($region, $lookupId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->fetch(
                'GET',
                sprintf(
                    'lookup/%s/%s',
                    $region,
                    $lookupId
                )
        );
        return $json['info'];
    }
}
