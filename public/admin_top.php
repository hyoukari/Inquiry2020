<?php   //　admin_top.php

// 初期処理の読み込み
require_once("init_admin_auth.php");
require_once(BASEPATH . "/libs/InquiryModel.php");

//
// var_dump($_GET);

// 検索データの取得　+ sort時の検索条件の保持用情報の作成
$find_item_list = [
    "name",
    "from",
    "to",
];
$find_multiitem_list = [
    "reply_flg",
];
//
$find_items = [];   // 検索データの取得用
$find_params = [];  // sort時の検索条件の保持用
foreach ($find_item_list as $s) {
    $find_items[$s] = strval($_GET[$s] ?? "");
    $find_params[] = "{$s}=" . rawurlencode($find_items[$s]);
}
foreach ($find_multiitem_list as $s) {
    $find_items[$s] = (array)($_GET[$s] ?? []);
    foreach ($find_items[$s] as $k => $v) {
        $find_params[] = rawurlencode("{$s}[]") . "=" . rawurlencode($v);
        // $find_params[] = "{$s}%5B%5D=" . rawurlencode($v);
    }
}
// var_dump($find_items);
// var_dump($find_params);

// sort情報の取得
$sort = strval($_GET["sort"] ?? "");
// var_dump($sort);

// ページング
$page = intval($_GET["page"] ?? 0);
var_dump($page);

// 問い合わせの一覧を取得
$ret = InquiryModel::getList($find_items, $sort, $page);
//
$context = [
    "find_items" => $find_items,
    "find_param_string" => implode("&", $find_params),
    "inquiry_list" => $ret["data"],
    "count" => $ret["count"],
    "max_page" => ceil($ret["count"] / 20) - 1,     // xxx 20はmagic number
];
// var_dump($context);
// var_dump($context["find_param_string"]);

// 出力用の設定
$template_file_name = "admin/admin_top.twig";

// 終了処理の読み込み
require_once("fin.php");
