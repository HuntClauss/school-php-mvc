<?php

class UserView extends View {
	public function show() {
//		header('Access-Control-Allow-Origin: http://szyper.clauss.me http://localhost:* http://127.0.0.1:*');
		header("Access-Control-Allow-Credentials: true");
		if (isset($this->model->status)) {
			http_response_code($this->model->status->code);
			return json_encode($this->model->status->resp);
		}

		return json_encode(["error" => "something gone really wrong"]);
	}

}