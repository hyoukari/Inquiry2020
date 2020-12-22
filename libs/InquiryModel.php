<?php   // InquiryModel.php

require_once(BASEPATH . "/libs/Model.php");

class InquiryModel extends Model
{
    // 継承先に、以下がある前提
    protected static $table_name = "inquiry";
    protected static $pk_name = "inquiry_id";

    // 一覧の取得
    public static function getList(array $find_items, string $sort, int $page)
    {
        //
        $dbh = DbHandle::get();
        // var_dump($find_items);
        // var_dump($sort);

        // プリペアードステートメントの作成
        $sql = "FROM inquiry";
        $where = [];
        $binds = [];

        // xxx 名前(曖昧検索)
        if ("" !== $find_items["name"]) {
            $where[] = " name LIKE :name ";
            $binds[":name"] = "%" . str_replace(["%", "_"], ["\\%", "\\_"], $find_items["name"]) . "%";  // エスケープ処理
        }
        // 問い合わせ日時(from-to)
        // BETWEEN:bit演算使って
        $created_at_from_to = 0;    // 1:from, 2:to
        if ("" !== $find_items["from"]) {
            $where[] = " created_at >= :from ";
            $binds[":from"] = date("Y-m-d H:i:s", strtotime("{$find_items['from']} 00:00:00"));
            $created_at_from_to += 1;
        }
        if ("" !== $find_items["to"]) {
            $where[] = " created_at <= :to ";
            $binds[":to"] = date("Y-m-d H:i:s", strtotime("{$find_items['to']} 23:59:59"));
            $created_at_from_to += 2;
        }
        if (1 === $created_at_from_to) {
            $where[] = "created_at >= :from";
        } elseif (2 === $created_at_from_to) {
            $where[] = "created_at <= :to";
        } elseif (3 === $created_at_from_to) {
            $where[] = "created_at BETWEEN :from AND :to";
        }


        // xxx status(未返信/返事済み)
        // bit演算使て
        $reply_flg = 0;
        if (true === in_array("0", $find_items["reply_flg"], true)) {
            echo "未返信がchecked\n";
            $reply_flg += 1;
        }
        if (true === in_array("1", $find_items["reply_flg"], true)) {
            echo "返信済みがchecked\n";
            $reply_flg += 2;
        }
        // var_dump($reply_flg);
        // 0と3はWHERE不要なので除外
        if (1 === $reply_flg) {
            $where[] = "reply_at is null";
        }
        if (2 === $reply_flg) {
            $where[] = "reply_at is not null";
        }

        // WHEREの合成
        if ([] !== $where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        // count(*)の取得
        // プリペアードステートメントの作成
        $sql_count = "SELECT count(*) as cnt " . $sql . ";";
        $pre = $dbh->prepare($sql_count);
        // 値のバインド
        foreach ($binds as $k => $v) {
            // var_dump($k, $v);
            $pre->bindValue($k, $v);
        }
        // SQLの実行
        $r = $pre->execute();
        $count = $pre->fetch(\PDO::FETCH_ASSOC)["cnt"];
        // var_dump($count);
        // exit;

        // sortの確定
        // (第一種)ホワイトリストを使う
        $sort_white_list = [
            "created_at" => "created_at",
            "created_at_desc" => "created_at DESC",
            "name" => "name",
            "name_desc" => "name DESC",
        ];
        $sort_e = $sort_white_list[$sort] ?? "inquiry_id DESC";
        //
        $sql_list = "SELECT * " . $sql . " ORDER BY {$sort_e} LIMIT 20 OFFSET :offset;";
        $pre = $dbh->prepare($sql_list);
        // var_dump($sql_list);

        // 値のバインド
        foreach ($binds as $k => $v) {
            // var_dump($k, $v);
            $pre->bindValue($k, $v);
        }
        // offsetのバインド
        $pre->bindValue(":offset", 20 * $page);

        // SQLの実行
        $r = $pre->execute();
        // xxx エラーチェック

        //
        $ret = [
            "data" => $pre->fetchAll(\PDO::FETCH_ASSOC),
            "count" => $count,
        ];

        return $ret;
    }
}
