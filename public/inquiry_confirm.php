<?php   // inquiry_confirm.php

// 初期処理の読み込み
require_once("init.php");

// 入力の受け取り
$parames = ["name", "email", "body"];
$data = [];
foreach ($parames as $p) {
    $data[$p] = strval($_POST[$p] ?? "");
}
// var_dump($data);
// exit;

// validate
$error_messages = [];
// 必須チェック
if ("" === $data["email"]) {
    $error_messages[] = "emailアドレスは必須です。<br>";
}
if ("" === $data["body"]) {
    $error_messages[] = "問い合わせ内容は必須です。<br>";
}
// emailの形式チェック
if (false === filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
    $error_messages[] = "正しいemailアドレスを入力してください。<br>";
}
// var_dump($error_messages);
// exit;
// エラー確認
if ([] !== $error_messages) {
    //
    echo "入力にミスがあります。戻って修正してください。<br>\n";
    foreach ($error_messages as $s) {
        echo $s;
    }
    exit;
}

// xxx validateがOKだったら

// テンプレートにデータを渡す
$context = [];
$context["data"] = $data;
$template_data = $context;

// セッションにデータを格納する
$_SESSION["inquiry_data"] = $data;



// 出力用の設定
$template_file_name = "front/inquiry_confirm.twig";
$template_data = [];

// 終了処理の読み込み
require_once("fin.php");
