<?php
/**
* Exception thrown while trying to fetch an API Resource that requires a session token without having one.
*/

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class EmptySession extends Exception {
	protected $message = 'A session is required to fetch this resource.';
}