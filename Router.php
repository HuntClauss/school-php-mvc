<?php

include_once "controllers/UserController.php";

class Router {
	private $routes = [];

	public function get($route, $controller) {
		$this->methodRoute($route, "GET", $controller);
	}

	public function post($route, $controller) {
		$this->methodRoute($route, "POST", $controller);
	}

	public function view($route, $file) {
		$this->routes[] = [
			"method" => "GET",
			"view" => true,
			"route" => $route,
			"file" => $file,
		];
	}

	function methodRoute($route, $method, $controller) {
		$result = [
			"method" => $method,
			"route" => $route,
			"controller" => $controller
		];

		if (strpos($controller, "@")) {
			$parts = explode("@", $controller);

			$result["controller"] = [
				"class" => $parts[0],
				"callback" => $parts[1]
			];
		}
		$this->routes[] = $result;
	}



	public function match() {
		$foundRoute = false;
		foreach ($this->routes as $route) {
			if ($this->compareRoutes($route["route"], $_SERVER["REQUEST_URI"])) {
				$foundRoute = true;
				if ($route["method"] !== $_SERVER["REQUEST_METHOD"])
					continue;

				if (array_key_exists("view", $route) && $route["view"] === true) {
					include __DIR__ . "/views/$route[file]";
					exit;
				}

				if (is_array($route["controller"])) {
					$controller = $route["controller"];
					$class = $controller["class"];
					$callback = $controller["callback"];

					return (new $class)->$callback($this);
				}
				return $route["controller"]();
			}
		}

		if ($foundRoute) $this->abort(405);
		$this->abort(404);
	}

	private function compareRoutes($r1, $r2) {
		$route = explode('/', $r1);
		$uri = explode('/', strtok($r2, '?'));

		if (count($route) !== count($uri)) return false;

		foreach ($route as $key => $value) {
			if ($uri[$key] !== $value) return false;
		}
		return true;
	}

	private function abort($code) {
		http_response_code($code);
		exit();
	}
}