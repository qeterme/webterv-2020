<?php $root = realpath($_SERVER["SERVER_NAME"]);

$title = "";
switch($active) {
    case "home":
        $title = "Promóciók";
        break;
    case "pizza":
        $title = "Pizzák";
        break;
    case "roast":
        $title = "Sültek";
        break;
    case "order":
        $title = "Rendelés";
        break;
    case "privacy":
        $title = "Adatkezelési irányelveink";
        break;
    case "terms":
        $title = "Általános Szerződési Feltételek";
        break;
    case "contactus":
        $title = "Kapcsolat";
        break;
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>TTIK Pizzéria » <?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo $root; ?>/eosge3/style/front.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="<?php echo $root; ?>/eosge3/icons/logo.svg">
    <script src="<?php echo $root; ?>/eosge3/script/script.js"></script>
</head>
<body>
