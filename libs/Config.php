<?php   // Config.php

class Config
{
    //
    static public function get($name, $default = null)
    {
        // 初めてcallなら
        if (null === static::$data) {
            static::read();
        }
        //
        return static::$data[$name] ?? $default;
    }

    static public function read()
    {
        // 設定ファイルを読み込む
        // xxx 「共通設定」の追加
        // xxx 「環境依存」設定ファイルのシンボリックリンク化
        // xxx get側でcallする
        static::$data = require_once(BASEPATH . "/environmental_dependence.conf");
        // var_dump(static::$data);
    }

    private static $data = null;
}

// test
// Config::read();
// echo Config::get("db_user"), "\n";
