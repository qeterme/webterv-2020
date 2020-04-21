function readTextFile(file, callback) {
    let rawFile = new XMLHttpRequest();
    rawFile.overrideMimeType("application/json");
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function () {
        if (rawFile.readyState === 4 && rawFile.status == "200") {
            callback(rawFile.responseText);
        }
    }
    rawFile.send(null);
}

async function addedToCart(name, size, text) {
    let title = name + " - " + size;
    let price = calculateTotal(text);

    let box = document.getElementById("added-to-cart");
    box.style = "";

    let titlePlace = document.getElementById("cart");
    let pricePlace = document.getElementById("cart-price");

    titlePlace.innerHTML = "<b>" + title + "</b> sikeresen a kosárba rakva!";
    pricePlace.innerHTML = price.toLocaleString('hu-HU', {
        style: 'currency',
        currency: 'HUF',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    cartNotification();

    await new Promise((resolve, reject) => setTimeout(resolve, 2500));

    box.style = "display:none;";
}

function addToCart(event) {
    let productID = event.currentTarget.productID;
    let productType = event.currentTarget.productType.options[event.currentTarget.productType.selectedIndex].value;
    let product = productID + productType;

    if (localStorage.cart === undefined) {
        localStorage.setItem("cart", "{}");
    }

    let storage = JSON.parse(localStorage.getItem("cart"));
    if (!storage.hasOwnProperty(product)) {
        storage[product] = 0;
    }

    storage[product] += 1;
    localStorage.setItem("cart", JSON.stringify(storage));
    addedToCart(event.currentTarget.productIDName, event.currentTarget.productType.options[event.currentTarget.productType.selectedIndex].label, event.currentTarget.text);
}

function calculateTotal(text) {
    if (localStorage.cart === undefined || localStorage.cart === "{}") {
        return;
    } else {
        let storage = JSON.parse(localStorage.getItem("cart"));
        let data = JSON.parse(text);

        const roast = data.foods.roasts;
        const roastFoodSize = data.foodSizes.roasts;
        const pizza = data.foods.pizza;
        const pizzaFoodSize = data.foodSizes.pizza;

        let total = 0;

        for (let obj in storage) {
            let product = obj.split("-");
            let productID;
            let productTypeSize;
            let productSize = product[2];

            if (product[0] === "pizza") {
                productID = pizza;
                productTypeSize = pizzaFoodSize;
            } else {
                productID = roast;
                productTypeSize = roastFoodSize;
            }

            total += storage[obj] * productTypeSize[productSize].price;
        }

        return total;
    }
}

function cartNotification() {
    let notificationIcon = document.getElementById("cart-noti");
    if (localStorage.cart === undefined || localStorage.cart === "{}") {
        notificationIcon.style = "display:none;";
    } else {
        notificationIcon.style = "";
    }
}

window.onload = function() {
    cartNotification();
};
