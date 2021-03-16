<?php
//公司部门管理
class invitedcodeMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
		$this->show();
	}

	//用户添加
	public function edit() {
        $info=$this->model->query("select * from att_admininfo where id=".$_SESSION["user_yg"]["id"]);
        $areaId=explode(";",$info[0]["areaId"]);
        $this->model->query("update caiba_users set invited_code='".$_POST["rcode"]."' where areaId in(".$areaId[2].") and invited_code='".$_POST["icode"]."'");
        $this->show('invitedcode/index');
	}

}