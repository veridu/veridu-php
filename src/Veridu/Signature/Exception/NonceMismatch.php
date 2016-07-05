<?php
/**
* Exception thrown when Request Nonce and Response Nonce don't match.
*/

namespace Veridu\Signature\Exception;

class NonceMismatch extends \Exception
{
    protected $message = 'Request and response nonce mismatch.';
}
