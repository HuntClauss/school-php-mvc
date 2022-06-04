<?php

class UserController {

	public function create() {
		$required = ["username", "email", "password"];

		foreach ($required as $field) {
			if (!isset($_POST[$field])) {
				return $this->json("error", "missing '$field' value");
			}
		}

		$user = new UserModel($_POST["username"], $_POST["email"], $_POST["password"]);
		return $this->json("success", "user created successfully");
	}

	public function delete() {

	}

	public function edit() {

	}

	public function authenticate() {

	}

	private function json($key, $msg): string {
		return "{\"$key\": \"$msg\"}";
	}
}