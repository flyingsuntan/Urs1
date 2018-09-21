<?php
class FriendsModel extends BaseModel{
    public function lst($mid){

        //echo $mid;
        $sql = "select a.*,b.user_name from urs_friends a left join urs_user b on a.fid=b.id where mid='$mid'and is_allow='yes' and is_delete='no' and defriend='no'";
        $data = $this->_dao->GetRows($sql);
        $sql1 = "select a.*,b.user_name from urs_friends a left join urs_user b on a.fid=b.id where mid='$mid'and is_allow='yes' and is_delete='no' and defriend='yes'";
        $data1 = $this->_dao->GetRows($sql1);
        $res = array();
        $res['defriend_no'] = $data;
        $res['defriend_yes'] = $data1;
        //var_dump($data);
        return $res;
    }
    /**********************添加好友***********************/
    public function add($data){
        $fname = $data['fname'];
        $sql = "select * from urs_user where user_name='$fname'";
        $res = $this->_dao->GetOneRow($sql);
        if(!$res){
            return array('valid'=>0,'msg'=>'用户不存在');exit;
        }
        $fid= $res['id'];
        $mid = $data['mid'];
        $sql1 = "select * from urs_friends where mid='$mid' and fid='$fid' ";
        $res1 = $this->_dao->GetOneRow($sql1);
        if($res1){
            return array('valid'=>0,'msg'=>'该用户已是您的好友！');exit;
        }
        if($fid == $mid){
            return array('valid'=>0,'msg'=>'不允许添加自己为好友！');exit;
        }
        $sql2 = "insert into urs_friends (mid,fid) value ('$mid','$fid')";
        $res2 = $this->_dao->exec($sql2);
        if($res2){
            return array('valid'=>1,'msg'=>'已发送好友添加请求！');
        }else{
            return array('valid'=>0,'msg'=>'添加失败！');
        }
    }
    /***************获取好友添加信息*****************/
    public function addlst($id){
        $sql = "select a.*,b.user_name ,b.id qid from urs_friends a left join urs_user b on a.mid=b.id where fid=$id and is_allow='no' and is_show='yes'";
        $res = $this->_dao->GetRows($sql);
        //foreach($res as $k => $v){
        //}
       return $res;
    }
    /****************同意添加好友******************/
    public function agree($id,$qid,$mid){
        $sql = "update urs_friends set is_allow='yes' where id=$id";
        $sql1 = "insert into urs_friends (mid,fid,is_allow) value ('$mid','$qid','yes')";
        $res1 = $this->_dao->exec($sql1);
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'同意添加好友成功！');
        }else{
            return array('valid'=>0,'msg'=>'同意添加好友失败！');
        }
    }
    /******************拒绝好友添加*************************/
    public function refuse($id){
        $sql = "updata urs_friends set is_show='no' where id='$id'";
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'拒绝添加好友成功！');
        }else{
            return array('valid'=>0,'msg'=>'拒绝添加好友失败！');
        }
    }
    /***********删除好友**************/
    public function del($fname,$mid){
        $sql = "select id from urs_user where user_name='$fname'";
        $fid = $this->_dao->GetOneRow($sql);
        $fid = $fid['id'];
        $sql1 = " update urs_friends set is_delete='yes',is_allow='no',is_show='no' where  mid='$mid' and fid='$fid'";
        $res = $this->_dao->exec($sql1);
        if($res){
            return array('valid'=>1,'msg'=>'好友删除成功！');
        }else{
            return array('valid'=>0,'msg'=>'好友删除失败！');
        }
    }
    /*****************拉黑好友********************/
    public function defriend($fname,$mid){
        $sql = "select id from urs_user where user_name='$fname'";
        $fid = $this->_dao->GetOneRow($sql);
        $fid = $fid['id'];
        $sql1 = " update urs_friends set defriend='yes' where  mid='$mid' and fid='$fid'";
        $res = $this->_dao->exec($sql1);
        if($res){
            return array('valid'=>1,'msg'=>'好友拉黑成功！');
        }else{
            return array('valid'=>0,'msg'=>'好友拉黑失败！');
        }
    }
    /***********************取消好友拉黑*************************/
    public function nodefriend($fname,$mid){
        $sql = "select id from urs_user where user_name='$fname'";
        $fid = $this->_dao->GetOneRow($sql);
        $fid = $fid['id'];
        $sql1 = " update urs_friends set defriend='no' where  mid='$mid' and fid='$fid'";
        $res = $this->_dao->exec($sql1);
        if($res){
            return array('valid'=>1,'msg'=>'取消好友拉黑成功！');
        }else{
            return array('valid'=>0,'msg'=>'取消好友拉黑失败！');
        }
    }
}