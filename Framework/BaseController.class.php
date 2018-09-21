<?php
class BaseController{
    function __construct()
    {
        header("content-type:text/html;charset=utf-8");
        session_start();
    }
    //显示一定的提示文字，然后自动跳转（可以设置停留的时间秒数）
    function GotoUrl($msg,$url,$time){
        echo "$msg";
        echo "<br /><a href='?'>返回</a>";
        echo "<br />页面将在{$time}秒之后自动跳转。";
        header("refresh:$time;url=$url");//自动定时跳转功能
    }
    public function I($data){
        $res = htmlspecialchars($data);
    }

    //引入工具类模型方法
    public function libaray($lib){
        include LIB_PATH . "{$lib}.class.php";
    }
    //引入辅助函数方法
    public function heloer ($helper){
        include HELPER_PATH . "{$helper}.php";
    }

}