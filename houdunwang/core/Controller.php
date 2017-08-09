<?php

//Controller类所在的空间名
namespace houdunwang\core;

//Controller类所在的空间名
class Controller{
//  url为跳转哪一页的参数，设置一个初始的url的值，当  如：app/home/Entry.php中add方法不传递参数的时候，默认返回上一级
    private $url='window.history.back()';
//  创建一个template属性来接收引用的提示模版
    private $template;
    //设置一个msg的属性，方便各个方法中的调用，用于成功失败中的消息提示
    private $msg;

    /**
     * 跳转
     * @param $url
     * @return $this
     */
//创建一个setRedirect方法用于跳转到指定 的页面
    protected function setRedirect($url){
//       当用户传递了url的值的时候，页面自动跳转
        $this->url = "location.href='{$url}'";
//        1 返回给app/home/controller/Entry.php中的某一方法，如add方法
//     2 Entry类里面的add方法又返回给houdunwang\core\Boot.php,在appRun中用echo输出对象，
//       3 自动触发本页houdnwang\core\Controller.php中的__toString
        return $this;
    }

    /**
     * 成功的时候
     * @param $msg
     * @return $this
     */
//创建一个success方法用来显示操作成功的提示信息
    protected function success($msg){
//         成功提示消息
        $this->msg = $msg;
//      成功了，就跳转到public/view/success.php这个页面，中转页面
        $this->template = './view/success.php';
//      	返回当前的对象，
//      1返回给app/home/controller/Entry.php中的某一方法，如add方法
//     2 Entry类里面的add方法又返回给houdunwang\core\Boot.php,在appRun中用echo输出对象，
//       3 自动触发本页houdunwang\core\Controller.php中的__toString
        return $this;
    }

    /**
     * 跳转失败的时候
     * @param $msg
     * @return $this
     */
//    创建一个error方法完成一些操作错误或者失败的信息提醒
    protected function error($msg){
//        成功提示消息
        $this->msg=$msg;
//        成功了，就跳转到public/view/error.php这个页面，中转页面
        $this->template='./view/error.php';
//       	返回当前的对象，
//      返回给app/home/controller/Entry.php中的某一方法，如add方法
//      Entry类里面的add方法又返回给houdunwang\core\Boot.php,在appRun中用echo输出对象，
//        自动触发本页houdunwang\core\Controller.php中的__toString
        return $this;
    }

//创建一个输出对象是自动触发的方法完成跳转操作
    public function __toString(){
        // TODO: Implement __toString() method.
//           触发__tostring方法时引入跳转页面，完成跳转，如果没有自动跳转就手动跳转到指定页面跳转到指定页面
        include $this->template;
//        因为__tostring方法只能返回一个字符串，所以要返回一个空字符串
        return '';
    }

}