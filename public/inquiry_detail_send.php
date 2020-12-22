<?php   // inquiry_detail_send.php

// 初期処理の読み込み
require_once("init_admin_auth.php");
require_once(BASEPATH . "/libs/InquiryModel.php");

// データの取得
$params = ["inquiry_id", "reply_charge", "reply_subject", "reply_body"];
$data = [];
$error_message = [];
foreach ($params as $p) {
    if ("" === ($data[$p] = strval($_POST[$p] ?? ""))) {
        $error_message[] = "{$p}は必須項目です";
    }
}

// var_dump($data, $error_message);
if ([] !== $error_message) {
    // xxx
    var_dump($error_message);
    exit;
}

$model = InquiryModel::find($data["inquiry_id"]);
// var_dump($model);

// エラーチェック(model)
if (null === $model) {
    // xxx
    echo "no model";
    exit;
}
// xxx エラーチェック(未送信か？)
if (null !== $model->reply_at) {
    // xxx
    echo "すでに返信済みです\n";
    exit;
}

// mail送信
$from = "hyoukari@dev2.m-fr.net";
$message = (new Swift_Message($data["reply_subject"]))
    ->setFrom($from)
    ->setTo($model->email)
    ->setBody($mdata["reply_body"]);
// var_dump($message);

// 送信する
// $smtp = new Swift_SmtpTransport("localhost", 25);
// // var_dump($smtp);
// $r = (new Swift_Mailer($smtp))->send($message);
// var_dump($r);
// xxx 戻り値はboolではなくint
// if (0 === $r) {
//     // xxx
//     echo "メールが上手く送れなかったよ？";
//     exit;
// }

// データの更新
foreach ($params as $p) {
    // pkは更新しない
    if ("inquiry_id" === $p) {
        continue;
    }
    //
    $model->$p = $data[$p];
}
//
$model->reply_at = (new DateTime())->format("Y-m-d H:i:s");
// var_dump($model->get());

//
$r = $model->update();
if (false === $r) {
    // xxx
    echo "updateで失敗しました";
    var_dump(DbHandle::get()->errorInfo());
    exit;
}

//
header("Location: inquiry_detail.php?inquiry_id=" . rawurldecode($model->inquiry_id));
