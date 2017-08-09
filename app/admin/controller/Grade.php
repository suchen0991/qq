<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2017/8/2
 * Time: 上午9:23
 */

namespace app\admin\controller;
use houdunwang\core\Controller;
use houdunwang\view\View;
use system\model\Grade as GradeModel;

class Grade extends Controller{
    /**
     * 班级列表
     * @return mixed
     */
public function lists(){
    $data=GradeModel::get();
    return View::make()->with(compact('data'));
}

    /**
     * 添加
     * @return $this
     */
public function store(){
    if (IS_POST){
        GradeModel::save($_POST);
        return $this->setRedirect('?s=admin/grade/lists')->success('添加成功');
    }
    return View::make();
}

    /**
     * 编辑
     * @return $this
     */
public function update(){
  $gid=$_GET['gid'];
  if (IS_POST){
      GradeModel::save($_POST);
      return $this->setRedirect('?s=admin/grade/lists')->success('编辑成功');

  }
  $oldData=GradeModel::find($gid);
  return View::make()->with(compact('oldData'));
}

    /**
     * 删除
     * @return $this
     */
public function remove(){
    GradeModel::where("gid={$_GET['gid']}")->destory();
    return $this->setRedirect('?=admin/grade/lists')->success('删除成功');

}



}