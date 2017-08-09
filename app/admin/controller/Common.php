<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2017/8/3
 * Time: 上午9:31
 */

namespace app\admin\controller;
use houdunwang\core\Controller;

class Common extends Controller{

    public function  __construct()
    {
        if (!isset($_SESSION['user'])){
            go('?s=admin/login/index');
        }
    }

}