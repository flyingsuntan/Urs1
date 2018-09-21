<?php
class AdminController extends BaseController{
    public function registerAction()
    {
        if ($_POST) {
            $data = $_POST;
            //载入辅助函数
            $this->heloer('input');
            $data = deepspecialchars($data);
            $data = deepaddslashes($data);
            $user_name = $data['user_name'];
            $user_password = $data['user_password'];
            $model = ModelFactory::M('AdminModel');
            if($user_name) {
                if($user_password) {
                    if ($model->isEmail($user_name)) {
                        if (preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9]/', $user_password)) {
                            if($model->chkuser($user_name)) {
                                if ($model->register($data)) {
                                    $this->GotoUrl('注册成功', '?p=front&c=Login&a=login', '3');
                                    exit;
                                } else {
                                    $this->GotoUrl('注册失败', '?p=front&c=Admin&a=register', '3');
                                    exit;
                                }
                            }else{
                                $this->GotoUrl('用户名已存在', '?p=front&c=Admin&a=register', '3');
                                exit;
                            }

                        } else {
                            $this->GotoUrl('密码必须由大小写字母组成', '?p=front&c=Admin&a=register', '3');
                            exit;
                        }
                    } else {
                        $this->GotoUrl('请用邮箱注册', '?p=front&c=Admin&a=register', '3');
                        exit;
                    }
                }else{
                    $this->GotoUrl('密码不能为空', '?p=front&c=Admin&a=register', '3');
                    exit;
                }
            }else{
                $this->GotoUrl('用户名不能为空', '?p=front&c=Admin&a=register', '3');
                exit;
            }


        }

        include VIEW_PATH . "register.html";


    }



}