<?php

use engine\Status;

class NoteController extends Controller {
	public function create() {
		session_start();
		if (!isset($_SESSION["logged"]))
			return $this->model->response(Status::error(403, "you have to be logged to do it"));

		$required = ["title", "content"];
		$validation = [
			"title" => 75,
			"content" => 500,
		];

		foreach ($required as $field) {
			$_POST[$field] = trim($_POST[$field]);
			if (empty($_POST[$field]))
				return $this->model->response(Status::error(403, "missing '$field' value"));

			if (strlen($_POST[$field]) > $validation[$field])
				return $this->model->response(Status::error(401, "content of '$field' is too long"));
		}

		try {
			$this->model->add($_SESSION["username"], $_POST["title"], $_POST["content"]);
		} catch (Exception $err) {
			return $this->model->response(Status::error(401, "note with same title already exists"));
		}

		return $this->model->response(Status::success(200, "note added successfully"));
	}

	public function delete() {
		session_start();
		if (!isset($_SESSION["logged"]))
			return $this->model->response(Status::error(403, "you have to be logged to do it"));

		if (empty($_POST["id"]))
			return $this->model->response(Status::error(401, "invalid id provided"));

		$id = intval($_POST["id"]);
		if (!$this->model->delete($_SESSION["username"], $id))
			return $this->model->response(Status::error(401, "cannot delete note, unknown id"));
		return $this->model->response(Status::success(200, "note deleted successfully"));
	}

	public function edit() {
		session_start();
		if (!isset($_SESSION["logged"]))
			return $this->model->response(Status::error(403, "you have to be logged to do it"));

		if (empty($_POST["id"]))
			return $this->model->response(Status::error(401, "note id not specified"));

		$id = intval($_POST["id"]);
		unset($_POST["id"]);

		$validation = [
			"title" => 75,
			"content" => 500,
		];

		$allowedFields = ["title", "content"];
		foreach ($_POST as $key => $value) {
			if (!in_array($key, $allowedFields, true)) {
				unset($_POST[$key]);
				continue;
			}

			if (array_key_exists($key, $_POST) && array_key_exists($key, $validation) && strlen($value) > $validation[$key])
				return $this->model->response(Status::error(403, "'$key' is too long"));
		}

		if (!$this->model->update($_SESSION["username"], $id, $_POST))
			return $this->model->response(Status::error(401, "nothing change"));
		return $this->model->response(Status::success(200, "note edited successfully"));
	}

	public function list() {
		session_start();
		if (!isset($_SESSION["logged"]))
			return $this->model->response(Status::error(403, "you have to be logged to do it"));

		$limit = 20;
		if (!empty($_POST["limit"])) {
			$temp = intval($_POST["limit"]);
			if ($temp > 0) $limit = min($temp, $limit);
		}

		$notes = $this->model->list($_SESSION["username"], $limit);
		return $this->model->response(Status::custom(200, ["notes" => $notes]));
	}
}