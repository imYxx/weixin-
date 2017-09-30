<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络
// +---------------------------------------------------------------------- 

namespace app\admin\controller;

use think\Request;

class Repair extends Admin
{

    /**
     * 报修添加
     */
    public function add()
    {
        if(request()->isPost()){
            $repair = model('repair');
            $post_data = \think\Request::instance()->post();
            $post_data['create_time'] = time();
            $data = $repair->insert($post_data);
            if($data){
                $this->success('新增成功', url('index'));
            } else {
                $this->error($repair->getError());
            }
        }


        return $this->fetch('../application/admin/view/repair/add.html');

    }
    //列表
    public function index()
    {
        $list = \think\Db::name('Repair')->select();
        //var_dump($list);exit;
        $this->assign('list', $list);

        return $this->fetch('../application/admin/view/repair/index.html');

    }
        //删除
    public function del(){
        $id = array_unique((array)input('id/a',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(\think\Db::name('Repair')->where($map)->delete()){
            session('admin_menu_list',null);
            //记录行为
            action_log('update_repair', 'Reprir', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    //编辑功能
    public function edit($id){
        if(request()->isPost()){
            $Repair = model('Repair');
            $post_data=$this->request->post();
            $data = $Repair->update($post_data);
            if($data){
                session('admin_repair_list',null);
                //记录行为
                action_log('update_repair', 'Repair', $data->id, UID);
                $this->success('更新成功', Cookie('__forward__'));
            } else {
                $this->error($Repair->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = \think\Db::name('Repair')->field(true)->find($id);
            $repairs = \think\Db::name('Repair')->field(true)->select();
            $repairs = model('Common/Tree')->toFormatTree( $repairs);

            $repairs = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $repairs);
            $this->assign('repairs',$repairs);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->assign('meta_title', '编辑后台菜单');
            return $this->fetch('../application/admin/view/repair/add.html');
        }
    }

}