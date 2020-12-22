<?php   // inquiry_detail.php

// 初期処理の読み込み
require_once("init_admin_auth.php");
require_once(BASEPATH . "/libs/InquiryModel.php");

// pkの(雑な)チェック
$pk = $_GET["inquiry_id"] ?? "";
if ("" === $pk) {
    header("Location: ./admin_top.php");
    exit;
}

// データの取得
$model = InquiryModel::find($pk);
if (null === $model) {
    header("Location: ./admin_top.php");
    exit;
}
// var_dump($model->get());

// 出力用の設定
$context = [
    "detail" => $model->get(),
];
$template_file_name = "admin/inquiry_detail.twig";

// 終了処理の読み込み
require_once("fin.php");
