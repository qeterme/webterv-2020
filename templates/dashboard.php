<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/eosge3/common/dashboard/head.php"; ?>

<body>
<div class="container">
    <?php
    $active = "home";
    require "$root/eosge3/common/dashboard/menu.php"; ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>DASHBOARD</h4>
                <h2>Otthon</h2>
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
