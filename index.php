<?php
spl_autoload_register(function ($name) {
	$name = str_replace("\\", "/", $name);
	if (preg_match("/Model$/", $name)) {
		$name = "model/$name";
	} else if (preg_match("/Controller$/", $name)) {
		$name = "controller/$name";
	} else if (preg_match("/View$/", $name)) {
		$name = "view/$name";
	}
	require_once "$name.php";
});


$router = new Router();
$router->get(new Route("/",  "index", "render", "Index"));

$router->post(new Route("/api/user/create", "create", "status", "User"));
$router->post(new Route("/api/user/delete", "delete", "status", "User"));
$router->post(new Route("/api/user/edit", "edit", "status", "User"));
$router->post(new Route("/api/user/show", "show", "show", "User"));
$router->post(new Route("/api/user/auth", "auth", "status", "User"));
$router->post(new Route("/api/user/logout", "logout", "status", "User"));

$router->post(new Route("/api/notes/create", "create", "status", "Note"));
$router->post(new Route("/api/notes/delete", "delete", "status", "Note"));
$router->post(new Route("/api/notes/edit", "edit", "status", "Note"));
$router->post(new Route("/api/notes/show", "show", "show", "Note"));

$router->match();