<?php
ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);

session_start();
if (isset($_SESSION["username"])) {
    header("Location: /eosge3/dashboard?PHPSESSID=" . session_id());
}

define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/auth/base.php";

$root = realpath($_SERVER["SERVER_NAME"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accounts = [];
    $file = fopen("data/users.txt", "r");
    while (($line = fgets($file)) !== false) {
        $accounts[] = unserialize($line);
    }
    fclose($file);

    $error = "";

    if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["password-sec"]) && !empty($_POST["address"]) && !empty($_POST["phone-num"])) {
        if ((!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) {
            $error = "Hibás email cím formátum!";
        } elseif ($_POST["password"] != $_POST["password-sec"]) {
            $error = "A két jelszó nem egyezik!";
        }

        foreach ($accounts as $account) {
            if ($account["email"] == $_POST["email"]) {
                $error = "Ez az email cím már foglalt!";
            }
        }
    } else {
        $error = "Valami nincs kitöltve?";
    }

    if (!empty($error)) {
        echo '<div class="error"><b>Hiba!</b> ' . $error . '</div>';
    } else {
        $accounts = [[
            "name" => $_POST["name"],
            "email" => $_POST["email"],
            "password" => hash('sha256', $_POST["password"]),
            "address" => $_POST["address"],
            "tel" => $_POST["phone-num"],
            "admin" => false
        ]];

        $_SESSION["user"] = [
            "name" => $_POST["name"],
            "address" => $_POST["address"],
            "tel" => $_POST["phone-num"],
            "email" => $_POST["email"]
        ];

        $_SESSION["username"] = $_POST["name"];
        $_SESSION["admin"] = false;

        file_put_contents("data/users.txt", serialize(end($accounts)) . PHP_EOL, FILE_APPEND | LOCK_EX);

        header("Location: /eosge3/dashboard");
    }
}

?>
<h2>Regisztráció</h2>
<form action="<?php echo $root; ?>/eosge3/auth/register" method="post" name="register">
    <fieldset>
        <h3>Neved:</h3>
        <input type="text" name="name" autocomplete="name">
        <h3>Email cím:</h3>
        <input type="email" name="email" autocomplete="email">
        <h3>Jelszó:</h3>
        <input type="password" name="password" autocomplete="new-password">
        <h3>Jelszó megerősítés:</h3>
        <input type="password" name="password-sec" autocomplete="new-password">
        <h3>Címed:</h3>
        <input type="text" name="address">
        <h3>Telefonszámod:</h3>
        <input type="tel" name="phone-num" autocomplete="tel"><br>

        <input type="submit" name="login" value="Regisztráció">
    </fieldset>
</form>
<span><a href="<?php echo $root; ?>/eosge3/auth">Van már felhasználód?</a></span>
</main>
</body>

</html>
