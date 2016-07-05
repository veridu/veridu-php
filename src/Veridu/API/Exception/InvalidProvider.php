<?php
/**
* Exception thrown when an Invalid Provider is given.
*/

namespace Veridu\API\Exception;

class InvalidProvider extends \Exception
{
    protected $message = 'Invalid provider.';
}
