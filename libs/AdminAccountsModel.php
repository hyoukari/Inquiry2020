<?php   // AdminAccountsModel.php

require_once(BASEPATH . "/libs/Model.php");

class AdminAccountsModel extends Model
{
    // 継承先に、以下がある前提
    protected static $table_name = "admin_accounts";
    protected static $pk_name = 'login_id';
}
