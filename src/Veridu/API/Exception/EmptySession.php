<?php
/**
* Exception thrown while trying to fetch an API Resource that requires a session token without having one.
*/

namespace Veridu\API\Exception;

class EmptySession extends \Exception {
	protected $message = 'A session is required to fetch this resource.';
}
