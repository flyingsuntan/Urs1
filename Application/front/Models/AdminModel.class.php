<?php
class AdminModel extends BaseModel{
    /**********用户注册***************/
    public function register($data){
        $user_name = $data['user_name'];
        $password = md5($data['user_password']);
        $sql = "insert into urs_user (user_name,password)value ('$user_name','$password')";
        $res = $this->_dao->exec($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
    //判断用户名是否存在
    public  function chkuser($user_name){
        $sql = "select * from urs_user where user_name='$user_name'";
        $res = $this->_dao->GetOneRow($sql);
        if(!$res){
            return true;
        }else{
            return false;
        }
    }
    //判断是否是正确的邮箱格式;
    public function isEmail($email){
        $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        if(preg_match($mode,$email)){
            return true;
        }
        else{
            return false;
        }
    }
    /**************判断密码必须由大小写和数字组成******************/
    public function chkpassword($user_password){
        if(preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9]/', $user_password)){
            return true;
        }else{
            return false;
        }
    }
}