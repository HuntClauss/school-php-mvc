<?php

namespace engine;

class Status {
	public $code;
	public $resp;

	public function __construct($code, $resp) {
		$this->code = $code;
		$this->resp = $resp;
	}

	public static function success($code, $msg): Status {
		return new Status($code, ["success" => $msg]);
	}

	public static function error($code, $msg): Status {
		return new Status($code, ["error" => $msg]);
	}

	public static function custom($code, $resp): Status {
		return new Status($code, $resp);
	}
}