<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/dashboard/head.php";
?>

<body>
<div class="container">
    <?php
    $active = "edit-profile";
    require __ROOT__ . "/eosge3/common/dashboard/menu.php";

    $error = "";
    $success = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accounts = [];
        $file = fopen("data/users.txt", "r");
        while (($line = fgets($file)) !== false) {
            $accounts[] = unserialize($line);
        }
        fclose($file);

        if (!empty($_POST["password"]) && ($_POST["password"] != $_POST["password-again"])) {
            $error = "A két jelszó nem egyezik!";
        }

        if (empty($error)) {
            foreach ($accounts as &$account) {
                if ($account["email"] == $_POST["email"]) {
                    $account["name"] = $_POST["name"];
                    $account["address"] = $_POST["address"];
                    $account["tel"] = $_POST["tel"];
                    if (!empty($_POST["password"])) {
                        $account["password"] = hash("sha256", $_POST["password"]);
                    }
                }
            }

            $file = fopen("data/users.txt", "w");
            foreach ($accounts as $account)
                fwrite($file, serialize($account) . "\n");
            fclose($file);

            $_SESSION["username"] = $_POST["name"];
            $_SESSION["user"] = [
                "name" => $_POST["name"],
                "address" => $_POST["address"],
                "tel" => $_POST["tel"],
                "email" => $_POST["email"]
            ];

            $success = "Az adatok frissítése megtörtént!";
        }
    } ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>PROFIL</h4>
                <h2>Szerkesztés</h2>
            </div>
            <div class="logged">
                <p>Bejelentkezve, mint <b><?php echo $_SESSION["username"]; ?></b></p>
            </div>
        </div>
        <hr>
        <!--    TARTALOM KEZDETE    -->
        <?php
        if (!empty($error)) {
            echo '<div class="error"><b>Hiba!</b> ' . $error . '</div>';
        } else if (!empty($success)) {
            echo '<div class="success"><b>Siker! </b>' . $success . '</div>';
        }
        ?>
        <form method="post" action="/eosge3/dashboard/edit/profile">
            <h3>Adatok szerkesztése</h3>
            <label>Név: <input type="text" name="name" value="<?php echo $_SESSION["user"]["name"]; ?>"
                               autocomplete="name"></label><br>
            <label>Email: <input type="email" name="email"
                                 value="<?php echo $_SESSION["user"]["email"]; ?>" disabled></label><br>
            <label>Telefonszám: <input type="tel" name="tel"
                                       value="<?php echo $_SESSION["user"]["tel"]; ?>" autocomplete="tel"></label><br>
            <label>Cím: <input type="text" name="address" value="<?php echo $_SESSION["user"]["address"]; ?>"></label>
            <input type="hidden" name="email" value="<?php echo $_SESSION["user"]["email"]; ?>">

            <h3>Jelszócsere</h3>
            <label>Jelszó: <input type="password" name="password" autocomplete="new-password"></label><br>
            <label>Jelszó mégegyszer: <input type="password" name="password-again"
                                             autocomplete="new-password"></label><br>
            <input type="submit" name="edit" value="Szerkesztés">
        </form>
    </main>
</div>
</body>
</html>
