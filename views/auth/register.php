<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/auth/base.php";

$root = realpath($_SERVER["SERVER_NAME"]);?>

<h2>Regisztráció</h2>
<form action="#" method="post">
    <fieldset>
        <h3>Neved:</h3>
        <input type="text" name="name">
        <h3>Email cím:</h3>
        <input type="email" name="email">
        <h3>Jelszó:</h3>
        <input type="password" name="password">
        <h3>Jelszó megerősítés:</h3>
        <input type="password" name="password-sec">
        <h3>Címed:</h3>
        <input type="text" name="address">
        <h3>Telefonszámod:</h3>
        <input type="tel" name="phone-num"><br>

        <input type="button" name="login" value="Regisztráció">
    </fieldset>
</form>
<span><a href="<?php echo $root;?>/eosge3/auth">Van már felhasználód?</a></span>
</main>
</body>
</html>
