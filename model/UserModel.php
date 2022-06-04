<?php

use engine\Database;
use engine\Status;

class UserModel extends Database {
	public $status;

	public function response($status) {
		$this->status = $status;
	}

	public function add($username, $email, $password): bool {
		$stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
		$stmt->bind_param("sss", $username, $email, $password);
		return $stmt->execute();
	}

	public function delete($username): bool {
		$stmt = $this->db->prepare('DELETE FROM users WHERE username = ?');
		$stmt->bind_param("s", $username);
		$stmt->execute();
		return $stmt->affected_rows === 1;
	}

	public function auth($username) {
		$stmt = $this->db->prepare('SELECT password FROM users WHERE username = ?');
		$stmt->bind_param("s", $username);
		$stmt->execute();

		return $stmt->get_result()->fetch_assoc()["password"];
	}

	public function getByUsername($username) {
		$stmt = $this->db->prepare('SELECT email FROM users WHERE username = ?');
		$stmt->bind_param("s", $username);
		$stmt->execute();

		$email = $stmt->get_result()->fetch_assoc()["email"];

		if (empty($email)) return false;
		return [
			"username" => $username,
			"email" => $email,
		];
	}

	public function update($username, $arr) {
		$params = "";
		$types = "";
		$values = [];
		foreach ($arr as $key => $value) {
			$params .= " $key = ?,";
			$types .= "s";
			$values[] = $value;
		}
		$params = rtrim($params, ",");
		$types .= "s";
		$values[] = $username;

		$stmt = $this->db->prepare('UPDATE users SET'.$params.' WHERE username = ?');
		$stmt->bind_param($types, ...$values);
		$stmt->execute();
		return $stmt->affected_rows === 1;
	}
}