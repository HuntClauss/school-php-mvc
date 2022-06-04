<?php

namespace engine;
use mysqli;

class Database {
	public $db;

	public function __construct() {
		$this->db = new mysqli(
			Config::DB_HOSTNAME,
			Config::DB_USERNAME,
			Config::DB_PASSWORD,
			Config::DB_NAME
		);
	}
}