<?php
class SystemController extends CommonController{
    /********************发送系统消息***********************/
    public function addACtion(){
        if($_POST){
            $data = $_POST;
            //载入辅助工具
            $this->heloer('input');
            $data = deepspecialchars($data);
            $data =deepaddslashes($data);
            $model = ModelFactory::M('SystemModel');
            $res = $model->add($data);
            $msg = $res['msg'];
            $this->GotoUrl($msg,'?p=front&c=Index&a=index',3);exit;
        }

        //加载发送信息页面
        require VIEW_PATH."addsysmessage.html";
    }
    /**************获取系统消息*******************/
    public function lstAction(){
        $id = $_SESSION['user_id'];
        $model = ModelFactory::M('SystemModel');
        $res = $model->lst($id);

        require VIEW_PATH."sysmessagelst.html";
    }
    /***********获取指定ID的系统信息*******************/
    public function detailedAction(){
        $id = $_GET['id'];
        $uid = $_SESSION['user_id'];
        $model = ModelFactory::M('SystemModel');
        $data = $model->detailed($id,$uid);

        require VIEW_PATH."detailed.html";
    }
}