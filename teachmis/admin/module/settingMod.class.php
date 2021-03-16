<?php
class settingMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    // 显示系统设置页面
    public function index()
    {
        require (__ROOTDIR__.'/config.php'); 
        $this->config_array=$config;
        $this->show();
    }

    // 修改系统设置
    public function save()
    {
        $config = $_POST; //接收表单数据
        $config_array = array();
        foreach ($config as $key => $value) {
            if(!strpos($key,'|')){
                $config_array["config['" . $key . "']"] = $value;
            }else{
                $strarray=explode('|', $key);
                $str="config['" . $strarray[0] . "']";
                foreach ($strarray as $keys=>$values) {
                    if($keys<>0){
                    $str.="['".$values."']";
                    }
                }
                unset($strarrays);
                $config_array[$str] = $value;
            }
        }
        $status=model('setting')->save($config_array);
        if($status){
            $this->msg('网站配置成功！',1);
        }else{
            $this->msg('网站配置失败，可能由于配置文件权限或路径问题！',0);
        }
    }

    // 测试邮件
    public function email()
    {
		$name=$_GET['name'];
		require (__ROOTDIR__.'/include/ext/Email.class.php'); //加载邮件发送类
        require (__ROOTDIR__.'/config.php'); 
		Email::init($config);//初始化配置
		Email::send($name,'测试邮件','这是一封由系统自动发出的邮件！');//发送邮件 
		$this->alert('已发送，请查收！');
    }

}
?>