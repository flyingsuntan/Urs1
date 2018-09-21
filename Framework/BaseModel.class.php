<?php
require 'MySQLDB.class.php';
class BaseModel{
    //这个，用于存储数据库工具的实例（对象）
    protected $_dao = null;
    function __construct()
    {
        $config = array(
            'db_host' => '127.0.0.1',
            'db_port' => '3306',
            'db_user' =>'root',
            'db_pwd' => 'root',
            'bm' => 'utf8',
            'databas' => 'urs'
        );
        $this->_dao = MySQLDB::GetInstance($config);
    }
}