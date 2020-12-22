<?php   // admin.php

// 初期処理の読み込み
require_once("init.php");

// セッションにflashデータがあったら取得
$context = $_SESSION["admin"]["flash"] ?? [];
unset($_SESSION["admin"]["flash"]);
// var_dump($context);
// exit;

// 出力用の設定
$template_file_name = "admin/admin.twig";

// 終了処理の読み込み
require_once("fin.php");
