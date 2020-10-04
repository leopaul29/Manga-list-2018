<?php

namespace Core;

use \PDO;

class DataBase {

	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_host;
	private $pdo;

	public function __construct($db_name, $db_user = 'blog', $db_pass = 'blog', $db_host = 'loaclhost') {
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_host = $db_host;
	}

	private function getPDO() {
		if($this->pdo === null) {
			$pdo = new PDO("mysql:charset=utf8mb4;dbname=$this->db_name;host=$this->db_host", $this->db_user, $this->db_pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo = $pdo;
		}
		return $this->pdo;
	}

	public function query($statement, $class_name = null, $one = false) {
		$req = $this->getPDO()->query($statement);

		if (
			strpos($statement, 'UPDATE') === 0 ||
			strpos($statement, 'INSERT') === 0 ||
			strpos($statement, 'DELETE') === 0
		) {
			return $req;
		}

		if(is_null($class_name)) {
			$req->setFetchMode(PDO::FETCH_OBJ);
		} else {
			$req->setFetchMode(PDO::FETCH_CLASS, $class_name);
		}

		if($one) {
			$datas = $req->fetch();
		} else {
			$datas = $req->fetchAll();
		}
		return $datas;
	}

	public function prepare($statement, $attributes, $class_name = null, $one = false) {
		$req = $this->getPDO()->prepare($statement);
		$res = $req->execute($attributes);

		if (
			strpos($statement, 'UPDATE') === 0 ||
			strpos($statement, 'INSERT') === 0 ||
			strpos($statement, 'DELETE') === 0
		) {
			return $res;
		}

		if(is_null($class_name)) {
			$req->setFetchMode(PDO::FETCH_OBJ);
		} else {
			$req->setFetchMode(PDO::FETCH_CLASS, $class_name);
		}

		if($one) {
			$datas = $req->fetch();
		} else {
			$datas = $req->fetchAll();
		}
		return $datas;
	}

	public function lastInsertId() {
		return $this->getPDO()->lastInsertId();
	}
}

//$count = $pdo->exec('INSERT INTO articles SET title="Mon titre", date="' . date('Y-m-d H:i:s') . '"');