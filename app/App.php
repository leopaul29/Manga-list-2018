<?php

use Core\Config;
use Core\Database;

class App {

	private $db_instance;

	private static $_instance;

	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new App();
		}
		return self::$_instance;
	}

	public static function load() {
		require ROOT . '/vendor/autoload.php';
		
		require ROOT . '/app/Autoloader.php';
		App\Autoloader::register();

		require ROOT . '/core/Autoloader.php';
		Core\Autoloader::register();

		/*
		 * to avoid to store an object with __PHP_Incomplete_Class
		 * do session_start after launch the autoloader
		 * https://stackoverflow.com/questions/965611/forcing-access-to-php-incomplete-class-object-properties
		 */
		session_start();
	}

	public function getTable($name) {
		$class_name = 'App\\Table\\' . ucfirst($name) . 'Table';
		return new $class_name($this->getDB());
	}

	public function getDB() {
		//if(is_null($this->db_instance)) {
			$config = Config::getInstance(ROOT . '/config/Config.php');
			$this->db_instance = new Database($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
		//}
		return $this->db_instance;
	}
}