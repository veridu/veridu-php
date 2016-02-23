<?php
/**
* Exception thrown when server response isn't a valid JSON.
*/

namespace Veridu\API\Exception;

use Veridu\Exception\Exception;

class InvalidFormat extends Exception {
	protected $message = 'The API response is in an invalid format.';
}
