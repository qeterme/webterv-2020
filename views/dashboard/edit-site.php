<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require __ROOT__ . "/eosge3/common/dashboard/head.php";
require __ROOT__ . "/eosge3/common/dashboard/foods.php";
require __ROOT__ . "/eosge3/common/dashboard/slides.php";
$error = "";
?>

<body>
<div class="container">
    <?php
    $active = "edit-site";
    require __ROOT__ . "/eosge3/common/dashboard/menu.php";

    if ($_SESSION["admin"] == false) {
        header("Location: /eosge3/dashboard?PHPSESSID=" . session_id());
    }
    ?>
    <main>
        <div class="head">
            <div class="title">
                <h4>DASHBOARD</h4>
                <h2>Oldal szerkesztése</h2>
            </div>
            <div class="logged">
                <p>Bejelentkezve, mint <b><?php echo $_SESSION["username"]; ?></b></p>
            </div>
        </div>
        <hr>
        <!--    TARTALOM KEZDETE    -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_GET["pizza"]) || isset($_GET["roasts"])) {
                $param = isset($_GET["pizza"]) ? "pizza" : "roasts";
                $id = $_GET[$param];

                // ha van új kép, akkor azt elmenteni, ellenőrizni (hibát dobni), négyzetre vágni, beállítani
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $targetDir = __ROOT__ . "/eosge3/userfiles/images/";
                    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
                    $targetFile = $targetDir . basename(hash('md5', $_FILES["file"]["name"]) . '.' . $imageFileType);

                    if ($_FILES["file"]["size"] > 1000000) {
                        $error = "Túl nagy a file! (max. 1MB)";
                    }

                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        $error = "Csak .jpg, .png és .jpeg kiterjesztésű fájlok tölthetőek fel!";
                    }

                    if (empty($error)) {
                        $tmpFile = $_FILES["file"]["tmp_name"];
                        $im = null;
                        switch ($imageFileType) {
                            case "jpg":
                            case "jpeg":
                                $im = imagecreatefromjpeg($tmpFile);
                                break;
                            case "png":
                                $im = imagecreatefrompng($tmpFile);
                                break;
                        }

                        if (!$im) {
                            $error = 'Nem sikerült a kép "phpsítása"!';
                        } else {
                            if (imagesx($im) != imagesy($im)) {
                                //megkeressük a hosszabb oldalt, kivonjuk belőle a rövidebbet, /2-vel elcsúsztatjuk a hosszabbon a cutot
                                if (imagesx($im) > imagesy($im)) {
                                    $offset = (imagesx($im) - imagesy($im)) / 2;
                                    $cut = imagecrop($im, ["x" => $offset, "y" => 0, "width" => imagesy($im), "height" => imagesy($im)]);
                                } else {
                                    $offset = (imagesy($im) - imagesx($im)) / 2;
                                    $cut = imagecrop($im, ["x" => 0, "y" => $offset, "width" => imagesx($im), "height" => imagesx($im)]);
                                }

                                if (!$im) {
                                    $error = "Nem sikerült megvágni a képet!";
                                } else {
                                    switch ($imageFileType) {
                                        case "jpg":
                                        case "jpeg":
                                            $im = imagejpeg($cut, $tmpFile);
                                            break;
                                        case "png":
                                            $im = imagepng($cut, $tmpFile);
                                            break;
                                    }
                                }
                            }
                            if (move_uploaded_file($tmpFile, $targetFile)) {
                                $foods = getFoods();
                                foreach ($foods["foods"][$param] as $foodId => &$value) {
                                    if ($foodId == $id) {
                                        $value["name"] = $_POST["name"];
                                        $value["description"] = $_POST["description"];
                                        $value["image"] = hash('md5', $_FILES["file"]["name"]) . '.' . $imageFileType;
                                        $value["foodSizes"] = $_POST["options"];
                                    }
                                }
                                modifyFoods($foods);
                                $success = "A(z) " . basename($_FILES["file"]["name"]) . " fájl feltöltése megtörtént, az adatok módosításra kerültek!";
                            } else {
                                $error = "Nem sikerült a fájl feltöltése, az adatok sem módosultak!";
                            }
                        }
                    }
                } else {
                    $foods = getFoods();
                    foreach ($foods["foods"][$param] as $foodId => &$value) {
                        if ($foodId == $id) {
                            $value["name"] = $_POST["name"];
                            $value["description"] = $_POST["description"];
                            $value["image"] = $_POST["image-select"];
                            $value["foodSizes"] = $_POST["options"];
                        }
                    }
                    modifyFoods($foods);
                    $success = "Az adatok módosításra kerültek!";
                }
            } else if ($_GET["part"] == "slide") {
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $targetDir = __ROOT__ . "/eosge3/userfiles/slides/";
                    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
                    $targetFile = $targetDir . basename(hash('md5', $_FILES["file"]["name"]) . '.' . $imageFileType);

                    if ($_FILES["file"]["size"] > 2000000) {
                        $error = "Túl nagy a file! (max. 2MB)";
                    }

                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        $error = "Csak .jpg, .png és .jpeg kiterjesztésű fájlok tölthetőek fel!";
                    }

                    if (empty($error)) {
                        $tmpFile = $_FILES["file"]["tmp_name"];
                        $im = null;
                        switch ($imageFileType) {
                            case "jpg":
                            case "jpeg":
                                $im = imagecreatefromjpeg($tmpFile);
                                break;
                            case "png":
                                $im = imagecreatefrompng($tmpFile);
                                break;
                        }

                        if (!$im) {
                            $error = 'Nem sikerült a kép "phpsítása"!';
                        } else {
                            if (imagesx($im) != 1500 && imagesy($im) != 750) {
                                $error = "Rossz felbontás!";
                            }
                        }

                        if (empty($error)) {
                            if (move_uploaded_file($tmpFile, $targetFile)) {
                                $slides = getSlides();
                                for ($i = 0; $i < 4; $i++) {
                                    $slide = &$slides[$i];
                                    $slide["description"] = $_POST[$i . "-description"];
                                    $slide["image"] = $_POST[$i . "-image-select"];
                                }
                                modifySlides($slides);

                                $success = "A(z) " . basename($_FILES["file"]["name"]) . " fájl feltöltése megtörtént, az adatok módosításra kerültek!";
                            } else {
                                $error = "Nem sikerült a fájl feltöltése, az adatok sem módosultak!";
                            }
                        }
                    }
                } else {
                    $slides = getSlides();
                    for ($i = 0; $i < 4; $i++) {
                        $slide = &$slides[$i];
                        $slide["description"] = $_POST[$i . "-description"];
                        $slide["image"] = $_POST[$i . "-image-select"];
                    }
                    modifySlides($slides);
                    $success = "Az adatok módosításra kerültek!";
                }
            }
        }

        function table($param) {
            switch ($param) {
                case "top":
                    echo <<<EOL
                            <table>
                                <colgroup><col><col><col></colgroup>
                                <thead>
                                    <tr>
                                        <th>Kép</th>
                                        <th>Név</th>
                                        <th>Szerkesztés</th>
                                    </tr>
                                </thead>
                                <tbody>
                            EOL;
                    break;
                case "bottom":
                    echo <<<EOL
                            </tbody>
                            </table>
                            EOL;
                    break;
            }
        }

        function showFoods($param) {
            $foods = getFoods();
            table("top");

            for ($i = 0; $i < count($foods["foods"][$param]); $i++) {
                $iplusz = $i + 1;
                $food = $foods["foods"][$param][$iplusz];
                echo '<tr><td><img alt="' . $food["name"] . '" src="/eosge3/userfiles/images/' . $food["image"] . '"></td>
                                    <td>' . $food["name"] . '</td>
                                    <td><a href="/eosge3/dashboard/edit/site?' . ($param == "pizza" ? "pizza" : "roasts") . '=' . $iplusz . '"><img src="/eosge3/icons/spoon.svg" alt="szerkesztés" height="30"></a></td>
                                  </tr>';
            }

            table("bottom");
        }

        function editFood($param) {
            $food = getFoods()["foods"][$param][$_GET[$param]];

            echo '<h1>' . $food["name"] . '</h1>';
            if (!empty($GLOBALS['error'])) {
                echo '<div class="error"><b>Hiba!</b> ' . $GLOBALS['error'] . '</div>';
            } else if (!empty($GLOBALS["success"])) {
                echo '<div class="success"><b>Siker!</b> ' . $GLOBALS["success"] . '</div>';
            }

            echo <<<EOL
                <form action="/eosge3/dashboard/edit/site?$param={$_GET[$param]}" method="post" autocomplete="off" enctype="multipart/form-data">
                    <h3>Szerkessz adatot,</h3>
                    <label>Név: <input type="text" name="name" value="{$food["name"]}"></label><br>
                    <label>Leírás: <input type="text" name="description" value="{$food["description"]}"></label><br>
                    <label>Méretek: </label>
                    <div class="items">
                EOL;

            for ($i = 0; $i < count(getFoods()["foodSizes"][$param]); $i++) {
                $iplusz = $i + 1;
                $option = getFoods()["foodSizes"][$param][$iplusz];
                echo '<div>
                        <label>
                        <input type="checkbox" name="options[]" value="' . $iplusz . '" ' . (in_array($iplusz, $food["foodSizes"]) ? "checked" : "") . '> '
                    . $option["name"] . ' (' . $option["price"] . ' Ft)</label>
                      </div>';
            }

            echo '</div>
                <h3>válassz képet</h3>';
            foreach (array_diff(scandir(__ROOT__ . "/eosge3/userfiles/images"), array('..', '.')) as $img) {
                echo '<label>
                        <input type="radio" name="image-select" value="' . $img . '" ' . ($img == $food["image"] ? "checked" : "") . '>
                        <img height="100" alt="' . $img . '" src="/eosge3/userfiles/images/' . $img . '">
                      </label>';
            }

            echo <<<EOL
                <h3>vagy tölts fel egy újat</h3>
                <p>Követelmények: .png, .jpg, .jpeg kiterjesztés</p>
                <input type="file" name="file"><br>
                <input type="submit" name="save" value="Mentés">
                </form>
                EOL;
        }

        function editSlides() {
            if (!empty($GLOBALS['error'])) {
                echo '<div class="error"><b>Hiba!</b> ' . $GLOBALS['error'] . '</div>';
            } else if (!empty($GLOBALS["success"])) {
                echo '<div class="success"><b>Siker! </b>' . $GLOBALS["success"] . '</div>';
            }

            $i = 0;
            echo '<form action="/eosge3/dashboard/edit/site?part=slide" method="post" autocomplete="off" enctype="multipart/form-data">';
            do {
                $slide = getSlides()[$i];
                $iplusz = $i + 1;
                echo <<<EOL
                    <h3>$iplusz. slide szerkesztése</h3>
                    <label>Leírás: <input type="text" name="$i-description" value="{$slide["description"]}"></label><br>
                    EOL;

                foreach (array_diff(scandir(__ROOT__ . "/eosge3/userfiles/slides"), array('..', '.')) as $img) {
                    echo '<label>
                        <input type="radio" name="' . $i . '-image-select" value="' . $img . '" ' . ($img == $slide["image"] ? "checked" : "") . '>
                        <img width="150" height="75" alt="' . $img . '" src="/eosge3/userfiles/slides/' . $img . '">
                      </label>';
                }
                $i++;
            } while ($i < 4);
            echo <<<EOL
                <h3>Tölts fel egy új képet</h3>
                <p>Követelmények: .png, .jpg, .jpeg kiterjesztés; 1500x750px-es felbontás</p>
                <input type="file" name="file"><br>
                <input type="submit" name="save" value="Mentés">
                </form>
                EOL;
        }

        if (empty($_GET["part"]) && empty($_GET["pizza"]) && empty($_GET["roasts"]) && empty($_GET["slide"])) {
            $sessid = session_id();
            echo <<<EOL
                    <script>
                    function setGet(dropdown) {
                        switch(dropdown.options[dropdown.selectedIndex].value) {
                            case "pizza":
                                window.location = "/eosge3/dashboard/edit/site?part=pizza&PHPSESSID=$sessid";
                                break;
                            case "roast":
                                window.location = "/eosge3/dashboard/edit/site?part=roasts&PHPSESSID=$sessid";
                                break;
                            case "slide":
                                window.location = "/eosge3/dashboard/edit/site?part=slide&PHPSESSID=$sessid";
                                break;
                            default: break;
                        }
                        
                    }
                    </script>
                    
                    <label for="selector">Válaszd ki mit szeretnél szerkeszteni:</label>
                    <select id="selector" onchange="setGet(this)">
                        <option disabled selected value> -- válassz -- </option>
                        <option value="pizza">Pizza</option>
                        <option value="roast">Sültek</option>
                        <option value="slide">Slideok</option>
                    </select>
                    EOL;
        } else if (!empty($_GET["part"])) {
            switch ($_GET["part"]) {
                case "pizza":
                    showFoods("pizza");
                    break;
                case "roasts":
                    showFoods("roasts");
                    break;
                case "slide":
                    editSlides();
                    break;
                default:
                    header("Location: /eosge3/dashboard/edit/site");
            }
        } else if (!empty($_GET["pizza"])) {
            editFood("pizza");
        } else if (!empty($_GET["roasts"])) {
            editFood("roasts");
        }
        ?>
    </main>
</div>
</body>

</html>
