<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/auth/base.php";

$root = realpath($_SERVER["SERVER_NAME"]);?>

<h2></h2>
<form action="#" method="post">
    <fieldset>
        <h3>Email cím:</h3>
        <input type="email" name="email">
        <h3>Jelszó:</h3>
        <input type="password" name="password"><br>

        <input type="button" name="login" value="Bejelentkezés">
    </fieldset>
</form>
<span><a href="<?php echo $root;?>/eosge3/auth/register">Regisztrálnál?</a></span>
</main>
</body>
</html>
