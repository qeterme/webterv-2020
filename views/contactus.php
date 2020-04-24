<?php
$active = "contactus";
require "common/front/head.php";?>

<body>
<?php
require "common/front/menu.php"; ?>
<main>
    <h2>Lépjen kapcsolatba velünk!</h2>
    <p>Elérhetőségeinket az alábbi táblázatban találja.</p>
    <table>
        <caption>Elérhetőségeink</caption>
        <thead>
        <tr>
            <th id="address">Címünk</th>
            <th id="phone">Telefonszámunk</th>
            <th id="email">Ímélcímünk</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th headers="address"><a href="https://maps.google.com/?q=6720%20Szeged,%20Aradi%20vértanúk%20tere%201.">6720
                    Szeged, Aradi vértanúk tere 1.</a></th>
            <th headers="phone"><a href="tel:+3662343480">+36 (62) 34-3480</a></th>
            <th headers="email"><a href="mailto:ttkdh@sci.u-szeged.hu">ttkdh@sci.u-szeged.hu</a></th>
        </tr>
        </tbody>
    </table>
</main>
<?php require "common/front/footer.php"; ?>
</body>
