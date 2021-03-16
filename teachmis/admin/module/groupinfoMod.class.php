<?php
//用户组管理
class groupinfoMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->list=model('groupinfo')->admin_list();
		$this->show();  
	}

	//用户组添加
	public function add() {
        $this->menus=array();
        $this->model_power=array();
        $this->action_name='添加';
        $this->action='add';
        $this->show('groupinfo/info');
	}


    public function data_save($data) {
        $model_power='';
        if(is_array($data['model_power'])){
            foreach ($data['model_power'] as $value) {
                $model_power.=$value.',';
            }
            $data['grouppower']=substr($model_power,0,-1);   
        }else{
            $data['grouppower']='';
        }
        return $data;
    }



	public function add_save() {
        $data=$this->data_save($_POST);
        //录入模型处理
        model('groupinfo')->add($data);
        header('Location: '.__APP__.'/groupinfo/index');
	}

    //用户组修改
    public function edit() {
        $id=$_GET['id'];
        $this->alert_str($id,'int');
        //用户组信息
        $this->info=model('groupinfo')->info($id);
        $this->user=model('admin')->current_user();

        //获取模块权限
        //$this->menu_list=model('menu')->menu_list();
        $this->model_power=explode(',', $this->info['grouppower']);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('groupinfo/info');
    }

    //用户组修改
    public function edit_save() {
        $this->alert_str($_POST['id'],'int',true);
        $data=$this->data_save($_POST);
        //录入模型处理
        model('groupinfo')->edit($data);
        header('Location: '.__APP__.'/groupinfo/index');
    }

    //用户组删除
    public function del() {
        $id=intval($_POST['id']);
        $this->alert_str($id,'int',true);
        $info=model('admin')->list_select("gid=".$id);
        if($info){
            $this->msg('管理组正在使用无法删除！',0);
        }
        //录入模型处理
        $status=model('groupinfo')->del($id);
        $this->msg('用户组删除成功！',1);
    }
	

	

}