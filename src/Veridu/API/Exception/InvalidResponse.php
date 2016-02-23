<?php
/**
* Exception thrown when status field is not present on response payload.
*/

namespace Veridu\API\Exception;

use Veridu\Exception\Exception;

class InvalidResponse extends Exception {
	protected $message = 'The API response is invalid.';
}
