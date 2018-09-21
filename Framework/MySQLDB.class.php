<?php
//header("content-type:text/html; charset=utf-8");
class MySQLDB{
    private $link = null;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pwd;
    private $bm;
    private $database;
    //实现单例第2步：用于存储唯一的单例对象
    private static $instance = null;
    //实现单例第3步：
    static function GetInstance($config){
        //if(!isset(self::$instance)){
        if(!(self::$instance instanceof self)){
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    //实现单例第4步：私有化克隆的魔术方法
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    //实现单例第1步：
    private function __construct($config)
    {
        //先将基本的连接信息保存起来！
        $this->db_host = !empty($config['db_host']) ? $config['db_host'] : "127.0.0.1";//考虑空值情况，使用默认值代替
        $this->db_port = !empty($config['db_port']) ? $config['db_port'] : "3306";
        $this->db_user = !empty($config['db_user']) ? $config['db_user'] : "root";
        $this->db_pwd = !empty($config['db_pwd']) ? $config['db_pwd'] : "root";
        $this->bm = !empty($config['bm']) ? $config['bm'] : "utf8";
        $this->database = !empty($config['databas']) ? $config['databas'] : "urc";
        //然后连接数据库
        $this->link = mysql_connect($this->db_host . ":" . $this->db_port,$this->db_user,$this->db_pwd) or die  ("连接失败");
        //设定编码
        //$set_bm = mysql_query("set names {$config['bm']}");
        $this->Bm($this->bm);
        //选定要使用的数据库名
        $this->DataBase($this->database);
    }
    //更改数据库编码
    function Bm($bm){
        $set_bm1 = mysql_query("set names $bm");
    }
    //更改数据库
    function DataBase($database){
        $set_cha1 = mysql_query("use $database");
    }
    //关闭连接数据库
    function closeDB()
    {
        $close  = mysql_close($this->link);
    }
    //这个方法为了执行一条增删改语句，他可以返回真假结果
    function exec($sql){
        $result = $this->query($sql);
        return true;
    }
    //这个方法为了执行一条返回一行数据的语句，它可以返回一维数组
    //数组的下标，就是sql语句中的取出的字段名；
    function GetOneRow($sql){
        $result = $this->query($sql);
        //这里开始处理数据，以返回数组。此时$result时一个结果集
        $rec = mysql_fetch_assoc($result);
        mysql_free_result($result);//提前释放资源（销毁结果集），否则需要等到页面结束才能释放
        return $rec;
    }
    //个方法为了执行一条返回多行数据的语句，它可以返回二维数组
    function GetRows($sql){
        $result = $this->query($sql);
        //这里开始处理数据，以返回数组。此时$result时一个结果集（且时多行数据）
        $arr = array();//空数组，用于存放要返回的结果数组（二维）
        while($rec = mysql_fetch_assoc($result)){
            $arr[] = $rec;//此时，$arr就是二维数组了
        }
        return $arr;
    }
    //个方法为了执行一条返回一个数据的语句，它可以返回一个直接值
    function GetOneData($sql){
        $result = $this->query($sql);
        //这里开始处理数据，以返回一个数据！
        $rec = mysql_fetch_row($result);
        $data = $rec[0];
        return $data;
    }
    //这个方法用于执行任何sql语句，并进行错误处理，或返回执行结果
    private function query($sql){
        $result = mysql_query($sql);
        if($result ===false){
            //语句执行失败，则需要处理这种失败情况：
            echo "<p>sql语句执行失败，请参考如下信息：";
            echo "<br />错误代号" . mysql_errno();//获取错误代号
            echo "<br />错误信息" . mysql_error();//获取错误提示内部
            echo "<br />错误语句" . $sql;
            die();
        }
        return $result;//返回的是“执行的结果”
    }
}
