<?php

class NoteModel {
	private $title;
	private $content;

	public function __construct($title, $content) {
		$this->title = ltrim(rtrim($title));
		$this->content = ltrim(rtrim($content));
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getContent(): string {
		return $this->content;
	}
}