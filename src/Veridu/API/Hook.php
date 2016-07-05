<?php

namespace Veridu\API;

use Veridu\Exception;

class Hook extends AbstractEndpoint
{

    /**
    * Creates a new hook
    *
    * @link https://veridu.com/wiki/Hook_Resource#How_to_create_a_new_Hook
    *
    * @return self
    */
    public function create($trigger, $callbackURL)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $this->signedFetch(
            'POST',
            'hook/',
            array(
                'trigger' => $trigger,
                'url' => $callbackURL
            )
        );
        return $this;
    }

    /**
    * Lists hooks
    *
    * @link https://veridu.com/wiki/Hook_Resource#How_to_retrieve_a_list_of_hooks
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
                'hook/'
        );
        return $json['list'];
    }

    /**
    * Retrieves detailed information about a hook
    *
    * @link https://veridu.com/wiki/Hook_Resource#How_to_retrieve_detailed_information_about_a_hook
    *
    * @return array
    */
    public function details($hookId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
                'GET',
                sprintf(
                    'hook/%s',
                    $hookId
                )
        );
        return $json['details'];
    }

    /**
    * Does something
    *
    * @link https://veridu.com/wiki/Hook_Resource#How_to_delete_a_hook
    *
    * @return array
    */
    public function delete($hookId)
    {
        $this->validateNotEmptySessionOrFail();
        $username = $this->storage->getUsername();
        self::validateNotEmptyUsernameOrFail($username);
        $json = $this->signedFetch(
                'DELETE',
                sprintf(
                    'hook/%s',
                    $hookId
                )
        );
        return $json['status'];
    }
}
