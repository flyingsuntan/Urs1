<?php
class CommonController extends BaseController{
    public function __construct()
    {
        parent::__construct();
        $this->chkLogin();

    }
    public function chkLogin(){
        if(!$_SESSION['user_id']){
            $this->GotoUrl('请先登录', '?p=front&c=Login&a=login', '3');exit;
        }
    }
}