<?php

namespace Veridu\Storage;

use Veridu\API\Exception\EmptyUsername;

final class MemoryStorage implements StorageInterface
{
    /**
    * @var string Session token
    */
    private $token = null;

    /**
    * @var integer Session expire unixtime
    */
    private $expires = -1;

    /**
    * @var string Username identification
    */
    private $username = null;

    /**
    * {@inheritDoc}
    */
    public function setSessionToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getSessionToken()
    {
        return $this->token;
    }

    /**
    * {@inheritDoc}
    */
    public function setSessionExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getSessionExpires()
    {
        return intval($this->expires);
    }

    /**
    * {@inheritDoc}
    */
    public function purgeSession()
    {
        $this->token = null;
        $this->expires = -1;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function isSessionEmpty()
    {
        return empty($this->token);
    }

    /**
    * {@inheritDoc}
    */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getUsername()
    {
        if ($this->isUsernameEmpty()) {
            throw new EmptyUsername();
        }
        return $this->username;
    }

    /**
    * {@inheritDoc}
    */
    public function purgeUsername()
    {
        $this->username = null;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function isUsernameEmpty()
    {
        return empty($this->username);
    }
}
