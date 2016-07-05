<?php
/**
* Exception thrown when server response isn't a valid JSON.
*/

namespace Veridu\API\Exception;

class InvalidFormat extends \Exception
{
    protected $message = 'The API response is in an invalid format.';
}
