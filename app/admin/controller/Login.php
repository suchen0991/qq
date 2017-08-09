<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2017/8/3
 * Time: 上午8:39
 */

namespace app\admin\Controller;
use houdunwang\core\Controller;
use houdunwang\view\View;
use Gregwar\Captcha\CaptchaBuilder;
use system\model\User;

class Login extends Controller
{
    /**
     * 登陆页面
     */
    public function index()
    {
//    预先存入数据库用户名和密码
        $password=password_hash('admin888',PASSWORD_DEFAULT);
    echo $password;
        if (IS_POST) {
            $post = $_POST;
            if (strtolower($post['captcha']) != $_SESSION['captcha']) {
                return $this->error('验证码错误');
            }
//        用户名不存在
            $data = User::where("username='{$post['username']}'")->get();
            if (!$data) {
                return $this->error('用户名不存在');
            }
//        密码错误
            if (!password_verify($post['password'], $data[0]['password'])) {
                return $this->error('密码错误');
            }
//        选择是否勾选7天免登陆
            if (isset($post['auto'])) {
                setcookie(session_name(), session_id(), time() + 7 * 24 * 3600, '/');
            } else {
                setcookie(session_name(), session_id(), 0, '/');

            }
            $_SESSION['user'] = [
                'uid' => $data[0]['uid'],
                'username' => $data['username'],
            ];
            return $this->setRedirect('?s=admin/entry/index')->success('登陆成功');

        }
        return View::make();

    }

    /**
     * 验证码
     */
    public function captcha()
    {
        $str = substr(md5(microtime(true)), 0, 3);
        $captcha = new CaptchaBuilder($str);
        $captcha->build();
        header('Content-type:image/jpeg');
        $captcha->output();

        $_SESSION['captcha'] = strtolower($captcha->getPhrase());


    }

    public function out(){
        session_unset();
        session_destroy();
        return $this->setRedirect('?s=admin/login/index')->success('退出成功');
    }
}