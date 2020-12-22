<?php   // TestModel.php

require_once(BASEPATH . "/libs/Model.php");

class TestModel extends Model
{
    // 継承先に、以下がある前提
    protected static $table_name = "test";
    protected static $pk_name = "test_id";
}
