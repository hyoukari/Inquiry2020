<?php   // init.php
//
ob_start();
session_start();

//
define("BASEPATH", realpath((__DIR__ . "/..")));
// var_dump(__DIR__);
// var_dump(__DIR__ . "/..");
// var_dump(realpath(__DIR__ . "/.."));
// exit;

// vendorを使うので
require_once(BASEPATH . '/vendor/autoload.php');
// Configクラスを読み込み
require_once(BASEPATH . "/libs/Config.php");
// DbHandleクラスを読み込み
require_once(BASEPATH . "/libs/DbHandle.php");


// Twigインスタンスを生成
$path = BASEPATH . '/templates';
$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader($path));
