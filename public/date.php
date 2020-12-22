<?php   // date.php

echo date("Y-m-d H:i:s"), "<br>";
// sleep(1);
echo date("Y-m-d H:i:s"), "<br>";
echo "<hr>";

//
$t = time();
echo date("Y-m-d H:i:s", $t), "<br>";
// sleep(1);
echo date("Y-m-d H:i:s", $t), "<br>";
echo "<hr>";

//
$date = new DateTimeImmutable();
echo $date->format("Y-m-d H:i:s"), "<br>";
