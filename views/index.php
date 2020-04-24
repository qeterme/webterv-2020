<?php
$active = "home";
require "common/front/head.php"; ?>

<body>
<?php
require "common/front/menu.php"; ?>
<main>
    <div class="slide">
        <?php
        $file = fopen("data/slides.txt", "r");
        $slides = [];
        while (($line = fgets($file)) !== false)
            $slides[] = unserialize($line);
        fclose($file);

        foreach ($slides as $slide) {
            echo '<img src="userfiles/slides/' . $slide["image"] . '" alt="' . $slide["description"] . '">';
        }
        ?>
    </div>
</main>
<?php require "common/front/footer.php"; ?>
</body>
