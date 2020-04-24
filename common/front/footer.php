<?php $root = realpath($_SERVER["SERVER_NAME"]); ?>
<footer>
    <p>Címünk: 6720 Szeged, Aradi vértanúk tere 1.<br>
        TeleFax: +36 (62) 34-3480<br>
        Email: ttkdh@sci.u-szeged.hu</p>
    <hr>
    <a href="<?php echo $root; ?>/eosge3/privacy">Adatkezelési irányelveink</a> |
    <a href="<?php echo $root; ?>/eosge3/terms">Általános Szerződési Feltételek</a> | <a
            href="<?php echo $root; ?>/eosge3/contactus">Kapcsolat</a>
    <?php
    if (!empty($_SESSION["username"])) {
        echo ' | <a href="' . $root . '/eosge3/dashboard">Dashboard</a>';
    } else {
        echo ' | <a href="' . $root . '/eosge3/auth">Bejelentkezés</a>';
    } ?>
</footer>
