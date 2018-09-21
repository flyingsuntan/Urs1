<?php
class LoginController extends BaseController{
    public function LoginAction(){
        if($_POST){
            $data = $_POST;
            //载入辅助函数
            $this->heloer('input');
            //过滤xss和sql注入
            $data = deepspecialchars($data);
            $data = deepaddslashes($data);
            if($data['user_name']){
                $AdminModel = ModelFactory::M('AdminModel');
                $isEmail = $AdminModel ->isEmail($data['user_name']);
                if($isEmail) {
                    if ($data['password']) {
                        if ($data['captcha']) {
                            if ($_SESSION['captcha'] == $data['captcha']) {
                                $model = ModelFactory::M('LoginModel');
                                if ($model->login($data)) {
                                    $this->GotoUrl('登录成功', '?p=front&c=Index&a=Index', '3');
                                    exit;
                                } else {
                                    $this->GotoUrl('用户名或密码错误', '?p=front&c=Login&a=login', '3');
                                    exit;
                                }
                            } else {
                                $this->GotoUrl('验证码错误', '?p=front&c=Login&a=login', '3');
                                exit;
                            }
                        } else {
                            $this->GotoUrl('请填写验证码', '?p=front&c=Login&a=login', '3');
                            exit;
                        }
                    } else {
                        $this->GotoUrl('请填写密码', '?p=front&c=Login&a=login', '3');
                        exit;
                    }
                }else{
                    $this->GotoUrl('用户名必须是邮箱！', '?p=front&c=Login&a=login', '3');
                    exit;
                }

            }else{
                $this->GotoUrl('请填写用户名', '?p=front&c=Login&a=login', '3');exit;
            }


        }


        include VIEW_PATH."login.html";
    }
    /***************退出登录********************/
    public function logoutAction(){
        session_destroy();
        $this->GotoUrl('退出成功', '?p=front&c=Login&a=login', '0');exit;
    }
    /***************生成验证码**********************/
    public function captchaAction(){
        //引入验证码类
        $this->libaray('Captcha');
        //实例化对象
        $captcha = new Captcha();
        //生成验证码
        $captcha->generateCode();
        //将验证码保存到session中
        $_SESSION['captcha'] = $captcha->getCode();
    }
}
