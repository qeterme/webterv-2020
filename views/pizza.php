<?php
$active = "pizza";
require "common/front/head.php";?>

<body>
<?php
require "common/front/menu.php";  ?>
<main id="foodies">
    <script>
        readTextFile("data/foods.json", function (text) {
            let data = JSON.parse(text);
            let main = document.getElementById("foodies");
            const pizza = data.foods.pizza;
            const foodSize = data.foodSizes.pizza;

            for (let num in pizza) {
                const size = pizza[num].foodSizes;

                let food = document.createElement("div");
                food.className = "food";

                let name = document.createElement("h3");
                name.innerHTML = pizza[num].name;

                let desc = document.createElement("p");
                desc.innerHTML = pizza[num].description;

                let imag = document.createElement("img");
                imag.src = "userfiles/images/" + pizza[num].image;

                let seld = document.createElement("div");
                seld.className = "select"

                let sele = document.createElement("select");

                for (let opt in pizza[num].foodSizes) {
                    let line = document.createElement("option");
                    line.text = foodSize[size[opt]].name + " - " + foodSize[size[opt]].price.toLocaleString('hu-HU', {
                        style: 'currency',
                        currency: 'HUF',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    line.value = size[opt];

                    sele.add(line);
                }

                let butt = document.createElement("input");
                butt.type = "button";
                butt.value = "Kosárba";
                butt.productID = "pizza-" + num + "-";
                butt.productType = sele;
                butt.productIDName = pizza[num].name;
                butt.text = text;
                butt.addEventListener("click", addToCart, false);

                seld.appendChild(sele);
                food.appendChild(name);
                food.appendChild(desc);
                food.appendChild(imag);
                food.appendChild(seld);
                food.appendChild(butt);
                main.appendChild(food);
            }
        });
    </script>
    <div id="added-to-cart" style="display:none;">
        <h2>Kosár értesítő</h2>
        <p id="cart"></p>
        <h3 id="cart-price"></h3>
    </div>
</main>
<?php require "common/front/footer.php"; ?>
</body>
