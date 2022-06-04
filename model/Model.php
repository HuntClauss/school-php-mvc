<?php

use engine\Database;

class Model extends Database {
	public $status;

	public function response($status) {
		$this->status = $status;
	}
}