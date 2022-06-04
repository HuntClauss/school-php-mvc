<?php

class Route {
	public $path;
	public $model;
	public $controller;
	public $view;

	public function __construct($route, $controllerAction, $viewAction, $target) {
		$this->path = trim($route, "\ \t\n\r\0\x0B\\/");
		$this->model = $target."Model";

		$this->controller = [
			"class" => $target."Controller",
			"action" => $controllerAction,
		];

		$this->view = [
			"class" => $target."View",
			"action" => $viewAction,
		];
	}
}

class Router {
	private $routes = [];

	public function get($route) {
		$this->addRoute("GET", $route);
	}

	public function post($route) {
		$this->addRoute("POST", $route);
	}

	private function addRoute($method, $route) {
		$temp = $route->path;
		$this->routes["$method $temp"] = [
			"model" => $route->model,
			"controller" => $route->controller,
			"view" => $route->view,
		];
	}

	public function match() {
		$uri = strtok($_SERVER["REQUEST_URI"], "?");
		$uri = trim($uri, "\ \t\n\r\0\x0B\\/");
		$method = $_SERVER["REQUEST_METHOD"];

		if (!array_key_exists("$method $uri", $this->routes))
			$this->abort(404);

		$elem = $this->routes["$method $uri"];
		$cInst = $elem["controller"];
		$cAction = $cInst["action"];

		$vInst = $elem["view"];
		$vAction = $vInst["action"];

		$model = new $elem["model"]();
		$controller = new $cInst["class"]($model);
		$view = new $vInst["class"]($model);

		$controller->$cAction();
		echo $view->$vAction();
	}

	private function abort($code) {
		http_response_code($code);
		exit();
	}
}