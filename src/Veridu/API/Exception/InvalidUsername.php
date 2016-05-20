<?php
/**
* Exception thrown when an invalid username is used.
*/

namespace Veridu\API\Exception;

class InvalidUsername extends \Exception {
	protected $message = 'Invalid username format.';
}
