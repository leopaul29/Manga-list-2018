<?php

namespace Core\Auth;

use Core\Database;
use Core\Entity\UserEntity;

class DBAuth {

	private $db;

	public function __construct(Database $db) {
		$this->db = $db;
	}

    /**
     * get user session object
     * @return user session object or false
     */
	public function getUserID() {
		if($this->isLogged()) {
			return $_SESSION['auth'];
		}
		return false;
	}

	/**
	 * @param $username
	 * @param $password
	 * @param $class_name user entity to map
	 * @return boolean
	*/
	public function login($username, $password, $class_name = 'Core\Entity\UserEntity') {
        // get user + role information
		$user = $this->db->prepare("
			SELECT *, roles.label as role_label
			FROM users 
			LEFT JOIN roles ON role_id = roles.id
			WHERE username = ?
		", [$username], $class_name, true);
		
		if($user) {
		    // test user's password with sha1 hash
			if($user->password === sha1($password)) {
			    // remove from user object password & role label
				unset($user->password);
				unset($user->label);
				// feed user_session with user info
				$_SESSION['auth'] = $user;
				// all is good :)
				return true;
			}
		}
		// user doesn't exist or password doesn't fit the user
		return false;
	}

    /**
     * if user is logged
     * @return bool
     */
	public function isLogged() {
		return isset($_SESSION['auth']);
	}

    /**
     * @return bool
     */
	public function isRoot() {
		if($this->isLogged()) {
			return $_SESSION['auth']->role_id == 1;
		}
		return false;
	}

	public function logout() {
		return session_destroy();
	}

}