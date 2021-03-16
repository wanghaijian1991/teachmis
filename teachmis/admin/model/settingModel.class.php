<?php
class settingModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //保存配置
    public function save($array) {
        $config_file=__ROOTDIR__.'/config.php';
        if (empty($array) || !is_array($array)) {
            return false;
        }
        $config = @file_get_contents($config_file); //读取配置
        foreach ($array as $name => $value) {
            $name = str_replace(array("'", '"', '[','*'), array("\\'", '\"', '\[','\*'), $name); //转义特殊字符，再传给正则替换
            if (is_string($value) && !in_array($value, array('true', 'false', '3306'))) {
                if(!intval($value)){
                    $value = "'" . $value . "'"; //如果是字符串，加上单引号
                }
            }
            $config = preg_replace("/(\\$" . $name . ")\s*=\s*(.*?);/i", "$1={$value};", $config); //查找替换
        }
        // 写入配置
        if (@file_put_contents($config_file, $config)){
            return true;
        }else{
           return false;
        }
    }

}

?>