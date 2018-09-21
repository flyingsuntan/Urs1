<?php
class UserModel extends BaseModel{
    /*********通过ID查询用户信息************/
    public function getRowById($id){
        $sql = "select * from urs_user where id=$id";
        $res = $this->_dao->GetOneRow($sql);
        return $res;
    }
    /**************修改用户信息****************/
    public function mpd($data,$id){
        $user_name = $data['user_name'];
        $password = md5($data['password']);
        if($data['password']){
            $sql = "update urs_user set user_name='$user_name',password='$password' where id='$id'";
            $res = $this->_dao->exec($sql);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            $sql = "update urs_user set user_name='$user_name'where id='$id' ";
            $res = $this->_dao->exec($sql);
            if($res){
                return true;
            }else{
                return false;
            }
        }
    }
    /*************修改用户头像*****************/
    public function pic($file_name,$id){
        $sql = "update urs_user set user_pic='$file_name' where id='$id'";
        $res = $this->_dao->exec($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /******************发送站内信息***********************/
    public function sendmessage($data){
        $mid = $data['mid'];
        $f_name = $data['f_name'];
        $title = $data['title'];
        $message = $data['message'];
        $fid = $this->_dao->GetOneRow("select id from urs_user where user_name='$f_name'");
        $fid = $fid['id'];
        if($fid == $mid){
            return array('valid'=>0,'msg'=>'不能给自己发信息');exit;
        }
        $is_have_message = ($this->_dao->GetOneRow("select count(id) from urs_message where mid=$mid and fid=$fid and status='no'"));
        $is_have_message = $is_have_message['count(id)'];
        if($is_have_message != 0){
            return array('valid'=>0,'msg'=>'对方有未读消息，不允许再次发送。');exit;
        }
        $sendtime = time();
        $sql = "insert into urs_message (mid,fid,title,message,sendtime) value ('$mid','$fid','$title','$message','$sendtime')";
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'发送成功。');
        }else{
            return array('valid'=>0,'msg'=>'发送失败。');
        }
    }
    /***********************获取用户站内信***************************/
    public function Receive_message($id){
        $sql = "select a.*,b.user_name from urs_message a left join urs_user b on b.id=a.fid where a.fid='$id'and a.status='no'";
        $sql2 = "select a.*,b.user_name from urs_message a left join urs_user b on b.id=a.fid where a.fid='$id'and a.status='yes'";
        $messages1 = $this->_dao->GetRows($sql);
        $messages2 = $this->_dao->GetRows($sql2);
        $messages['read_no'] = $messages1;
        $messages['read_yes'] = $messages2;
        return $messages;
    }
    /**************获取站内信详细信息*******************/
    public function messages_read($id){
        $res1 = $this->getChildren($id);
        $res2 = $this->getParent($id);
        $ids = array();
        foreach ($res1 as $v){
            $ids[] = $v;
        }
        foreach ($res2 as $v){
            $ids[] = $v;
        }
        $ids[] = $id;
        $ids = implode(',',$ids);
        //将信息状态改为已读
        $readtime = time();
        $sql1 = "update urs_message set readtime='$readtime',status='yes'where id='$id' ";
        $this->_dao->exec($sql1);
        //获取站内信息
        $sql = "select a.*,b.user_name from urs_message a left join urs_user b on a.mid=b.id where a.id in($ids)";
        $res = $this->_dao->GetRows($sql);
        //var_dump($res);
        $sql2 = "select a.*,b.user_name from urs_message a left join urs_user b on a.mid=b.id where a.id='$id'";
        $res1 = $this->_dao->GetOneRow($sql2);
        $res['z'] = $res1;
        return $res;
    }
    /***********获取信息下的回复************/
    public function getChildren($id){
        $sql = "select * from urs_message";
        $data = $this->_dao->GetRows($sql);
        return $this->_getChildren($data,$id);
    }


    /***********获取回复下的子回复****************/
    public function _getChildren($data,$id=0,$isClear = false){
        static $res = array();
        if($isClear){
            $res = array();
        }
        //循环所有的分类找子分类
        foreach ($data as $k => $v){
            if($v['pid'] == $id){
                $res[] = $v['id'];
                //在找这个$v的子分类
                $this->_getChildren($data,$v['id']);
            }
        }
        return $res;
    }
    /*************获取回复的父回复信息*************/
    public function getparent($id){
        $sql = "select * from urs_message";
        $sql1 ="select * from urs_message where id='$id'";
        $data1 = $this->_dao->GetOneRow($sql1);
        $data = $this->_dao->GetRows($sql);
        //var_dump($this->_getChildren($data,$data1['pid']));die;
        return $this->_getParent($data,$data1['pid']);
    }
    /***********获取回复的父回复***************/
    public function _getParent($data,$pid,$isClear = false){
        static $res = array();
        if($isClear){
            $res = array();
        }
        //循环所有的分类找子分类
        foreach ($data as $k => $v){
            if($v['id'] == $pid){
                $res[] = $v['id'];
                $id = $v['id'];
                $sql1 ="select * from urs_message where id='$id'";
                $data1 = $this->_dao->GetOneRow($sql1);
                //在找这个$v的子分类
                $this->_getParent($data,$data1['pid']);
            }
        }
        //var_dump($res);die;
        return $res;
    }
    /*******************回复站内信************************/
    public function reply_message($data){
        $mid = $data['mid'];
        $pid = $data['pid'];
        $f_name = $data['fname'];
        //$message = $data['message'];
        $title = $data['title'];

        $fid = $this->_dao->GetOneRow("select id from urs_user where user_name='$f_name'");
        $fid = $fid['id'];
        $sendtime = time();
        $message = $data['message'];

        $is_have_message = ($this->_dao->GetOneRow("select count(id) from urs_message where mid=$mid and fid=$fid and status='no'"));
        $is_have_message = $is_have_message['count(id)'];
        if($is_have_message != 0){
            return array('valid'=>0,'msg'=>'对方有未读消息，不允许再次发送。');exit;
        }
        $sql = "insert into urs_message (mid,fid,title,message,sendtime,pid) value ('$mid','$fid','$title','$message','$sendtime',$pid)";
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'回复成功！');exit;
        }else{
            return array('valid'=>1,'msg'=>'回复失败！');exit;
        }
    }
    /************************好友发送信息************************************/
    public function friendsendmessage($data){
        $mid = $data['mid'];
        $f_name = $data['f_name'];
        $title = $data['title'];
        $message = $data['message'];
        $fid = $this->_dao->GetOneRow("select id from urs_user where user_name='$f_name'");
        $fid = $fid['id'];
        if($fid == $mid){
            return array('valid'=>0,'msg'=>'不能给自己发信息');exit;
        }
        $defriend = $this->_dao->GetOneRow("select * from urs_friends where mid=$fid and fid=$mid and defriend='yes'");
        if($defriend){
            return array('valid'=>0,'msg'=>'对方已把你拉黑不允许在发送信息。');exit;
        }
        $is_delete = $this->_dao->GetOneRow("select * from urs_friends where mid=$fid and fid=$mid and is_delete='yes'");
        if($is_delete){
            return array('valid'=>0,'msg'=>'对方已把你删除不允许在发送信息。');exit;
        }
        $is_have_message = ($this->_dao->GetOneRow("select count(id) from urs_message where mid=$mid and fid=$fid and status='no'"));
        $is_have_message = $is_have_message['count(id)'];
        if($is_have_message != 0){
            return array('valid'=>0,'msg'=>'对方有未读消息，不允许再次发送。');exit;
        }
        $sendtime = time();
        $sql = "insert into urs_message (mid,fid,title,message,sendtime) value ('$mid','$fid','$title','$message','$sendtime')";
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'发送成功。');
        }else{
            return array('valid'=>0,'msg'=>'发送失败。');
        }
    }
}