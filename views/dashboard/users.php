<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/dashboard/head.php"; ?>

<body>
<div class="container">
    <?php
    $active = "users";
    require __ROOT__ . "/eosge3/common/dashboard/menu.php"; ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>DASHBOARD</h4>
                <h2>Felhasználók</h2>
            </div>
            <div class="logged">
                <p>Bejelentkezve, mint <b><?php echo $_SESSION["username"];?></b></p>
            </div>
        </div>
        <hr>
        <!--    TARTALOM KEZDETE    -->
    </main>
</div>
</body>
</html>
