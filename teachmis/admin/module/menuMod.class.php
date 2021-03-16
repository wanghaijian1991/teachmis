<?php
class menuMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }


    //后台首页菜单
    public function index()
    {
        $this->list=model('menu')->main_menu();
        $this->display();
    }
    //后台菜单添加
    public function add()
    {
        if(!$_POST)
        {
            $this->list=model('menu')->admin_menu(0);
            $this->display();
        }else{
            model('menu')->add($_POST);
            $this->display();
        }
    }
    //后台菜单修改
    public function edit()
    {
        if(!$_POST)
        {
            $this->list=model('menu')->admin_menu(0);
            $this->info=model('menu')->menu_info($_GET["id"]);
            $this->display();
        }else{
            model('menu')->add($_POST);
            echo "<script>window.location.href='".__URL__."'</script>";
        }
    }
	
	 //后台管理
    public function admin() {
        $this->list=model('menu')->admin_menu(3);
        $this->display();
    }
	
	 //管理
    public function user() {
        $this->list=model('menu')->admin_menu(6);
		
		$this->user=model('admin')->current_user();
		$tree=model('menu')->user_menu($this->user['gid']);
		$data='';
		if(!empty($tree)){
			foreach ($tree as $value) {
				if($value["fid"]==0)
				{
                    $url=' , url:"'.__APP__.'/employees/index/fid-'.$value['id'].'", target:"main" , icon:"'.__PUBLICURL__.'/ztree/css/img/ico2.gif" '; 
				}
                    $url=' , url:"'.__APP__.'/employees/index/id-'.$value['id'].'", target:"main" , icon:"'.__PUBLICURL__.'/ztree/css/img/ico2.gif" '; 

                if($value['pw']==1){
                    $purview=' , isHidden:true ';
                }else{
                    $purview=' , isHidden:false ';
                }
               $data.='{cid:'.$value['id'].',pid:'.$value['fid'].', name:"'.$value['name'].'" '.$url.$purview.' }, '."\n";
           }
           $data.='{name:" ", isHidden:true  }'."\n";
		}
		$this->class_tree=$data;
		$this->display();
    }
	
    //后台首页菜单
    public function fragment()
    {
        $this->list=model('menu')->admin_menu(10);
        $this->display();
    }    
	
	
	







}

?>