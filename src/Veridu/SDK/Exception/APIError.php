<?php
/**
* Exception thrown when the API responds a request with an error.
*/

namespace Veridu\SDK\Exception;

use Veridu\Exception\Exception;

class APIError extends Exception {
	protected $message = 'The API returned an error.';
}