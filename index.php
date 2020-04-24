<?php
$request = $_SERVER["REQUEST_URI"];
$uri_parts = explode('?', $request, 2);

switch (strtolower($uri_parts[0])) {
    case '':
    case "/eosge3" :
    case "/eosge3/" :
        require __DIR__ . "/views/index.php";
        break;
    case "/eosge3/auth" :
    case "/eosge3/auth/" :
        require __DIR__ . "/views/auth/login.php";
        break;
    case "/eosge3/auth/register" :
        require __DIR__ . "/views/auth/register.php";
        break;
    case "/eosge3/auth/logout" :
        require __DIR__ . "/views/auth/logout.php";
        break;
    case "/eosge3/dashboard" :
    case "/eosge3/dashboard/" :
        require __DIR__ . "/views/dashboard/index.php";
        break;
    case "/eosge3/dashboard/orders" :
        require __DIR__ . "/views/dashboard/orders.php";
        break;
    case "/eosge3/dashboard/users" :
        require __DIR__ . "/views/dashboard/users.php";
        break;
    case "/eosge3/dashboard/edit/site" :
        require __DIR__ . "/views/dashboard/edit-site.php";
        break;
    case "/eosge3/dashboard/edit/profile" :
        require __DIR__ . "/views/dashboard/edit-profile.php";
        break;
    case "/eosge3/pizza" :
        require __DIR__ . "/views/pizza.php";
        break;
    case "/eosge3/roast" :
        require __DIR__ . "/views/roast.php";
        break;
    case "/eosge3/order" :
        require __DIR__ . "/views/order.php";
        break;
    case "/eosge3/privacy" :
        require __DIR__ . "/views/privacy.php";
        break;
    case "/eosge3/terms" :
        require __DIR__ . "/views/terms.php";
        break;
    case "/eosge3/contactus" :
        require __DIR__ . "/views/contactus.php";
        break;
    default:
        http_response_code(404);
        require __DIR__ . "/views/404.php";
        break;
}
