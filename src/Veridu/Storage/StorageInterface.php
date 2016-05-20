<?php

namespace Veridu\Storage;

interface StorageInterface {

	/**
	* Sets session token
	*
	* @param string $value Token value
	*
	* @return Storage Class Instance
	*/
	public function setSessionToken($token);

	/**
	* Returns the session token
	*
	* @return string|null
	*/
	public function getSessionToken();

	/**
	* Sets the session expire unixtime
	*
	* @param integer $value Expire unixtime value
	*
	* @return Storage Class Instance
	*/
	public function setSessionExpires($expires);

	/**
	* Returns the expire unixtime
	*
	* @return integer
	*/
	public function getSessionExpires();

	/**
	* Purges session data
	*
	* @return Storage Class Instance
	*/
	public function purgeSession();

	/**
	* Returns if the session is empty
	*
	* @return boolean
	*/
	public function isSessionEmpty();

	/**
	* Sets the username identification
	*
	* @param string $value Username identification
	*
	* @return Storage Class Instance
	*/
	public function setUsername($username);

	/**
	* Returns the username identification
	*
	* @return string
	*/
	public function getUsername();

	/**
	* Purges username
	*
	* @return Storage Class Instance
	*/
	public function purgeUsername();

	/**
	* Returns if the username is empty
	*
	* @return boolean
	*/
	public function isUsernameEmpty();

}
