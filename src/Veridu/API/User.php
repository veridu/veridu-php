<?php

namespace Veridu\API;

use Veridu\API\Exception;

class User extends AbstractEndpoint
{

    /**
    * Assigns the current session to a user (if the user doesn't exists, it will be created)
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_create_a_new_user_entry_and_assign_it_to_the_current_session
    *
    * @param string $username User identification
    *
    * @return self
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    * @throws Veridu\Exception\NonceMismatch
    */
    public function create($username)
    {
        $this->validateNotEmptySessionOrFail();
        self::validateUsernameFormatOrFail($username);
        if ((!$this->storage->isUsernameEmpty()) && ($this->storage->getUsername() === $username)) {
            return $this;
        }

        $this->signedFetch(
            'POST',
            sprintf(
                'user/%s',
                $username
            )
        );
        $this->storage->setUsername($username);
        return $this;
    }

    /**
    * Retrieves the full profile and scores for the given user
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_full_profile_and_scores_for_the_given_user
    *
    * @param string $username User identification
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function getAllDetails($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/all',
                $username
            )
        );

        return $json['profile'];
    }

    /**
    * Retrieves the attribute scores for the given user
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_full_profile_and_scores_for_the_given_user
    *
    * @param string $username User identification
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function getAllAttributeScores($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/score',
                $username
            )
        );

        return $json['profile'];
    }

    /**
    * Retrieves te attribute values for the given user
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_full_profile_and_scores_for_the_given_user
    *
    * @param string $username User identification
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function getAllAttributeValues($username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/value',
                $username
            )
        );

        return $json['profile'];
    }

    /**
    * Retrieves details about the given attribute for the given username
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_current_value.2Fscore_field_for_the_given_attribute
    *
    * @param string $attribute Attribute name (name, birth, gender, location, email, phone)
    * @param string $username User identification
    *
    * @return array|null
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function attributeDetails($attribute, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/%s/all',
                $username,
                $attribute
            )
        );

        return $json['attribute'];
    }

    /**
    * Retrieves the score of the given attribute for the given username
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_current_value.2Fscore_field_for_the_given_attribute
    *
    * @param string $attribute Attribute name (name, birth, gender, location, email, phone)
    * @param string $username User identification
    *
    * @return float|null
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function attributeScore($attribute, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/%s/score',
                $username,
                $attribute
            )
        );

        return $json['attribute'];
    }

    /**
    * Retrieves the value of the given attribute for the given username
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_current_value.2Fscore_field_for_the_given_attribute
    *
    * @param string $attribute Attribute name (name, birth, gender, location, email, phone)
    * @param string $username User identification
    *
    * @return string|null
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function attributeValue($attribute, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/%s/value',
                $username,
                $attribute
            )
        );

        return $json['attribute'];
    }

    /**
    * Compares the given value with the inferred value for the given attribute
    *
    * @link https://veridu.com/wiki/User_Resource#How_to_retrieve_the_validation_score_when_comparing_the_given_value_with_the_inferred_value_for_the_given_attribute
    *
    * @param string $attribute Attribute name (name, birth, gender, location, email, phone)
    * @param string $value Value the attribute value will be compared to
    * @param string $username User identification
    *
    * @return array
    *
    * @throws Veridu\Exception\EmptySession
    * @throws Veridu\Exception\EmptyUsername
    * @throws Veridu\Exception\InvalidUsername
    * @throws Veridu\Exception\InvalidResponse
    * @throws Veridu\Exception\InvalidFormat
    * @throws Veridu\Exception\APIError
    */
    public function compareAttribute($attribute, $value, $username = null)
    {
        $this->validateNotEmptySessionOrFail();
        $username = empty($username) ? $this->storage->getUsername() : $username;
        self::validateUsernameOrFail($username);

        $json = $this->fetch(
            'GET',
            sprintf(
                'user/%s/%s',
                $username,
                $attribute
            ),
            $value
        );

        return $json;
    }
}
