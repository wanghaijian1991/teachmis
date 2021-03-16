<?php
//密码管理
class infoMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $this->info=model('info')->info($_SESSION["user_yg"]["id"]);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('info/addinfo');
	}

    //保存修改
    public function edit() {

        if ($_POST['password']!='')
        {
            if ($_POST['password2']=='')
            {
				$this->alert('未填写确认密码！','');
               return;
            }
            if($_POST['password']<>$_POST['password2']){
				$this->alert('两次密码输入不同！','');
                return;
            }
            $_POST['password']=md5($_POST['password']);
			//录入模型处理
			model('info')->update($_POST);
			$this->alert('修改成功！',__APP__.'/attinfo');
        }else{
            $this->alert('未填写密码！','');
               return;
        }
        

    }


}