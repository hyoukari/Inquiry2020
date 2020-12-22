<?php   // swiftmailer.php
// html://dev2.m-fr.net/hyoukari/inquiry/swiftmailer.php

require_once(__DIR__ . "/../vendor/autoload.php");

//
$from = "hyoukari@dev2.m-fr.net";
$to = "zasaga134@ruru.be";
$subject = "test subject";
$message = "test message body";

// メール本体を作る
$message = (new Swift_Message($subject))
    ->setFrom($from)
    ->setTo($to)
    ->setBody($message);
// var_dump($message);

// 送信する
// $smtp = new Swift_SmtpTransport("localhost", 25);
// // var_dump($smtp);
// $r = (new Swift_Mailer($smtp))->send($message);
// var_dump($r);
