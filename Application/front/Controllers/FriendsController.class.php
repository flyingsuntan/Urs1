<?php
class FriendsController extends CommonController{
    public function lstAction(){
        $model = ModelFactory::M('FriendsModel');
        //获取好友添加信息
        $mid = $_SESSION['user_id'];
        $count = count($model->addlst($mid));
        //获取全部好友信息
        $data = $model->lst($mid);
        //var_dump($data);
        //var_dump($data);die;
        //载入好友列表页
        require VIEW_PATH."friendslst.html";
    }
    /************************添加好友***********************/
    public function addAction(){
        if($_POST){
            //载入辅助函数
            $this->heloer('input');
            $data = $_POST;
            $data = deepspecialchars($data);
            $data = deepaddslashes($data);
            //var_dump($_POST);
            $model = ModelFactory::M('FriendsModel');
            $mid = $_SESSION['user_id'];
            $data['mid'] = $mid;
            $res = $model->add($data);
            if($res['valid']==0){
                $msg = $res['msg'];
                $this->GotoUrl($msg,'?p=front&c=Friends&a=add',3);exit;
            }else{
                $msg = $res['msg'];
                $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
            }
        }

        //载入添加页面
        require VIEW_PATH."friendsadd.html";
    }
    /**************好友添加信息******************/
    public function addlstAction(){
        //获取好友添加信息
        $model = ModelFactory::M('FriendsModel');
        $id = $_SESSION['user_id'];
        $data = $model->addlst($id);
        //var_dump($data);
        //载入好友添加页面
        require VIEW_PATH."friendsaddlst.html";
    }
    /****************同意添加好友*********************/
    public function agreeAction(){
        $id = $_GET['id'];
        $qid = $_GET['qid'];
        $mid = $_SESSION['user_id'];
        $model = ModelFactory::M('FriendsModel');
        $res = $model->agree($id,$qid,$mid);
        $msg = $res['msg'];
        $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;

    }
    /***************拒绝好友添加***************/
    public function refuseAction(){
        $id = $_GET['id'];
        $model = ModelFactory::M('FriendsModel');
        $res = $model->refuse($id);
        $msg = $res['msg'];
        $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
    }
    /*************删除好友*************/
    public function delAction(){
        $fname = $_GET['fname'];
        $mid = $_SESSION['user_id'];
        $model = ModelFactory::M('FriendsModel');
        $res = $model->del($fname,$mid);
        $msg = $res['msg'];
        $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
    }
    /********************拉黑好友****************************/
    public function defriendAction(){
        $fname = $_GET['fname'];
        $mid = $_SESSION['user_id'];
        $model = ModelFactory::M('FriendsModel');
        $res = $model->defriend($fname,$mid);
        $msg = $res['msg'];
        $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
    }
    /************************取消拉黑好友*****************************/
    public function nodefriendAction(){
        $fname = $_GET['fname'];
        $mid = $_SESSION['user_id'];
        $model = ModelFactory::M('FriendsModel');
        $res = $model->nodefriend($fname,$mid);
        $msg = $res['msg'];
        $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
    }
}