<?php
class IndexController extends CommonController{
    public function indexAction(){
        //获取用户的站内信
        $id = $_SESSION['user_id'];
        $Usermodel = ModelFactory::M('UserModel');
        $messages = $Usermodel->Receive_message($id);
        //获取系统消息
        $sysModel = ModelFactory::M('SystemModel');
        $system = $sysModel->lst($id);
        $count1= count($system['no_read']);
        //var_dump($messages);die;
        $count = count($messages['read_no']);
        require VIEW_PATH."index.html";
    }

}