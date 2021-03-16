<?php
class menuModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取主菜单
    public function main_menu(){
        $user=model('admin')->current_user();
        if(!empty($user['model_power'])){
            $mod_where=' AND id in('.$user['model_power'].')';
        }
        $menu_list=$this->model->table('menuinfo')->where('menustatus=1 AND fid=0 '.$mod_where)->order('id asc')->select();
        foreach ($menu_list as $key=>$value) {
			/*$arr=explode(',',$menu_power);
            if(in_array($value['id'],$arr)){*/
            $menu_list[$key]["menus"]=$this->model->table('menuinfo')->where('menustatus=1 AND fid='.$value["id"].' '.$mod_where)->order('id asc')->select();
            /*}*/
			
        }
        return $menu_list;
    }

    public function menu_info($id)
    {
        return $this->model->table('menuinfo')->where("id=".$id)->find();
    }
    //新增
    public function add($data)
    {
        if($data["id"])
        {
            return $this->model->table('menuinfo')->data($data)->where("id=".$data["id"])->update();
        }else{
            return $this->model->table('menuinfo')->data($data)->insert();
        }
    }
	
	
    //获取菜单
    public function admin_menu($pid=0) {
        $user=model('admin')->current_user();
        if(!empty($user['model_power'])){
            $mod_where=' AND id in('.$user['model_power'].')';
        }
        return $this->model->table('menuinfo')->where('menustatus=1 AND fid='.$pid.$mod_where)->order('id asc')->select();
    }

    //获取菜单项目
    public function menu_list($pid=0) {
        return $this->model->table('menuinfo')->where('fid='.$pid)->order('id asc')->select();
    }

    //格式化产品内容菜单
    public function product_menu($gid=null) {
        $data=$this->model->table('product_category')->order('sequence DESC,cid ASC')->select();
        if(!empty($gid)){
            $user=model('admin')->current_user();
            if(!empty($user['class_power'])){
                $class_power=explode(',', $user['class_power']);
            }
        }
        return $this->gentree($data,$class_power);
    }

    //检测模块
    public function module_menu($module) {
        return $this->model->table('menuinfo')->where('module="'.$module.'"')->find();
    }

    //输出数据
    public function gentree($data,$class_power=array()){
        if(empty($class_power)){
            return $data;
        }
        if(!empty($data)){
            foreach ($data as $key=>$value) {
                $tree[$key]=$value;
                if(in_array($value['cid'],$class_power)){
                    $tree[$key]['pw']=0;
                }else{
                    $tree[$key]['pw']=1;
                }
            }
        }
        return $tree;

    }
	
    //格式化内容菜单
    public function user_menu($gid=null) {
		if($_SESSION["user_yg"]["fid"]==1)
		{
			$data=$this->model->table('department')->where("id<>35 and id<>36")->order("id ASC")->select();
		}else
		{
			$data=$this->model->table('department')->where("id=".$_SESSION["user_yg"]["fid"]." or fid=".$_SESSION["user_yg"]["fid"])->order("id ASC")->select();
		}
        if(!empty($gid)){
            $user=model('admin')->current_user();
            if(!empty($user['class_power'])){
                $class_power=explode(',', $user['class_power']);
            }
        }
        return $this->gentree($data,$class_power);
    }


}

?>