<?php
$active = "roast";
require "common/front/head.php";?>

<body>
<?php
require "common/front/menu.php";  ?>
<main id="foodies">
    <script>
        readTextFile("data/foods.json", function (text) {
            let data = JSON.parse(text);
            let main = document.getElementById("foodies");
            const roast = data.foods.roasts;
            const foodSize = data.foodSizes.roasts;

            for (let num in roast) {
                let food = document.createElement("div");
                food.className = "food";

                let name = document.createElement("h3");
                name.innerHTML = roast[num].name;

                let desc = document.createElement("p");
                desc.innerHTML = roast[num].description;

                let imag = document.createElement("img");
                imag.src = "userfiles/images/" + roast[num].image;

                let seld = document.createElement("div");
                seld.className = "select"

                let sele = document.createElement("select");

                for (let opt in roast[num].foodSizes) {
                    const size = roast[num].foodSizes;
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
                butt.productID = "roast-" + num + "-";
                butt.productType = sele;
                butt.productIDName = roast[num].name;
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
