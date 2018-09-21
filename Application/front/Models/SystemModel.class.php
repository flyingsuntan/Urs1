<?php
class SystemModel extends BaseModel{
    /************发表系统消息*************/
    public function add($data){
        $title = $data['title'];
        $content = $data['content'];
        $sendtime = time();
        $sql = "insert into urs_system_message (title,content,sendtime) value ('$title','$content','$sendtime')";
        $res = $this->_dao->exec($sql);
        if($res){
            return array('valid'=>1,'msg'=>'发表系统消息成功');
        }else{
            return array('valid'=>0,'msg'=>'发表系统消息失败');
        }

    }
    /********************获取系统消息*********************/
    public function lst($id){
        //查询已读取信息的id
        $sql = "select * from urs_user_system_message where uid='$id'";
        $res = $this->_dao->GetRows($sql);
        $rids= array('mr'=>0);
        foreach ($res as $k => $v){
            $rids[] = $v['mid'];
        }
        $rids = implode(',',$rids);
        //查询已读信息
        $sql1 = "select * from urs_system_message where id in ($rids) order by sendtime desc";
        $res1 = $this->_dao->GetRows($sql1);

        //查询未读信息
        $sql2 = "select * from urs_system_message where id not in ($rids) order by sendtime desc";
        $res2 = $this->_dao->GetRows($sql2);
        $data['is_read'] = $res1;
        $data['no_read'] = $res2;
        return $data;
    }
    /****************获取指定ID的系统信息*******************/
    public function detailed($id,$uid){
        //将此信息标记为该用户已读
        $sql1 = "insert into urs_user_system_message (uid,mid) value ('$uid','$id')";
        $this->_dao->exec($sql1);
        //将此信息的点击量加1
        $sql2 = "update urs_system_message set clicks=clicks+1 where id='$id'";
        $this->_dao->exec($sql2);
        //获取指定ID的信息
        $sql = "select * from urs_system_message where id='$id'";
        $res = $this->_dao->GetOneRow($sql);
        return $res;
    }
}