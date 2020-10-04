<?php

namespace Core;

Class Config {

	private $settings = [];
	private static $_instance;

	public static function getInstance($file) {
		if(is_null(self::$_instance)) {
			self::$_instance = new Config($file);
		}
		return self::$_instance;
	}

	public function __construct($file) {
		$this->settings = require $file; //dirname(__DIR__) . '/config/Config.php';
	}

	public function get($key) {
		if(!isset($this->settings[$key])) {
			return null;
		}
		return $this->settings[$key];
	}
}