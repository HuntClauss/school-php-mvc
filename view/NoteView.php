<?php

class NoteView extends View {
	public function list() {
		if (isset($this->model->status)) {
			http_response_code($this->model->status->code);
			return json_encode($this->model->status->resp);
		}

		return json_encode(["notes" => []]);
	}
}