<?php   // cookie.php
ob_start();

var_dump($_COOKIE);
setcookie("test", mt_rand(0, 999));

ob_end_flush();
