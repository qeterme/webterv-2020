<?php
function getFoods() {
    $jsonFile = file_get_contents("data/foods.json");

    return json_decode($jsonFile, true);
}

function getDataOf($input) {
    $parts1 = explode(":", trim($input)); //pl: [0] = pizza-1-1, [1] = 12 - kaja, darab
    $parts2 = explode("-", trim($parts1[0], '"')); //pl: [0] = pizza, [1] = 12, [2] = 2 - típus, fajta, méret

    $foods = getFoods();

    switch ($parts2[0]) {
        case "pizza":
            return ["name" => $foods["foods"]["pizza"][$parts2[1]]["name"],
                "unitPrice" => $foods["foodSizes"]["pizza"][$parts2[2]]["price"],
                "unitName" => $foods["foodSizes"]["pizza"][$parts2[2]]["name"],
                "pcs" => $parts1[1]];
            break;
        case "roast":
            return ["name" => $foods["foods"]["roasts"][$parts2[1]]["name"],
                "unitPrice" => $foods["foodSizes"]["roasts"][$parts2[2]]["price"],
                "unitName" => $foods["foodSizes"]["roasts"][$parts2[2]]["name"],
                "pcs" => $parts1[1]];
            break;
    }
}

function modifyFoods($param) {
    $json = json_encode($param, JSON_PRETTY_PRINT);
    file_put_contents("data/foods.json", $json);
}
