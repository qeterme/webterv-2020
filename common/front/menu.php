<?php $root = realpath($_SERVER["SERVER_NAME"]); ?>
<header>
    <figure class="brand"><a href="<?php echo $root;?>/eosge3/"><img src="<?php echo $root;?>/eosge3/icons/logo.svg" alt="logo"> TTIK Pizzéria</a></figure>
    <nav class="menu">
        <input type="checkbox" id="menuToggle">
        <label for="menuToggle" class="menu-icon"><img class="menu-icon" src="<?php echo $root;?>/eosge3/icons/hamburger.svg"
                                                       alt="Menü ikon"></label>
        <ul>
            <li  <?php if ($active == "home") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/">Promóciók</a></li>
            <li  <?php if ($active == "pizza") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/pizza">Pizzák</a></li>
            <li  <?php if ($active == "roast") {echo "class=\"selected\"";} ?>><a href="<?php echo $root;?>/eosge3/roast">Sültek</a></li>
            <li class="action<?php if ($active == "order") {echo " selected";} ?>"><a href="<?php echo $root;?>/eosge3/order">Rendelés<img id="cart-noti" src="<?php echo $root;?>/eosge3/icons/notification.svg" alt="kosár értesítő"></a></li>
        </ul>
    </nav>
</header>
