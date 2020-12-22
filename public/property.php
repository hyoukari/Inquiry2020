<?php   // property

class Hoge
{
    //
    public function __set($name, $value)
    {
        //
        $this->data[$name] = $value;
    }

    public $pub_i;
    private $pri_i;
    private $data = [];
}

//
$obj = new Hoge();
$obj->pub_i = 10;
// $obj->pri_i = 99;
$obj->i = 777;

$s = "\"--;";
$obj->$s = 10;
var_dump($obj);
