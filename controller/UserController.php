<?php

use engine\Status;

class UserController {
	private $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function create() {
		$required = ["username", "email", "password"];
		$validation = [
			"username" => "/[a-z\d_\-]{4,30}/i",
			"email" => "/[a-z\d_\-+.]{1,50}@[a-z\d_\-]{2,30}\.[a-z\d\-]{2,25}/i",
			"password" => "/.{8,65}/",
		];

		foreach ($required as $field) {
			if (!isset($_POST[$field])) {
				return $this->model->response(Status::error(403, "missing '$field' value"));
			}

			if (!preg_match($validation[$field], $_POST[$field])) {
				return $this->model->response(Status::error(403, "'$field' is invalid or contains prohibited signs"));
			}
		}

		$username = trim($_POST["username"]);
		$email = trim($_POST["email"]);
		$password = $_POST["password"];

		$passwd_hash = password_hash($password, PASSWORD_BCRYPT);
//		$passwd_hash = password_hash($password, PASSWORD_ARGON2ID);

		try {
			$this->model->add($username, $email, $passwd_hash);
		} catch (Exception $err) {
			return $this->model->response(Status::error(401, "username already in use"));
		}

		session_start();
		$_SESSION["logged"] = true;
		$_SESSION["username"] = $username;
		return $this->model->response(Status::success(200, "user created successfully"));
	}

	public function delete() {
		session_start();
		if (!isset($_SESSION["logged"]))
			return $this->model->response(Status::error(403, "you have to be logged to do it"));
		if (!isset($_POST["password"]))
			return $this->model->response(Status::error(401, "you have to provide password to perform this action"));

		$password = $_POST["password"];

		if (!password_verify($password, $this->model->auth($_SESSION["username"])))
			return $this->model->response(Status::error(403, "invalid credentials"));

		if (!$this->model->delete($_SESSION["username"]))
			return $this->model->response(Status::error(500, "something went wrong, account was not deleted"));

		session_destroy();
		return $this->model->response(Status::success(200, "account successfully deleted"));
	}

	public function edit() {
		// TODO placeholder
	}

	public function auth() {
		$required = ["username", "password"];
		foreach ($required as $field) {
			if (!isset($_POST[$field])) {
				return $this->model->response(Status::error(403, "missing '$field' value"));
			}
		}

		$username = $_POST["username"];
		$password = $_POST["password"];

		if (!password_verify($password, $this->model->auth($username)))
			return $this->model->response(Status::error(403, "invalid credentials"));

		session_start();
		$_SESSION["logged"] = true;
		$_SESSION["username"] = $username;
		return $this->model->response(Status::success(200, "logged successfully"));
	}

	public function logout() {
		session_start();
		session_destroy();
		return $this->model->response(Status::success(200, "successfully logged out"));
	}

	public function show() {}
}