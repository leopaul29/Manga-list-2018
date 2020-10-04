<?php
define('ROOT', dirname(__DIR__));

require ROOT . '/app/App.php';
App::load();

// Routing
// https://www.youtube.com/watch?v=I-DN2C7Gs7A
$page = 'home';
if(isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
	$page = 'home.index';
}

$page = explode('.', $page);
$controller = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
$action = $page[1];
$controller = new $controller(implode('.', $page));
$controller->$action();