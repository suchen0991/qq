<?php

//view类所在的空间名
namespace houdunwang\view;


/**
 * 定义一个View类用来自动调用本空间名里面的Base类里面的方法
 * @param $name [方法名称]
 * @param $arguments [方法传入的参数]
 */

class View{
//创建一个静态调用不存在的方法时执行的方法执行Base类中对应的方法
      public static function __callStatic($name, $arguments){
//          将当前空前中base类对应方法返回的对象值返回到entry类中的index方法中，并返回到houdunwang/core/boot类中的
//          APPRUN方法中echo出来触发当前空前的__tostring完成载入模版和对应数据的操作
          return call_user_func_array([new Base(),$name],$arguments);
      }
}