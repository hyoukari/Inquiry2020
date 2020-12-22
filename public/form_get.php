<?php   // form_get.php
//
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

// var_dump($_GET);
$value = $_GET["value"] ?? "";
$c = $_GET["c"] ?? "";
if (false === is_array($c)) {
    $c = [$c];
}

// var_dump($value, $c);

$value = h($value);
echo "入力したのは${value}です";
