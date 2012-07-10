<?php defined('SYSPATH') OR die('No direct access allowed.');

class Auth_Dummy_User_Model extends Auth_User_Model {

	protected $fields = array(
		'username' => 'monitor',
		'password' => 'monitor'
	);

	public function __set($key, $value)
	{
		$this->fields[$key] = $value;
	}

	public function __get($key)
	{
		return $this->fields[$key];
	}

	public function __construct() {
	}

	/**
	* @param 	string 		authorization point
	* @return 	boolean 	
	*/
	public function authorized_for($auth_point)
	{
		return true;
	}

	/**
	 * Updates the password of the user.
	 *
	 * @param  string    new password
	 * @return boolean
	 */
	public function change_password( $password )
	{
		return false;
	}

} // End Auth User Model
