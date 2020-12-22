<?php   //　admin_logout.php

// 初期処理の読み込み
require_once("init.php");

// ログアウト処理
unset($_SESSION["admin"]);

// 遷移
header("Location: admin.php");
