<?php

namespace Veridu;

use GuzzleHttp\Client;
use Veridu\Storage\StorageInterface;
use Veridu\Storage\MemoryStorage;
use Veridu\Signature\SignatureInterface;
use Veridu\Signature\HMAC;

final class API
{
    /**
    * @var string API Key
    */
    private $key;

    /**
    * @var string API Secret
    */
    private $secret;

    /**
    * @var string API Version
    */
    private $version;

    /**
    * @var SignatureInterface SignatureInterface instance
    */
    private $signature;

    /**
    * @var Storage Storage instance
    */
    private $storage = null;

    /**
    * @var Client Guzzle HTTP Client instance
    */
    private $client = null;

    /**
    * Major SDK Version
    */
    const MAJOR = 1;

    /**
    * Minor SDK Version
    */
    const MINOR = 0;

    /**
    * Revision SDK Version
    */
    const REVISION = 0;

    /**
    * Namespace resolution for Endpoint classes (returns full namespaced class name)
    *
    * @param string $endpoint API Endpoint name
    *
    * @return string|null Full Class Name
    */
    private function classNameResolution($endpoint)
    {
        $endpoint = strtolower($endpoint);

        if (in_array($endpoint, array('cpr', 'kba', 'otp', 'sso'))) {
            $endpoint = strtoupper($endpoint);
        } elseif ($endpoint === 'nemid') {
            $endpoint = 'NemID';
        } else {
            $endpoint = ucfirst($endpoint);
        }

        return sprintf('\\Veridu\\API\\%s', $endpoint);
    }

    /**
    * Returns a new API instance
    *
    * @param string $key Client KEY
    * @param string $secret Client SECRET
    * @param string $version API Version
    * @param StorageInterface $storage instance that implements StorageInterface
    * @param SignatureInterface $signature instance that implements SignatureInterface
    *
    * @return self
    */
    public static function factory($key, $secret, $version = '0.3', StorageInterface $storage = null, SignatureInterface $signature = null)
    {
        if (empty($storage)) {
            $storage = new Storage\MemoryStorage;
        }

        if (empty($signature)) {
            $signature = new Signature\HMAC;
        }

        return new API(
            $key,
            $secret,
            $version,
            $storage,
            $signature
        );
    }

    /**
    * Class constructor
    *
    * @param string $key Client KEY
    * @param string $secret Client SECRET
    * @param string $version API Version
    * @param StorageInterface $storage instance that implements StorageInterface
    * @param SignatureInterface $signature instance that implements SignatureInterface
    *
    * @return void
    */
    public function __construct($key, $secret, $version, StorageInterface &$storage, SignatureInterface &$signature)
    {
        $this
            ->setKey($key)
            ->setSecret($secret)
            ->setVersion($version)
            ->setStorage($storage)
            ->setSignature($signature);
    }

    /**
    * Lazy loader for API endpoints
    *
    * @param string $endpoint Endpoint name
    *
    * @return Veridu\API\EndpointInterface|null
    */
    public function __get($endpoint)
    {
        if (empty($this->client)) {
            $this->client = new Client(array(
                'connect_timeout' => 10,
                'headers' => array(
                    'User-Agent' => sprintf(
                        'Veridu-PHP/%d.%d.%d (%s; %s)',
                        self::MAJOR,
                        self::MINOR,
                        self::REVISION,
                        PHP_VERSION,
                        $this->key
                    )
                ),
                'http_errors' => false,
                'timeout' => 10
            ));
        }

        $className = $this->classNameResolution($endpoint);
        if (class_exists($className)) {
            $this->$endpoint = new $className(
                $this->key,
                $this->secret,
                $this->version,
                $this->client,
                $this->signature,
                $this->storage
            );
            return $this->$endpoint;
        }
        return null;
    }

    public function __call($functionName, array $args)
    {
    }

    /**
    * Sets the API Key
    *
    * @return self
    */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
    * Returns the API Key
    *
    * @return string
    */
    public function getKey()
    {
        return $this->key;
    }

    /**
    * Sets the API Secret
    *
    * @return self
    */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
    * Returns the API Secret
    *
    * @return string
    */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
    * Sets the API version to be used during requests
    *
    * @return self
    */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
    * Returns the API version used during requests
    *
    * @return string
    */
    public function getVersion()
    {
        return $this->version;
    }

    /**
    * Sets the storage instance
    *
    * @return self
    */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
    * Returns the storage instance
    *
    * @return Veridu\Storage\StorageInterface
    */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
    * Sets the signature instance
    *
    * @return self
    */
    public function setSignature(SignatureInterface $signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
    * Returns the signature instance
    *
    * @return Veridu\Signature\SignatureInterface
    */
    public function getSignature()
    {
        return $this->signature;
    }
}
