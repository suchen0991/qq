<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2017/8/3
 * Time: 上午8:38
 */

namespace app\admin\controller;
use houdunwang\core\Controller;
use houdunwang\view\View;
use system\model\User as UserModel;

class User extends Controller {
public function changePassword(){
    if (IS_POST){
        $post=$_POST;
//        先比对旧密码是否正确
        $user=UserModel::where("uid=".$_SESSION['user']['uid'])->get();
        if (!password_verify($post['oldPassword'],$user[0]['password'])){
            return $this->error('旧密码错误');
        }
//        判断两次密码是否一致
        if ($post['newPassword']!=$post['confirmPassword']){
            return $this->error('两次密码不一致');
        }
//        修改
        $data=['password'=>password_hash($post['newPassword'],PASSWORD_DEFAULT)];
        UserModel::where('uid='.$_SESSION['user']['uid'])->update($data);


//        清除session重新登陆
        session_unset();
        session_destroy();

        return $this->setRedirect('?s=admin/login/index')->success('修改成功');
    }
    return View::make();
}

}