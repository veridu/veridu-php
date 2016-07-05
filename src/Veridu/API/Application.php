<?php

namespace Veridu\API;

use Veridu\Exception;

class Application extends AbstractEndpoint
{

    /**
    * Creates a new hosted application
    *
    * @link https://veridu.com/wiki/Application_Resource#How_to_create_a_new_hosted_application
    *
    * @return self
    */
    public function create($provider, $key, $secret)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
            'POST',
            'application',
            array(
                'key' => $key,
                'secret' => $secret,
                'provider' => $provider
            )
        );
        return $json;
    }

    /**
    * Retrieves a list of all hosted applications
    *
    * @link https://veridu.com/wiki/Application_Resource#How_to_retrieve_a_list_of_all_hosted_applications
    *
    * @return array
    */
    public function listAll()
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
            'GET',
            'application'
        );
        return $json['list'];
    }

    /**
    * Retrieves detailed information about a hosted application
    *
    * @link https://veridu.com/wiki/Application_Resource#How_to_get_a_detailed_information_about_a_hosted_application
    *
    * @return array
    */
    public function details($appId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
            'GET',
            sprintf(
                'application/%s',
                $appId
            )
        );
        return $json;
    }

    /**
    * Enables a hosted application
    *
    * @link https://veridu.com/wiki/Application_Resource#How_to_enable.2Fdisabled_a_hosted_application
    *
    * @return self
    */
    public function enable($appId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $this->signedFetch(
            'PUT',
            sprintf(
                'application/%s',
                $appId
            ),
            array(
                'enabled' => 'true'
            )
        );
        return $this;
    }

    /**
    * Disables a hosted application
    *
    * @link https://veridu.com/wiki/Application_Resource#How_to_enable.2Fdisabled_a_hosted_application
    *
    * @return self
    */
    public function disable($appId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $this->signedFetch(
            'PUT',
            sprintf(
                'application/%s',
                $appId
            ),
            array(
                'enabled' => 'false'
            )
        );
        return $this;
    }
}
