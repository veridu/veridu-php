<?php

namespace Veridu\API;

use Veridu\Exception;

class Check extends AbstractEndpoint
{
    const NONE = 0x0000;
    const TRACESMART_ALL = 0xFFFF;
    const TRACESMART_ADDRESS = 0x0001;
    const TRACESMART_DOB = 0x0002;
    const TRACESMART_DRIVERLICENSE = 0x0004;
    const TRACESMART_PASSPORT = 0x0008;
    const TRACESMART_CREDITACTIVE = 0x0010;

    const FILTER_ALL = 'all';
    const FILTER_STATE = 'state';
    const FILTER_INFO = 'info';

    private function tracesmartSetup($map)
    {
        $filter = array();

        if (($map & self::TRACESMART_ADDRESS) == self::TRACESMART_ADDRESS) {
            $filter[] = 'address';
        }

        if (($map & self::TRACESMART_DOB) == self::TRACESMART_DOB) {
            $filter[] = 'dob';
        }

        if (($map & self::TRACESMART_DRIVERLICENSE) == self::TRACESMART_DRIVERLICENSE) {
            $filter[] = 'driving';
        }

        if (($map & self::TRACESMART_PASSPORT) == self::TRACESMART_PASSPORT) {
            $filter[] = 'passport';
        }

        if (($map & self::TRACESMART_CREDITACTIVE) == self::TRACESMART_CREDITACTIVE) {
            $filter[] = 'credit-active';
        }

        return $filter;
    }

    /**
    * Creates a new Background Check
    *
    * @link https://veridu.com/wiki/Check_Resource#How_to_create_a_new_Background_Check
    *
    * @param string $username User identification
    *
    * @return self
    */
    public function create($provider, $setup = self::NONE, $additionalParams = array(), $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        switch ($provider) {
            case 'tracesmart':
                $setup = $this->tracesmartSetup($setup);
                break;
            default:
                throw new \Veridu\API\Exception\InvalidProvider;
        }

        $response = $this->signedFetch(
            'POST',
            sprintf(
                'check/%s/%s',
                $username,
                $provider
            ),
            array(
                'setup' => implode(',', $setup),
                'param' => implode(',', $additionalParams)
            )
        );

        return $response['task_id'];
    }

    /**
    * Retrieves data from all providers
    *
    * @link https://veridu.com/wiki/Check_Resource#How_to_retrieve_data_from_all_providers
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function listAll($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->signedFetch(
                'GET',
                sprintf(
                    'check/%s',
                    $username
                )
            );
        return $json['info'];
    }

    /**
    * Retrivies data from one provider
    *
    * @link https://veridu.com/wiki/Check_Resource#How_to_retrieve_data_from_one_provider
    *
    * @param string $username User identification
    *
    * @return array
    */
    public function details($username, $provider, $setup, $filter = Check::FILTER_ALL)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->signedFetch(
            'GET',
            sprintf(
                'check/%s/%s',
                $username,
                $provider
            ),
            array(
                'filter' => $filter,
                'setup' => $setup
            )
        );
        if ($filter == 'all') {
            return $json['info'];
        }
        return $json[$filter];
    }
}
