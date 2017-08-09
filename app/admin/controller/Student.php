<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2017/8/2
 * Time: 上午9:42
 */

namespace app\admin\controller;

use houdunwang\core\Controller;

use houdunwang\view\View;
use system\model\Grade;
use system\model\Material;
use system\model\Stu;


class Student extends Controller{
    /**
     * 显示学生
     * @return mixed
     *
public function lists(){
    $data =Model::q("SELECT *FROM stu s JOIN grade g ON s.gid=g.gid");
    return View::make()->with(compact('data'));
}

    /**
     * 添加学生
     * @return $this
     */
public function  store(){
    if (IS_POST){
        if (isset($_POST)){
            $_POST['hobby']=implode(',',$_POST['hobby']);
        }
        Stu::save($_POST);
        return $this->setRedirect('?s=admin/student/lists')->success('保存成功');

    }
//    获得班级信息
    $gradeData=Grade::get();
//    头像信息
    $materialData=Material::get();
    return View::make()->with(compact('gradeData','materialData'));
}

public function update(){
    $sid=$_GET['sid'];
    if (IS_POST){
        $_POST['hobby']=implode(',',$_POST['hobby']);
       Stu::save($_POST);
        return $this->setRedirect('?s=admin/grade/lists')->success('添加成功');

    }
//    获得就数据
    $oldData=Stu::find($sid);
    $oldData['hobby'] = explode(',',$oldData['hobby']);

    $gradeData = Grade::get();

    $materialData = Material::get();

    return View::make()->with(compact('oldData','gradeData','materialData'));
}


/**
 * 删除
 */

public function remove(){
   Stu::where("sid={$_GET['sid']}")->destory();
    return $this->setRedirect('?=admin/grade/lists')->success('删除成功');

}


}