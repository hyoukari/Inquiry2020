<?php   // session.php

session_start();
var_dump($_SESSION);

$_SESSION["test"] = mt_rand(0, 999);
