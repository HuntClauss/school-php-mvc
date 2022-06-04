<?php

class UserView {
	private $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function status() {
		$code = $this->model->status->code;
		$resp = $this->model->status->resp;

		http_response_code($code);
		return json_encode($resp);
	}

	public function show() {
		session_start();
		if (!isset($_SESSION["logged"])) {
			http_response_code(403);
			return json_encode(["error" => "you have to be logged to do it"]);
		}

		if (isset($this->model->status)) {
			$code = $this->model->status->code;
			$resp = $this->model->status->resp;
			http_response_code($code);
			return json_encode($resp);
		}

		$result = $this->model->getByUsername($_SESSION["username"]);
		if ($result === false) {
			http_response_code(401);
			return json_encode(["error" => "something went wrong"]);
		}

		return json_encode($result);
	}

}