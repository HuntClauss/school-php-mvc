<?php

class UserView extends View {
	public function show() {
		if (isset($this->model->status)) {
			http_response_code($this->model->status->code);
			return json_encode($this->model->status->resp);
		}

		return json_encode(["error" => "something gone really wrong"]);
	}

}