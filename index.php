<?php
spl_autoload_register(function ($name) {
	if (preg_match("/Model$/", $name)) {
		$name = "models/$name";
	} else if (preg_match("/Controller$/", $name)) {
		$name = "controllers/$name";
	}
	require_once "$name.php";
});

$router = new Router();
$router->view("/", "index.php");

$router->post("/api/user/create", "UserController@create");
$router->post("/api/user/delete", "UserController@delete");
$router->post("/api/user/edit", "UserController@edit");
$router->post("/api/user/auth", "UserController@authenticate");
//$router->post("/api/user/logout", "UserController@")

$router->post("/api/notes/create", "NoteController@create");
$router->post("/api/notes/delete", "NoteController@delete");
$router->post("/api/notes/edit", "NoteController@edit");
$router->post("/api/notes/show", "NoteController@show");

die($router->match());