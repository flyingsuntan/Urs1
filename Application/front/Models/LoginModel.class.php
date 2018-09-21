<?php
class LoginModel extends BaseModel{
    public function login($data){
        $user_name = $data['user_name'];
        $password = md5($data['password']);
        $sql = "select * from urs_user where user_name='$user_name'and password='$password'";
        $res = $this->_dao->GetOneRow($sql);
        if($res){
            $_SESSION['user_id']=$res['id'];
            $_SESSION['user_name']=$res['user_name'];
            $_SESSION['user_pic']=$res['user_pic'];
            return true;
        }else{
            return false;
        }
    }
}