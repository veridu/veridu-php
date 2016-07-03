<?php

namespace Veridu\API;

use Veridu\API\Exception;

class Profile extends AbstractEndpoint
{
    const FILTER_ALL = 0xFFFF;
    const FILTER_STATE = 0x0001;
    const FILTER_USER = 0x0002;
    const FILTER_DETAILS = 0x0004;
    const FILTER_DOCUMENT = 0x0008;
    const FILTER_BADGES = 0x0010;
    const FILTER_CERTIFICATE = 0x020;
    const FILTER_FLAGS = 0x0040;
    const FILTER_FACTS = 0x0080;
    const FILTER_PROVIDER = 0x0100;
    const FILTER_CPR = 0x0200;
    const FILTER_KBA = 0x0400;
    const FILTER_NEMID = 0x0800;
    const FILTER_OTP = 0x1000;
    const FILTER_PERSONAL = 0x2000;

    private function createFilter($map)
    {
        if (($map & self::FILTER_ALL) == self::FILTER_ALL) {
            return 'all';
        }

        $filter = array();

        if (($map & self::FILTER_STATE) == self::FILTER_STATE) {
            $filter[] = 'state';
        }

        if (($map & self::FILTER_USER) == self::FILTER_USER) {
            $filter[] = 'user';
        }

        if (($map & self::FILTER_DETAILS) == self::FILTER_DETAILS) {
            $filter[] = 'details';
        }

        if (($map & self::FILTER_DOCUMENT) == self::FILTER_DOCUMENT) {
            $filter[] = 'document';
        }

        if (($map & self::FILTER_BADGES) == self::FILTER_BADGES) {
            $filter[] = 'badges';
        }

        if (($map & self::FILTER_CERTIFICATE) == self::FILTER_CERTIFICATE) {
            $filter[] = 'certificate';
        }

        if (($map & self::FILTER_FLAGS) == self::FILTER_FLAGS) {
            $filter[] = 'flags';
        }

        if (($map & self::FILTER_FACTS) == self::FILTER_FACTS) {
            $filter[] = 'facts';
        }

        if (($map & self::FILTER_PROVIDER) == self::FILTER_PROVIDER) {
            $filter[] = 'provider';
        }

        if (($map & self::FILTER_CPR) == self::FILTER_CPR) {
            $filter[] = 'cpr';
        }

        if (($map & self::FILTER_KBA) == self::FILTER_KBA) {
            $filter[] = 'kba';
        }

        if (($map & self::FILTER_NEMID) == self::FILTER_NEMID) {
            $filter[] = 'nemid';
        }

        if (($map & self::FILTER_OTP) == self::FILTER_OTP) {
            $filter[] = 'otp';
        }

        if (($map & self::FILTER_PERSONAL) == self::FILTER_PERSONAL) {
            $filter[] = 'personal';
        }

        return $filter;
    }

    /**
    * Retrieves the consolidates profile of a given user
    *
    * @link https://veridu.com/wiki/Profile_Resource#How_to_retrieve_the_consolidated_profile_of_a_given_user
    *
    * @param integer $filter Filter map
    * @param string $username User identification
    *
    * @return array
    */
    public function retrieve($filter = Profile::FILTER_ALL, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);
        $filters = $this->createFilter($filter);

        if ($filters != 'all') {
            $filters = implode(',', $filters);
            $json = $this->fetch(
                'GET',
                sprintf(
                    'profile/%s',
                    $username
                ),
                array(
                    'filter' => $filters
                )
            );
        } else {
            $json = $this->fetch(
                'GET',
                sprintf(
                    'profile/%s',
                    $username
                )
            );
        }
        return $json;
    }
}
