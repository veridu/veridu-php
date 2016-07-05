<?php
/**
* Abstract Endpoint definition
*/
namespace Veridu\API;

use Veridu\Common\Compat;
use Veridu\Common\URL;
use Veridu\Signature\SignatureInterface;
use Veridu\Storage\StorageInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\requests;

abstract class AbstractEndpoint implements EndpointInterface
{
    /**
    * @var string API Key
    */
    protected $key;

    /**
    * @var string API Secret
    */
    protected $secret;

    /**
    * @var string API Version
    */
    protected $version;

    /**
    * @var ClientInterface HTTP Client to perform API requests
    */
    protected $client;

    /**
    * @var Veridu\Signature\SignatureInterface Signature object for signed API requests
    */
    protected $signature;

    /**
    * @var Veridu\Storage\StorageInterface Storage object for session and username
    */
    protected $storage = null;

    /**
    * @var string Last API call error
    */
    protected $lastError = null;

    /**
    * Base API URL
    */
    const BASE_URL = 'https://api.veridu.com';

    /**
    * Validates a username format
    *
    * @link https://veridu.com/wiki/Introduction#Basic_Concepts
    *
    * @param string $username User identification
    *
    * @return boolean
    */
    public static function validateUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $username);
    }

    /**
    * Validates if a username is not empty, throws a Exception\EmptyUsername exception if empty
    *
    * @link https://veridu.com/wiki/Introduction#Basic_Concepts
    *
    * @param string $username User identification
    *
    * @return boolean
    */
    public static function validateNotEmptyUsernameOrFail($username)
    {
        if (empty($username)) {
            throw new Exception\EmptyUsername();
        }
        return true;
    }

    /**
    * Validates if a username is valid, throws a Exception\InvalidUsername exception if invalid $useranme
    *
    * @link https://veridu.com/wiki/Introduction#Basic_Concepts
    *
    * @param string $username User identification
    *
    * @return boolean
    */
    public static function validateUsernameFormatOrFail($username)
    {
        if (! self::validateUsername($username)) {
            throw new Exception\InvalidUsername();
        }
        return true;
    }

    /**
    * Checks if a session is not empty, throws Exception\EmptySession exception if empty
    *
    * @link https://veridu.com/wiki/Introduction#Basic_Concepts
    *
    * @param string $username User identification
    *
    * @return boolean
    */
    public function validateNotEmptySessionOrFail()
    {
        if ($this->storage->isSessionEmpty()) {
            throw new Exception\EmptySession;
        }
        return true;
    }

    /**
    * Checks if a username is not empty and is valid, throws Exception\EmptyUsername or Exception\InvalidUsername exception
    *
    * @link https://veridu.com/wiki/Introduction#Basic_Concepts
    *
    * @param string $username User identification
    *
    * @return boolean
    */
    public static function validateUsernameOrFail($username)
    {
        if (self::validateNotEmptyUsernameOrFail($username) && self::validateUsernameFormatOrFail($username)) {
            return true;
        }
        return false;
    }

    /**
    * Class constructor
    *
    * @param Config $config Basic client configuration
    * @param HTTPClient $client HTTP Client to perform API requests
    * @param Veridu\Signature\SignatureInterface $signature Signature object for signed API requests
    * @param Veridu\Storage\StorageInterface $storage Storage object for session and username
    * @param boolean $debug Debug mode
    *
    * @return void
    */
    public function __construct($key, $secret, $version, ClientInterface &$client, SignatureInterface &$signature, StorageInterface &$storage, $debug = false)
    {
        $this->setKey($key);
        $this->setSecret($secret);
        $this->setVersion($version);
        $this->setClient($client);
        $this->setSignature($signature);
        $this->setStorage($storage);
    }

    /**
    * Fetches an API resource
    *
    * @param string $method Request method
    * @param string $resource Resource URI
    * @param string|array $data Request payload/query string
    *
    * @return string
    *
    * @throws Exception\InvalidMethod
    * @throws Exception\InvalidFormat
    * @throws Exception\InvalidResponse
    * @throws Exception\APIError
    */
    protected function fetch($method, $resource, $data = null)
    {
        $this->lastError = null;
        $options = array(
            'headers' => array(
                'Veridu-Client' => $this->key
            )
        );
        if (!$this->storage->isSessionEmpty()) {
            $options['headers']['Veridu-Session'] = $this->storage->getSessionToken();
        }
        $url = URL::build(
            self::BASE_URL,
            array(
                $this->version,
                $resource
            )
        );
        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($data)) {
                    $options['query'] = $data;
                }
                $response = $this->client->get($url, $options);
                break;
            case 'POST':
                if (!empty($data)) {
                    $options['form_params'] = $data;
                }
                $response = $this->client->post($url, $options);
                break;
            case 'DELETE':
                if (!empty($data)) {
                    $options['form_params'] = $data;
                }
                $response = $this->client->delete($url, $options);
                break;
            case 'PUT':
                if (!empty($data)) {
                    $options['form_params'] = $data;
                }
                $response = $this->client->put($url, $options);
                break;
            default:
                throw new Exception\InvalidMethod;
        }
        $json = json_decode($response->getBody(), true);
        if (is_null($json)) {
            throw new Exception\InvalidFormat;
        }
        if (!isset($json['status'])) {
            throw new Exception\InvalidResponse;
        }
        if ($json['status'] === false) {
            $this->lastError = $json['error']['type'];
            throw new Exception\APIError($json['error']['message']);
        }
        return $json;
    }

    /**
    * Fetches a signed API resource
    *
    * @param string $method Request method
    * @param string $resource Resource URI
    * @param string|array $data Request payload/query string
    *
    * @return string
    *
    * @throws Exception\InvalidMethod
    * @throws Exception\InvalidFormat
    * @throws Exception\InvalidResponse
    * @throws Exception\APIError
    * @throws \Veridu\Signature\Exception\NonceMismatch
    */
    protected function signedFetch($method, $resource, array $data = null)
    {
        $sign = $this->signature->sign(
            $this->key,
            $this->secret,
            $this->version,
            $method,
            URL::build(self::BASE_URL, array($this->version, $resource))
        );

        if (empty($data)) {
            $json = $this->fetch($method, $resource, $sign);
        } else {
            $json = $this->fetch($method, $resource, array_merge($data, $sign));
        }

        if ((empty($json['nonce'])) || (strcmp($json['nonce'], $this->signature->lastNonce()) != 0)) {
            throw new \Veridu\Signature\Exception\NonceMismatch;
        }
        unset($json['nonce']);
        return $json;
    }

    /**
    * {@inheritDoc}
    */
    public function setClient(ClientInterface &$client)
    {
        $this->client = $client;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getClient()
    {
        return $this->client;
    }

    /**
    * {@inheritDoc}
    */
    public function setSignature(SignatureInterface &$signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
    * {@inheritDoc}
    */
    public function setStorage(StorageInterface &$storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
    * {@inheritDoc}
    */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
    * {@inheritDoc}
    */

    public function lastError()
    {
        return $this->lastError;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }
}
