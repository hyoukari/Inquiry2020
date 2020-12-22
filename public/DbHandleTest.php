<?php   // DbHandleTest.php
//
require_once("./init.php");

class DbHandle
{
    static public function get(): \PDO
    {
        static $dbh = null;
        if (null === $dbh) {
            $user = Config::get("db_user");
            $pass = Config::get("db_pass");
            $host = Config::get("db_host");
            $dbname = Config::get("db_dbname");
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            //
            try {
                $dbh = new \PDO($dsn, $user, $pass, $option = null);
            } catch (\PDOException $e) {
                // xxx
                echo $e->getMessage();
                exit;
            }
        }
        return $dbh;
    }
}
$dbh = DbHandle::get();
var_dump($dbh);
