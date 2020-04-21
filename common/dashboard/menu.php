<?php
session_start();
$root = realpath($_SERVER["SERVER_NAME"]);
?>
<aside>
    <header>
        <img src="<?php echo $root;?>/eosge3/icons/logo.svg" alt="logo">
    </header>
    <nav class="menu">
        <h5>DASHBOARD</h5>
        <ul>
            <li <?php if ($active == "home") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/dashboard">Otthon</a></li>
            <li <?php if ($active == "orders") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/dashboard/orders">Rendelések</a></li>

            <?php
                if ($active == "edit-site" && $_SESSION["admin"] && isset($_SESSION["admin"])) {
                    echo '<li class="selected"><a href="' . $root . '/eosge3/dashboard/edit/site">Oldal szerkesztése</a></li>';
                } elseif ($_SESSION["admin"] && isset($_SESSION["admin"])) {
                    echo '<li><a href="' . $root . '/eosge3/dashboard/edit/site">Oldal szerkesztése</a></li>';
                }
                ?>

            <li <?php if ($active == "users") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/dashboard/users">Felhasználók</a></li>
        </ul>
        <h5>PROFIL</h5>
        <ul>
            <li <?php if ($active == "edit-profile") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/dashboard/edit/profile">Szerkesztés</a></li>
            <li><a href="#">Kijelentkezés</a></li>
        </ul>
    </nav>
    <hr>
    <p class="copyright"><span title="Majd meglátod ;)">4020</span> - TTIK Pizzéria™</p>
</aside>
