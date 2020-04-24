<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/dashboard/head.php";
require __ROOT__ . "/eosge3/common/dashboard/orders.php";
require __ROOT__ . "/eosge3/common/dashboard/foods.php";
$_SESSION["admin"] = true;
$_SESSION["username"] = "qeterme"; ?>

<body>
<div class="container">
    <?php
    $active = "orders";
    require __ROOT__ . "/eosge3/common/dashboard/menu.php"; ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>DASHBOARD</h4>
                <h2>Rendelések</h2>
            </div>
            <div class="logged">
                <p>Bejelentkezve, mint <b><?php echo $_SESSION["username"]; ?></b></p>
            </div>
        </div>
        <hr>
        <!--    TARTALOM KEZDETE    -->
        <?php
        $sum = 0;
        if ($_SESSION["admin"]) {
            echo <<<EOL
                <table>
                <colgroup><col><col><col><col><col><col></colgroup>
                <thead>
                <tr>
                    <th id="id">ID</th>
                    <th id="order">Rendelés</th>
                    <th id="date">Dátum</th>
                    <th id="costumer">Megrendelő</th>
                    <th id="order-type">Típusa</th>
                    <th id="price">Összeg</th>
                </tr>
                </thead>
                <tbody>
                EOL;
            foreach (array_reverse(getOrders()) as $order) {
                $foodList = explode(",", trim($order["order"], "{}"));
                $price = 0;
                $foods = "";
                foreach ($foodList as $food) {
                    $food = getDataOf($food);
                    $price += ($food["unitPrice"] * $food["pcs"]);
                    $foods .= "<li>" . $food["pcs"] . "x: " . $food["name"] . " (" . $food["unitName"] . ")</li>";
                }

                $sum += $price;

                echo '<tr>
                                <td headers="id">' . $order["id"] . '</td>
                                <td headers="order">
                                    <ul>' . $foods . '</ul>
                                </td>
                                <td headers="date">' . $order["date"] . '</td>
                                <td headers="costumer">
                                    <b>' . $order["costumer"]["name"] . '</b>
                                    <ul>
                                        <li>' . $order["costumer"]["address"] . '</li>
                                        <li>' . $order["costumer"]["tel"] . '</li>
                                        <li>' . $order["costumer"]["email"] . '</li>
                                    </ul>
                                </td>
                                <td headers="order-type">' . $order["order-type"] . '</td>
                                <td headers="price">' . $price . ' Ft</td>
                              </tr>';
            }
            echo <<<EOL
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td>Végösszeg</td>
                    <td>$sum Ft</td>
                </tr>
                </tfoot>
                </table>
                EOL;
        } else {
            echo <<<EOL
                    <table>
                    <colgroup><col><col><col><col><col></colgroup>
                    <thead>
                    <tr>
                        <th id="id">ID</th>
                        <th id="order">Rendelés</th>
                        <th id="date">Dátum</th>
                        <th id="order-type">Típusa</th>
                        <th id="price">Összeg</th>
                    </tr>
                    </thead>
                    <tbody>
                    EOL;
            foreach (array_reverse(getOrders()) as $order) {
                if ($order["costumer"] == $_SESSION["user"]) {
                    $foodList = explode(",", trim($order["order"], "{}"));
                    $price = 0;
                    $foods = "";
                    foreach ($foodList as $food) {
                        $food = getDataOf($food);
                        $price += ($food["unitPrice"] * $food["pcs"]);
                        $foods .= "<li>" . $food["pcs"] . "x: " . $food["name"] . " (" . $food["unitName"] . ")</li>";
                    }

                    $sum += $price;

                    echo '<tr>
                                <td headers="id">' . $order["id"] . '</td>
                                <td headers="order"><ul>' . $foods . '</ul></td>
                                <td headers="date">' . $order["date"] . '</td>
                                <td headers="order-type">' . $order["order-type"] . '</td>
                                <td headers="price">' . $price . ' Ft</td>
                              </tr>';
                }
            }

            echo <<<EOL
                </tbody>
                </table>
                EOL;
        }
        ?>
    </main>
</div>
</body>
</html>
