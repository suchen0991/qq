<?php

//Base类所在的空间名
namespace houdunwang\view;

//创建一个Base类，当app/home/controller/entry中的entry类中
//的index方法调用View类时会调用这个类中的方法完成载入前台页面的操作和载入显示的数据
class Base{
//    保存分配的变量属性
    private $data=[];
//    模板路径
    private $template;

    /**
     * 分配变量
     * @param $data
     * @return $this
     */
    public function  with($data){
//       用来储存发过来的数据库内容 确保先返回数据再返回模板
        $this->data=$data;
        //1.返回当前对象，
        //(1)返给\houdunwang\view\View里面的__callStatic
        //(2)View里面的__callStatic再返回给\app\home\controller\Entry里面的index方法(with)
        //(3)Entry里面的index方法又返回给\houdunwang\core\Boot里面的appRun方法，在appRun方法用了echo
//        为了触发__toString
        return $this;
    }

    /**
     * 制作模板方法
     * @return $this
     */
    public function make(){
//        组合一个模板根据 APP  CONTROLLER ACTION 常量传来的不同值 来组合模板
        //      s=admin/grade/lists
//                        ../app/admin/view/grade/lists.php;
        $this->template='../app/' . APP . '/view/' . CONTROLLER . '/'.ACTION . '.php';
        //1.返回当前对象，
        //(1)返给\houdunwang\view\View里面的__callStatic
        //(2)View里面的__callStatic再返回给\app\home\controller\Entry里面的index方法(View::make())
        //(3)Entry里面的index方法又返回给\houdunwang\core\Boot里面的appRun方法，在appRun方法用了echo 输出这个对象
        //2.为了触发__toString
        return $this;
    }

//创建一个输出对象是自动触发的方法完成先载入数据再载入模版的操作，让所对应的数据能在对应的模版中调用
    public function __toString(){
        //把键名变为变量名，键值变为变量值
        extract($this->data);
        //载入模板
        include $this->template;
        //这个方法必须返回字符串
        return '';
    }


}