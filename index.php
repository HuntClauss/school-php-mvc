<?php
spl_autoload_register(function ($name) {
	if (preg_match("/Model$/", $name)) {
		$name = "models/$name";
	} else if (preg_match("/Controller$/", $name)) {
		$name = "controller/$name";
	}
	require_once "$name.php";
});

$router = new Router();
$router->view("/", "index.php");
$router->get("/test.php", "UserController@createUser");

die($router->match());