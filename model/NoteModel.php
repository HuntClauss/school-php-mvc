<?php

class NoteModel extends Model {
	public function add($username, $title, $content) {
		$stmt = $this->db->prepare('INSERT INTO notes (owner, title, content) VALUES ((SELECT id FROM users WHERE username = ?), ?, ?)');
		$stmt->bind_param("sss", $username, $title, $content);
		return $stmt->execute();
	}

	public function delete($username, $id) {
		$stmt = $this->db->prepare('DELETE FROM notes WHERE owner = (SELECT id FROM users WHERE username = ?) AND id = ?');
		$stmt->bind_param("si", $username, $id);
		$stmt->execute();
		return $stmt->affected_rows === 1;
	}

	public function update($username, $id, $arr) {
		$params = "";
		$types = "";
		$values = [];
		foreach ($arr as $key => $value) {
			$params .= " $key = ?,";
			$types .= "s";
			$values[] = $value;
		}
		$params = rtrim($params, ",");
		$types .= "si";
		$values[] = $username;
		$values[] = $id;

		$stmt = $this->db->prepare('UPDATE notes SET'.$params.' WHERE owner = (SELECT id FROM users WHERE username = ?) AND id = ?');
		$stmt->bind_param($types, ...$values);
		$stmt->execute();
		return $stmt->affected_rows === 1;
	}

	public function list($username, $limit) {
		$stmt = $this->db->prepare('SELECT id, title, content FROM notes WHERE owner = (SELECT id FROM users WHERE username = ?) LIMIT ?');
		$stmt->bind_param("si", $username, $limit);
		$stmt->execute();

		$res = $stmt->get_result();
		$result = [];
		while ($row = $res->fetch_assoc())
			$result[] = $row;

		return $result;
	}
}