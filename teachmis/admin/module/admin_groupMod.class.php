<?php
//用户组管理
class admin_groupMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->list=model('admin_group')->admin_list();
		$this->show();  
	}

	//用户组添加
	public function add() {
        $this->user=model('admin')->current_user();
        //获取栏目树
        $tree=model('menu')->news_menu();
        if(!empty($tree)){
            $data='';
            foreach ($tree as $value) {
               $data.='{cid:'.$value['cid'].',pid:'.$value['pid'].', name:"'.$value['name'].'"  ,title:"'.$value['name'].'" }, '."\n";
            }
            $data.='{name:" ", isHidden:true  }'."\n";
        }
        $this->class_tree=$data;

        //获取模块权限
        $this->menu_list=model('menu')->menu_list();
        $this->action_name='添加';
        $this->action='add';
        $this->show('admin_group/info');
	}


    public function data_save($data) {
        if(!empty($data['class_power'])){
            $data['class_power']=substr($data['class_power'],0,-1);   
        }

        if(is_array($data['model_power'])){
            foreach ($data['model_power'] as $value) {
                $model_power.=$value.',';
            }
            $data['model_power']=substr($model_power,0,-1);   
        }else{
            $data['model_power']='';
        }
        return $data;
    }



	public function add_save() {
        $data=$this->data_save($_POST);
        //录入模型处理
        model('admin_group')->add($data);
        $this->msg('用户组添加成功！',1);
	}

    //用户组修改
    public function edit() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        //用户组信息
        $this->info=model('admin_group')->info($id);
        $this->user=model('admin')->current_user();
        if($this->info['grade']<$this->user['grade']){
            $this->msg('越权操作！',0);
        }
        //获取栏目树
        $tree=model('menu')->news_menu();
        $class_power=explode(',', $this->info['class_power']);
        $data='';
        if(!empty($tree)){
            foreach ($tree as $value) {
                if(!empty($this->info['class_power'])){
                    if(in_array($value['cid'],$class_power)){
                        $purview=' , checked:true ';
                    }else{
                        $purview=' ';
                    }
                }
               $data.='{cid:'.$value['cid'].',pid:'.$value['pid'].',name:"'.$value['name'].'" ,title:"'.$value['name'].'" '.$purview.' }, '."\n";
            }
            $data.='{name:" ", isHidden:true  }'."\n";
        }
        $this->class_tree=$data;

        //获取模块权限
        $this->menu_list=model('menu')->menu_list();
        $this->model_power=explode(',', $this->info['model_power']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('admin_group/info');
    }

    //用户组修改
    public function edit_save() {
        $this->alert_str($_POST['id'],'int',true);
        $data=$this->data_save($_POST);
        //录入模型处理
        model('admin_group')->edit($data);
        $this->msg('用户组修改成功! ',1);
    }

    //用户组删除
    public function del() {
        $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
        $info=model('admin_group')->info($id);
        if($info['keep']==1){
            $this->msg('内置管理组无法删除！',0);
        }
        $this->user=model('admin')->current_user();
        if($info['grade']<$this->user['grade']){
            $this->msg('越权操作！',0);
        }
        //录入模型处理
        model('admin_group')->del($id);
        $this->msg('用户组删除成功！',1);
    }
	

	

}