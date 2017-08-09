<?php

//boot类所在的空间名
namespace houdunwang\core;

//创建一个boot类来完成框架的初始化和载入模版的操作
class Boot{
    /*
     * 初始化框架
     */
    //    设置一个静态的方法，在public/index.php中，Boot类是静态调用run方法的
    public static function run(){
        self::handleErrir();
//       初始化框架,要在单一入口中开启session，设置时区，定义一个是否post提交的常量
         self::init();
//             执行apprun这个静态方法实现模版载入和调用应用类的操作
        self::appRun();
    }
    private static function handleErrir(){

        $whoops=new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

       $whoops->register();
    }

    //创建一个静态appRun方法来实现模版的载入操作
    protected static function appRun(){
        //      用于地址栏输入?s=home/entry/index   home是默认应用（有可能是admin），entry是类名，index是方法
//      载入主页面

        $s= isset($_GET['s']) ?$_GET['s'] :"admin/entry/index";
//        将$s/进行拆分，因为需要分别用到传递过来的三个名称  如：home  entry  index
        $arr=explode('/',$s);
        //        p($arr);
//        Array
//        (
//            [0] => home
//            [1] => entry
//           [2] => index
//)
////       1、  把应用，如：‘home’ 定义为常量，常量不受命名空间和类的限制
//        2、目的是组合文件路径，如在houdunwang/view/View.php文件里的View类的make方法组合模板路径，需要用到这些常量 如‘home’
//        3、home是默认路径，也有可能是admin后台路径，因此home不可以写死
        define("APP",$arr[0]);
//        houdunwang/view/View.php中组合文件名的时候需要用到文件的名，如：entry，需要用到文件的类名：如home/Entry
        define("CONTROLLER",$arr[1]);
//        ACTION可以做方法名，也可以组合路径中做文件名，在houdunwang/view/View.php文件里需要组合路径名与方法名一样的文件名称如：index.phpex
        define("ACTION",$arr[2]);
//       组合类名，如app\home\controller\Entry ,类名首字母大写，如Entry，一个\可以转义{}，因此这里需要加上两个\\阻止转义
        $className = "\app\\{$arr[0]}\controller\\".ucfirst($arr[1]);
//      调用控制器中的方法，实例化$className的类,调用$arr[2]的方法，也就是用户需要用的方法
//        echo是为了触发houdunwang/view/Base.php中的__toString,当方法中返回对象时候，对象被echo输出，触发__toString,注意：只能是echo输出才能触发
        echo call_user_func_array([new $className,$arr[2]],[]);
//      s=admin/grade/lists
    }



    /**
     * 初始化框架
     */
//  创建一个init方法完成框架的初始化操作
    protected static function init(){
//        开启session
        //session_id() ,获取或者修改目前session的value值，这里是获取的意思，能获取到表示已经开启session了，|| session_start();就不用再执行了，如果获取不到，那么执行右边的开始session
        session_id()||session_start();
//       设置时区,中国东八区设置成PRC
        date_default_timezone_set("PRC");
//         定义是否POST提交的常量
//        当用户点击提交按钮，表单要求传输方式是post方式，此时REQUEST_METHOD走的是POST方式
//        常量不受命名空间、类的限制
        define('IS_POST',$_SERVER['REQUEST_METHOD']=='POST' ? true :false);
    }
}