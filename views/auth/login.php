<?php
ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);

session_start();
if (isset($_SESSION["username"])) {
    header("Location: /eosge3/dashboard");
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

    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        if ((!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) {
            $error = "Hibás email cím formátum!";
        }

        foreach ($accounts as $account) {
            if ($account["email"] == $_POST["email"]) {
                $error = "";

                if ($account["password"] != hash('sha256', $_POST["password"])) {
                    $error = "Helytelen jelszó!";
                }

                break;
            } else {
                $error = "Ez az email cím még nincs regisztrálva!";
            }
        }
    } else {
        $error = "Valami nincs kitöltve?";
    }

    if (!empty($error)) {
        echo '<div class="error"><b>Hiba!</b> ' . $error . '</div>';
    } else {
        foreach ($accounts as $account) {
            if ($account["email"] == $_POST["email"]) {
                $_SESSION["user"] = [
                    "name" => $account["name"],
                    "address" => $account["address"],
                    "tel" => $account["tel"],
                    "email" => $_POST["email"]
                ];

                $_SESSION["username"] = $account["name"];
                $_SESSION["admin"] = $account["admin"];

                header("Location: /eosge3/dashboard?PHPSESSID=" . session_id());
            }
        }
    }
}
?>

<h2>Bejelentkezés</h2>
<form action="<?php echo $root; ?>/eosge3/auth" method="post">
    <fieldset>
        <h3>Email cím:</h3>
        <input type="email" name="email" autocomplete="email">
        <h3>Jelszó:</h3>
        <input type="password" name="password" autocomplete="current-password"><br>

        <input type="submit" name="login" value="Bejelentkezés">
    </fieldset>
</form>
<span><a href="<?php echo $root; ?>/eosge3/auth/register">Regisztrálnál?</a></span>
</main>
</body>

</html>
