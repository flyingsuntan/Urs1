<?php
class UserController extends CommonController{
    /***************修改个人资料*****************/
    public function mpdAction(){
        $usermodel = ModelFactory::M('UserModel');
        $id = $_GET['id'];
        if($_POST){
            $data1 = $_POST;
            //载入辅助函数
            $this->heloer('input');
            $data1 = deepaddslashes($data1);
            $data1 = deepspecialchars($data1);
            $adminmodel = ModelFactory::M('AdminModel');
            $data2 = $usermodel->getRowById($id);
            if(!($adminmodel->isEmail($data1['user_name']))) {
                $this->GotoUrl('用户名必须是邮箱','?p=front&c=User&a=mpd&id=$id&id='.$id,3);exit;
            }
            if($data2['user_name'] != $data1['user_name']) {
                if (!($adminmodel->chkuser($data1['user_name']))) {
                    $this->GotoUrl('用户名已存在', '?p=front&c=User&a=mpd&&id=' . $id, 3);
                    exit;
                }
            }
            if($data1['password']) {
                if (!($adminmodel->chkpassword($data1['password']))) {
                    $this->GotoUrl('密码必须由大小写和数字组成', '?p=front&c=User&a=mpd&id=' . $id, 3);
                    exit;
                }
            }
            $res = $usermodel->mpd($data1, $id);
            if($res){
                session_destroy();
                $this->GotoUrl('更改成功','?p=front&c=Login&a=login',3);exit;
            }else{
                $this->GotoUrl('更改失败', '?p=front&c=User&a=mpd&id='.$id, 3);exit;
            }
        }
        //加载原有的用户信息
        $usermodel = ModelFactory::M('UserModel');
        $data = $usermodel->getRowById($id);
        require VIEW_PATH."user.html";
    }
    /***********修改用户头像****************/
    public function picAction(){

        $model = ModelFactory::M('UserModel');
        if($_POST) {
            $data = $_POST;
            //载入辅助函数
            $this->heloer('input');
            $data = deepspecialchars($data);
            $data =deepaddslashes($data);
            $id = $data['id'];
            if ($data['user_pic_url']) {
                unlink(UPLOAD_PATH . $data['user_pic_url']);
            }
            //载入上传辅助工具类
            $this->libaray('Upload');
            $upload = new upload;
            if ($file_name = $upload->up($data['user_pic'])) {
                $res = $model->pic($file_name, $id);
                if ($res) {
                    $this->GotoUrl('更改成功', '?p=front&c=User&a=mpd&id='.$id, 3);
                    exit;
                } else {
                    $this->GotoUrl('更改失败', '?p=front&c=User&a=mpd&id=' . $id, 3);
                    exit;
                }
            } else {
                echo '上传失败，错误信息为：', $upload->error();

            }
        }
        $id = $_GET['id'];
        $model = ModelFactory::M('UserModel');
        $data = $model->getRowById($id);
        require VIEW_PATH."user_pic.html";
    }
    /****************发送站内信**********************/
    public function sendmessageAction(){
        if($_POST){
            //var_dump($_POST);die;
            $data = $_POST;
            $this->heloer('input');
            $data = deepspecialchars($data);
            $data =deepaddslashes($data);
            $mid = $_SESSION['user_id'];
            $data['mid'] = $mid;
            $model = ModelFactory::M('UserModel');
            $res = $model->sendmessage($data);
            if($res['valid']==0){
                $msg = $res["msg"];
                $this->GotoUrl($msg,'?p=front&c=User&a=sendmessage',3);exit;
            }else{
                $msg = $res["msg"];
                $this->GotoUrl($msg,'?p=front&c=User&a=sendmessage',3);exit;
            }
        }
        require VIEW_PATH."sendmessage.html";
    }
    /***************获取站内信********************/
    public function Receive_messageAction(){
        $id = $_SESSION['user_id'];
        $model = ModelFactory::M('UserModel');
        $res = $model->Receive_message($id);
        $res_read_no = $res['read_no'];
        $res_read_yes = $res['read_yes'];
        //载入站内信页面
        require VIEW_PATH."Receive_message.html";
    }
    /*************读取指定信息**************/
    public function messages_readAction(){
        $id = $_GET['id'];
        $model = ModelFactory::M('UserModel');
        $res = $model->messages_read($id);
        $res1 = $res['z'];
        unset($res['z']);
        $res = array_values($res);
        //var_dump($res);die;
    //载入站内信详细页面
        require VIEW_PATH."messages_read.html";

    }
    /**********回复信息***************/
    public function reply_messageAction(){
        $model = ModelFactory::M('UserModel');
        //var_dump($_POST);die;
        $data = $_POST;
        $this->heloer('input');
        $data = deepspecialchars($data);
        $data = deepaddslashes($data);
        $mid = $_SESSION['user_id'];
        $data['mid'] = $mid;
        $res = $model->reply_message($data);
        if($res['valid']==0){
            $msg = $res['msg'];
            $this->GotoUrl($msg,'?p=front&c=Index&a=index',3);exit;
        }else{
            $msg = $res['msg'];
            $this->GotoUrl($msg,'?p=front&c=Index&a=index',3);exit;
        }
    }
    /***************好友发送信息*****************/
    public function friendsendmessageAction(){

        if($_POST) {
            $data = $_POST;
            $this->heloer('input');
            $data = deepspecialchars($data);
            $data = deepaddslashes($data);
            $mid = $_SESSION['user_id'];
            $data['mid'] = $mid;
            //$fname = $data['f_name'];
            $model = ModelFactory::M('UserModel');
            $res = $model->friendsendmessage($data);
            if($res['valid']==0){
                $msg = $res["msg"];
                $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
            }else{
                $msg = $res["msg"];
                $this->GotoUrl($msg,'?p=front&c=Friends&a=lst',3);exit;
            }
        }
        $fname = $_GET['fname'];
        require VIEW_PATH."friendsendmessage.html";
    }
}







