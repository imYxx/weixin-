<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ��Ʒ����
// +---------------------------------------------------------------------- 

namespace app\admin\controller;

use think\Request;

class Repair extends Admin
{

    /**
     * �������
     */
    public function add()
    {
        if(request()->isPost()){
            $repair = model('repair');
            $post_data = \think\Request::instance()->post();
            $post_data['create_time'] = time();
            $data = $repair->insert($post_data);
            if($data){
                $this->success('�����ɹ�', url('index'));
            } else {
                $this->error($repair->getError());
            }
        }


        return $this->fetch('../application/admin/view/repair/add.html');

    }
    //�б�
    public function index()
    {
        $list = \think\Db::name('Repair')->select();
        //var_dump($list);exit;
        $this->assign('list', $list);

        return $this->fetch('../application/admin/view/repair/index.html');

    }
        //ɾ��
    public function del(){
        $id = array_unique((array)input('id/a',0));

        if ( empty($id) ) {
            $this->error('��ѡ��Ҫ����������!');
        }

        $map = array('id' => array('in', $id) );
        if(\think\Db::name('Repair')->where($map)->delete()){
            session('admin_menu_list',null);
            //��¼��Ϊ
            action_log('update_repair', 'Reprir', $id, UID);
            $this->success('ɾ���ɹ�');
        } else {
            $this->error('ɾ��ʧ�ܣ�');
        }
    }

    //�༭����
    public function edit($id){
        if(request()->isPost()){
            $Repair = model('Repair');
            $post_data=$this->request->post();
            $data = $Repair->update($post_data);
            if($data){
                session('admin_repair_list',null);
                //��¼��Ϊ
                action_log('update_repair', 'Repair', $data->id, UID);
                $this->success('���³ɹ�', Cookie('__forward__'));
            } else {
                $this->error($Repair->getError());
            }
        } else {
            $info = array();
            /* ��ȡ���� */
            $info = \think\Db::name('Repair')->field(true)->find($id);
            $repairs = \think\Db::name('Repair')->field(true)->select();
            $repairs = model('Common/Tree')->toFormatTree( $repairs);

            $repairs = array_merge(array(0=>array('id'=>0,'title_show'=>'�����˵�')), $repairs);
            $this->assign('repairs',$repairs);
            if(false === $info){
                $this->error('��ȡ��̨�˵���Ϣ����');
            }
            $this->assign('info', $info);
            $this->assign('meta_title', '�༭��̨�˵�');
            return $this->fetch('../application/admin/view/repair/add.html');
        }
    }

}