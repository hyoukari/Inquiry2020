<?php   // ModelTest.php
//
require_once("init.php");
require_once(BASEPATH . "/libs/TestModel.php");

// $model = new TestModel();
// $model->i = 10;
// $model->s = "string";
// var_dump($model);

// $r = $model->insert();
// var_dump($r);

$mobj = TestModel::find(1);
var_dump($mobj);

// $mobj = TestModel::find(999);
// var_dump($mobj);

// UPDATE
// $mobj->test_id = 99;
$mobj->i = $mobj->i + 10;
$mobj->update();
var_dump($mobj);
