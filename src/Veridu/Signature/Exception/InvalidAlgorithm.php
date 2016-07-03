<?php
/**
* Exception thrown when setting an invalid HMAC Hash algorithm.
*/

namespace Veridu\Signature\Exception;

class InvalidAlgorithm extends \Exception
{
    protected $message = 'Invalid HMAC Hash algorithm.';
}
