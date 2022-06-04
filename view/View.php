<?php

class View {
	public $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function status() {
		http_response_code($this->model->status->code);
		return json_encode($this->model->status->resp);
	}
}