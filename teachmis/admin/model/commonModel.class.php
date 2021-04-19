<?php
//公共类
class commonModel
{
    protected $model = NULL; //数据库模型
    protected $config = array();
    private $_data = array();

    protected function init(){}
    
    public function __construct(){
        global $config;
        session_start();
        $this->config = $config;
        $this->model = self::initModel( $this->config);
        $this->init();
    }


    //初始化模型
    static public function initModel($config){
        static $model = NULL;
        if( empty($model) ){
            $model = new cpModel($config);
        }
        return $model;
    }

    public function __get($name){
        return isset( $this->_data[$name] ) ? $this->_data[$name] : NULL;
    }

    public function __set($name, $value){
        $this->_data[$name] = $value;
    }

    //提示
    public function msg($message,$status=1) {
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
    }

    //查询
    public function where_str($where)
    {
        if(is_array($where))
        {
            $where_str='';
            foreach($where as $k=>$v)
            {
                if(!$where_str)
                {
                    if(is_array($v))
                    {
                        switch ($v[0])
                        {
                            case 'like':
                                $where_str=$k.' like "%'.$v[1].'%"';
                                break;
                            default:
                                $where_str=$k.'="'.$v[1].'"';
                        }
                    }else{
                        $where_str=$k.'="'.$v.'"';
                    }
                }else{
                    if(is_array($v))
                    {
                        switch ($v[0])
                        {
                            case 'like':
                                $where_str.=' and '.$k.' like "%'.$v[1].'%"';
                                break;
                            default:
                                $where_str.=' and '.$k.'="'.$v[1].'"';
                        }
                    }else{
                        $where_str.=' and '.$k.'="'.$v.'"';
                    }

                }
            }
        }else{
            $where_str=$where;
        }
        return $where_str;
    }

}