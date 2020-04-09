<?php
$request = $_SERVER['REQUEST_URI'];
echo $request;
switch ($request) {
    case '':
    case '/eosge3/' :
        require __DIR__ . '/views/index.php';
        break;
    case '/eosge3/about' :
        require __DIR__ . '/views/about.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}
