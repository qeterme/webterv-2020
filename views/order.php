<?php
$active = "order";
require "common/front/head.php"; ?>

<body>
<?php
require "common/front/menu.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orders = [];
    $file = fopen("data/orders.txt", "r");
    while (($line = fgets($file)) !== false) {
        $orders[] = unserialize($line);
    }
    fclose($file);

    $nextId = -1;

    if (!empty($orders)) {
        $nextId = end($orders)["id"] + 1;
    } else {
        $nextId = 1;
    }

    $error = "";

    if (isset($_POST["how"]) && !empty($_POST["full-name"]) && !empty($_POST["address"]) && !empty($_POST["phone-number"]) && !empty($_POST["email"])) {
        if ((!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) {
            $error = "Hibás email cím formátum!";
        }
    } else {
        $error = "Valami nincs kitöltve?";
    }
    echo $_POST["full-name"];
    if (empty($error)) {
        $orders = [[
            "id" => $nextId,
            "order" => $_POST["order-cart"],
            "date" => date("Y-m-d H:m:s"),
            "costumer" => [
                "name" => $_POST["full-name"],
                "address" => $_POST["address"],
                "tel" => $_POST["phone-number"],
                "email" => $_POST["email"]],
            "order-type" => $_POST["how"]
        ]];

        file_put_contents("data/orders.txt", serialize(end($orders)) . PHP_EOL, FILE_APPEND | LOCK_EX);
        echo '<script>localStorage.setItem("cart", "{}")</script>';
    }
}
?>
<main id="order-page" class="empty-cart">
    <div id="cart">
        <h2>Kosarad</h2>
        <div id="cards"></div>
        <h3 id="total">Összesen: </h3>
    </div>
    <div id="order-details">
        <h2>Rendelés</h2>
        <?php
        if (!empty($error)) {
            echo '<div class="error"><b>Hiba!</b> ' . $error . '</div>';
        }
        ?>
        <form action="/eosge3/order" method="post">
            <fieldset>
                <legend>Átvételi módok</legend>
                <input type="radio" id="delivery" name="how" value="deilvery">
                <label for="delivery">WebFelszolgáló Rajt házhozszállítás</label><br>
                <input type="radio" id="pickup" name="how" value="pickup">
                <label for="pickup">Jössz, viszed</label><br>
                <input type="radio" id="eat" name="how" value="eat">
                <label for="eat">Jössz, itt eszed</label>
            </fieldset>
            <?php
            if (empty($_SESSION["user"])) {
                echo <<<EOL
                <fieldset>
                    <legend>Megrendelő adatai</legend>
                    <label>Neved: <input type="text" name="full-name" size="30" autocomplete="name"></label><br>
                    <label>Címed: <input type="text" name="address" size="30"></label><br>
                    <label>Telefonszámod: <input type="tel" name="phone-number" size="30" autocomplete="tel"></label><br>
                    <label>Email címed: <input type="email" name="email" size="30" autocomplete="email"></label><br>
                </fieldset>
                EOL;
            } else {
                echo <<<EOL
                <fieldset>
                    <legend>Megrendelő adatai</legend>
                    <label>Neved: <input type="text" name="full-name" size="30" value="{$_SESSION["user"]["name"]}" disabled></label><br>
                    <label>Címed: <input type="text" name="address" size="30" value="{$_SESSION["user"]["address"]}" disabled></label><br>
                    <label>Telefonszámod: <input type="tel" name="phone-number" size="30" value="{$_SESSION["user"]["tel"]}" disabled></label><br>
                    <label>Email címed: <input type="email" name="email" size="30" value="{$_SESSION["user"]["email"]}" disabled></label><br>
                </fieldset>
                
                <input type="hidden" name="full-name" value="{$_SESSION["user"]["name"]}">
                <input type="hidden" name="address" value="{$_SESSION["user"]["address"]}">
                <input type="hidden" name="phone-number" value="{$_SESSION["user"]["tel"]}">
                <input type="hidden" name="email" value="{$_SESSION["user"]["email"]}">
                EOL;
            }
            ?>
            <input type="hidden" id="order-cart" name="order-cart">

            <input type="submit" name="order" value="Megrendelés">
        </form>
        <?php
        if (empty($_SESSION["user"])) {
            echo '<p>Az egyszerűbb megrendelés érdekében következő alkalommal <a href="/eosge3/auth/register">regisztrálj</a> és <a href="/eosge3/auth">lépj be</a>!</p>';
        } ?>
    </div>
    <div id="cart-empty">
        <h2>Sajnos üres a kosarad</h2>
        <p>Menj és rakj néhány pizzát és pár darab nuggetset a virtuális kosaradba!</p>
    </div>
    <script>
        function showCart() {
            let cartEmpty = document.getElementById("cart-empty"); // see
            let cart = document.getElementById("cart"); // not see
            let orderDetails = document.getElementById("order-details"); // not see
            let orderPage = document.getElementById("order-page"); // empty-cart class
            let orderCart = document.getElementById("order-cart"); // hidden input

            if (localStorage.cart === undefined || localStorage.cart === "{}") {
                localStorage.setItem("cart", "{}");

                cartEmpty.style = "";
                cart.style = "display:none;";
                orderDetails.style = "display:none;"
                orderPage.className = "empty-cart";
            } else {
                cartEmpty.style = "display:none;";
                cart.style = "";
                orderDetails.style = ""
                orderPage.className = "";
            }

            orderCart.value = localStorage.getItem("cart");
        }

        function writeTotal(text) {
            if (localStorage.cart === undefined || localStorage.cart === "{}") {
                return;
            } else {
                let totalPlace = document.getElementById("total");
                let total = calculateTotal(text);
                totalPlace.innerHTML = "Összesen: " + total.toLocaleString('hu-HU', {
                    style: 'currency',
                    currency: 'HUF',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }

        function deleteProductCCP(cart, card, productID) {
            let storage = JSON.parse(localStorage.getItem("cart"));

            cart.removeChild(card);
            delete storage[productID];

            localStorage.setItem("cart", JSON.stringify(storage));
            cartNotification();
        }

        function deleteProductE(event) {
            let storage = JSON.parse(localStorage.getItem("cart"));

            event.currentTarget.cart.removeChild(event.currentTarget.card);
            delete storage[event.currentTarget.productID];
            console.log(storage, event.currentTarget.productID);

            localStorage.setItem("cart", JSON.stringify(storage));
            cartNotification();

            showCart();
            writeTotal(event.currentTarget.text);
        }

        function pcsModify(event) {
            let productID = event.currentTarget.prodID;
            let pcs = event.currentTarget.value;
            let price = event.currentTarget.price;
            let fipr = event.currentTarget.finalPrice;
            let card = event.currentTarget.card;
            let cart = event.currentTarget.cart;
            fipr.innerHTML = "= " + (pcs * price).toLocaleString('hu-HU', {
                style: 'currency',
                currency: 'HUF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            let storage = JSON.parse(localStorage.getItem("cart"));
            storage[productID] = parseInt(pcs);
            localStorage.setItem("cart", JSON.stringify(storage));

            if (parseInt(pcs) < 1) {
                deleteProductCCP(cart, card, productID);
            }

            showCart();
            writeTotal(event.currentTarget.text);
        }

        readTextFile("data/foods.json", function (text) {
            let cart = document.getElementById("cards");
            let storage = JSON.parse(localStorage.getItem("cart"));

            showCart();
            writeTotal(text);

            let data = JSON.parse(text);

            const roast = data.foods.roasts;
            const roastFoodSize = data.foodSizes.roasts;
            const pizza = data.foods.pizza;
            const pizzaFoodSize = data.foodSizes.pizza;

            for (let obj in storage) {
                let product = obj.split("-");
                let productID;
                let productTypeSize;
                let productType = product[1];
                let productSize = product[2];

                if (product[0] === "pizza") {
                    productID = pizza;
                    productTypeSize = pizzaFoodSize;
                } else {
                    productID = roast;
                    productTypeSize = roastFoodSize;
                }

                let cartItem = document.createElement("div");
                cartItem.className = "cart-item";

                let imag = document.createElement("img");
                imag.src = "userfiles/images/" + productID[productType].image;

                let dele = document.createElement("img")
                dele.id = "delete";
                dele.src = "icons/delete.svg";
                dele.cart = cart;
                dele.card = cartItem;
                dele.productID = obj;
                dele.text = text;
                dele.addEventListener("click", deleteProductE);

                let name = document.createElement("h2");
                name.innerHTML = productID[productType].name + " - " + productTypeSize[productSize].name;

                let desc = document.createElement("p");
                desc.innerHTML = productID[productType].description;

                let pric = document.createElement("p");
                pric.className = "price";
                pric.innerHTML = productTypeSize[productSize].price.toLocaleString('hu-HU', {
                    style: 'currency',
                    currency: 'HUF',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }) + " * ";

                let fipr = document.createElement("p"); // final price
                fipr.className = "price";
                fipr.innerHTML = "= " + (storage[obj] * productTypeSize[productSize].price).toLocaleString('hu-HU', {
                    style: 'currency',
                    currency: 'HUF',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                let pcin = document.createElement("input"); // pcs input
                pcin.className = "price";
                pcin.type = "number";
                pcin.name = "pcs";
                pcin.value = storage[obj];
                pcin.prodID = obj;
                pcin.price = productTypeSize[productSize].price;
                pcin.finalPrice = fipr;
                pcin.card = cartItem;
                pcin.cart = cart;
                pcin.text = text;
                pcin.addEventListener("input", pcsModify);

                cartItem.appendChild(dele);
                cartItem.appendChild(imag);
                cartItem.appendChild(name);
                cartItem.appendChild(desc);
                cartItem.appendChild(pric);
                cartItem.appendChild(pcin);
                cartItem.appendChild(fipr);
                cart.appendChild(cartItem);
            }
        });
    </script>
</main>
<?php require "common/front/footer.php"; ?>
</body>
