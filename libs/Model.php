<?php   // Model.php

class Model
{
    /*
    // 継承先に、以下がある前提
    protected static $table_name = '';
    protected static $pk_name = '';


    $flight = new Flight;
    $flight->name = $request->name;
    $flight->pk = $hoge;
    $flight->insert();
    */


    /**
     * data丸ごと取得
     * 主に「テンプレート等にデータを渡す」ために使用
     */
    public function get()
    {
        return $this->data;
    }

    //
    public function __set($name, $value)
    {
        // xxx pkなら不許可
        if ((true === $this->pk_deter_flg) && ($name === static::$pk_name)) {
            throw new \Exception("主キーは変更できません");
        }
        $this->data[$name] = $value;
    }
    public function __get($name)
    {
        // return $this->data[$name] ?? null;
        if (false === array_key_exists($name, $this->data)) {
            throw new \Exception("{$name}は存在しないカラムです");
        }
        return $this->data[$name];
    }

    // 識別子のエスケープ
    public static function escape($value)
    {
        // もし引数が配列なら、個々に分解して再帰でcallする
        if (true === is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = static::escape($v);
            }
            return $value;
        }
        // else
        // 英数アンダースコア 以外は全部NG
        $len = strlen($value);
        for ($i = 0; $i < $len; ++$i) {
            //
            if (true === ctype_alnum($value[$i])) {
                continue;
            }
            if ("_" === $value[$i]) {
                continue;
            }
            //else
            throw new \Exception("識別子 {$value} には、使用不可な文字が含まれています。");
        }
        //
        return $value;
    }

    //
    public function insert()
    {
        //
        $dbh = DbHandle::get();

        // プリペアードステートメントを作成
        // + 識別子のエスケープ処理
        $table_name = $this::escape($this::$table_name);
        $cols = $this::escape(array_keys($this->data));
        // var_dump($table_name, $cols);

        //
        $sql_cols = implode(", ", $cols);
        $holder = [];
        foreach ($cols as $s) {
            $holder[] = ":{$s}";
        }
        $sql_holder = implode(", ", $holder);

        //
        $sql = "INSERT INTO {$table_name}({$sql_cols}) VALUES({$sql_holder});";
        $pre = $dbh->prepare($sql);
        // var_dump($pre);

        // プレースホルダに値をバインド
        foreach ($this->data as $k => $v) {
            static::bind($pre, $k, $v);
        }

        // SQLを実行
        $r = $pre->execute();
        // var_dump($r);

        // pk更新の抑止
        if (true === $r) {
            $this->pk_deter_flg = true;
        }

        return $r;
    }

    //
    public static function find($value)
    {
        //
        $dbh = DbHandle::get();

        // プリペアードステートメントの作成
        $table_name = static::escape(static::$table_name);
        $pk_name = static::escape(static::$pk_name);
        $sql = "SELECT * FROM {$table_name} WHERE {$pk_name}=:{$pk_name}";
        $pre = $dbh->prepare($sql);

        // 値のバインド
        static::bind($pre, $pk_name, $value);

        // SQLの実行
        $r = $pre->execute();
        // var_dump($r);

        // データの取得
        $data = $pre->fetch(\PDO::FETCH_ASSOC);
        // var_dump($data);
        if (false === $data) {
            return null;
        }

        // インスタンス作ってデータ入れてreturn
        $robj = new static();
        $robj->data = $data;

        // pk更新の抑止
        $robj->pk_deter_flg = true;

        //
        return $robj;
    }

    // UPDATE
    public function update()
    {
        // 更新すべきデータを一通り取得
        // xxx 「変更のないデータ」も一端update。チューニング対象
        $data = $this->data;
        unset($data[static::$pk_name]);
        var_dump($this->data);

        // pkの情報を取得
        $pk = $this->{static::$pk_name};
        var_dump(static::$pk_name, $pk);

        //
        $dbh = DbHandle::get();

        // SQLの組み立て
        $table_name = $this->escape($this::$table_name);
        $cols = $this::escape(array_keys($data));
        $pk_col = $this::escape(static::$pk_name);
        // var_dump($table_name, $cols, $pk_col);
        // exit;

        // xxx
        $set_array = [];
        foreach ($cols as $s) {
            $set_array[] = "{$s}=:{$s}";
        }
        $set_value = implode(", ", $set_array);
        //
        $sql = "UPDATE {$table_name} SET {$set_value} WHERE {$pk_col}=:{$pk_col};";
        $pre = $dbh->prepare($sql);
        // var_dump($sql);

        foreach ($this->data as $k => $v) {
            static::bind($pre, $k, $v);
        }
        // SQL実行
        $r = $pre->execute();
        return $r;
    }

    // bind
    protected static function bind(\PDOStatement $pre, string $k, $v): bool
    {
        //
        if (true === is_null($v)) {
            $type = \PDO::PARAM_NULL;
        } elseif ((true === is_int($v)) || (true === is_float($v))) {
            $type = \PDO::PARAM_INT;
        } else {
            $type = \PDO::PARAM_STR;
        }
        //
        return $pre->bindValue(":{$k}", $v, $type);
    }

    private $data = [];
    private $pk_deter_flg = false;
}
