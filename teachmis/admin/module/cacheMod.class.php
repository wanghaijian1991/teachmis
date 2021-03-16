<?php
//缓存处理
class cacheMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

    //删除模板缓存
    public function clear_tpl()
    {
    	$dir=__ROOTDIR__.'/data/tpl_cache/';
        del_dir($dir);
        $this->msg('删除模板缓存成功!');
    }

    //删除html缓存
    public function clear_html()
    {
    	$dir=__ROOTDIR__.'/data/html_cache/';
        del_dir($dir);
        $this->msg('删除HTML缓存成功! ');
    }

    //删除数据缓存
    public function clear_data()
    {
    	$dir=__ROOTDIR__.'/data/db_cache/';
        del_dir($dir);
        $this->msg('删除数据缓存成功! ');
    }

    //删除所有缓存
    public function clear_all()
    {
    	$tpl_cache=__ROOTDIR__.'/data/tpl_cache/';
        del_dir($tpl_cache);

    	$html_cache=__ROOTDIR__.'/data/html_cache/';
        del_dir($html_cache);

    	$db_cache=__ROOTDIR__.'/data/db_cache/';
        del_dir($db_cache);

        $this->msg('已经删除所有缓存! ');
    }

}