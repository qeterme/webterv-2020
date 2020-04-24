<?php
function getSlides() {
    $file = fopen("data/slides.txt", "r");
    $slides = [];
    while (($line = fgets($file)) !== false)
        $slides[] = unserialize($line);
    fclose($file);
    return $slides;
}

function modifySlides($params) {
    $file = fopen("data/slides.txt", "w");
    foreach ($params as $param)
        fwrite($file, serialize($param) . "\n");
    fclose($file);
}
