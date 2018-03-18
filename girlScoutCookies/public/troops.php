
<?php
// backend to match the first letter of the girl scout's name to their respective troop and send the
// troop name back to the client
$troops = Array(
    "a" => "Ants",
    "b" => "Brownies",
    "c" => "Cupcakes",
    "d" => "Daredevils",
    "e" => "Eagles",
    "f" => "Frogs",
    "g" => "Giraffes",
    "h" => "Hippos",
    "i" => "Iguanas",
    "j" => "Jellyfish",
    "k" => "Kittens",
    "l" => "Lollipops",
    "m" => "Muffins",
    "n" => "Newts",
    "o" => "Oranges",
    "p" => "Pancakes",
    "q" => "Quails",
    "r" => "Roses",
    "s" => "Sharks",
    "t" => "Tiger",
    "u" => "Unicorns",
    "v" => "Vipers",
    "w" => "Watermelons",
    "x" => "X-rays",
    "y" => "Yaks",
    "z" => "Zebras"
);
$name = $_GET['name'];
$firstLetter = $name[0];
echo $troops[$firstLetter];
?>