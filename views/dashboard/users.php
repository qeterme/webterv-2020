<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/dashboard/head.php";
?>

<body>
<div class="container">
    <?php
    $active = "users";
    require __ROOT__ . "/eosge3/common/dashboard/menu.php";

    if ($_SESSION["admin"] == false) {
        header("Location: /eosge3/dashboard?PHPSESSID=" . session_id());
    }
    ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>DASHBOARD</h4>
                <h2>Felhasználók</h2>
            </div>
            <div class="logged">
                <p>Bejelentkezve, mint <b><?php echo $_SESSION["username"]; ?></b></p>
            </div>
        </div>
        <hr>
        <!--    TARTALOM KEZDETE    -->
        <?php
        $accounts = [];
        $file = fopen("data/users.txt", "r");
        while (($line = fgets($file)) !== false) {
            $accounts[] = unserialize($line);
        }
        fclose($file);

        if (empty($_GET["email"])) {
            echo <<<EOL
                <table>
                    <colgroup><col><col><col></colgroup>
                    <thead>
                    <tr>
                        <th id="name">Név</th>
                        <th id="email">Email</th>
                        <th id="edit">Szerkesztés</th>
                    </tr>
                    </thead>
                    <tbody>
                EOL;

            foreach ($accounts as $account) {
                echo '<tr>
                        <td headers="name">' . $account["name"] . '</td>
                        <td headers="email">' . $account["email"] . '</td>
                        <td headers="edit" style="text-align:center;">
                            <a href="/eosge3/dashboard/users?email=' . $account["email"] . '">
                                <img src="/eosge3/icons/spoon.svg" alt="szerkesztés" height="30">
                            </a>
                        </td>
                    </tr>';

            }

            echo <<<EOL
                </tbody>
                </table>
                EOL;
        } else {
            $error = "";
            foreach ($accounts as $account) {
                if ($account["email"] == $_GET["email"]) {
                    $error = "";
                    break;
                } else {
                    $error = "<h1>Nincs ilyen fiók!</h1>";
                }
            }
            echo $error;
            if (empty($error)) {
                echo '
                    <h1>' . $account["email"] . '</h1>
                    <form autocomplete="off" method="post" action="/eosge3/dashboard/users?email=' . $account["email"] . '">               
                        <label>Név: <input type="text" name="name" value="' . $account["name"] . '"></label><br>
                        <label>Cím: <input type="text" name="address" value="' . $account["address"] . '"></label><br>
                        <label>Telefonszám: <input type="tel" name="phone-num" value="' . $account["tel"] . '"></label><br>
                        <label>Admin jogosultság: <input type="checkbox" name="permission" ' . ($account["admin"] ? "checked" : "") . '></label><br>
                
                        <input type="submit" name="edit" value="Módosítás">
                    </form>
                ';
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $accounts = [];
            $file = fopen("data/users.txt", "r");
            while (($line = fgets($file)) !== false) {
                $accounts[] = unserialize($line);
            }
            fclose($file);

            foreach ($accounts as &$account) {
                if ($account["email"] == $_GET["email"]) {
                    $account["name"] = $_POST["name"];
                    $account["address"] = $_POST["address"];
                    $account["tel"] = $_POST["phone-num"];
                    $account["admin"] = isset($_POST["permission"]) ? true : false;
                }
            }

            $file = fopen("data/users.txt", "w");
            foreach ($accounts as $account)
                fwrite($file, serialize($account) . "\n");
            fclose($file);
        }
        ?>
    </main>
</div>
</body>

</html>
