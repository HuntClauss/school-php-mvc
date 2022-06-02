<?php

class UserModel {
	private $username;
	private $password;
	private $email;

	public function __construct($username, $email, $password) {
		$this->username = $username;
		$this->email = $email;
		$this->password = password_hash($password, PASSWORD_BCRYPT);
//		$this->password = password_hash($password, PASSWORD_ARGON2ID); // works only with php > 7.3 and only with libargon2, not with libsodium
//		echo $this->password;
	}

	public function getUsername(): string {
		return $this->username;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getPasswordHash(): string {
		return $this->password;
	}
}