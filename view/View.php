<?php

class View {
	public $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function status() {
//		header('Access-Control-Allow-Origin: http://szyper.clauss.me http://localhost:* http://127.0.0.1:*');
		header("Access-Control-Allow-Credentials: true");
		http_response_code($this->model->status->code);
		return json_encode($this->model->status->resp);
	}
}