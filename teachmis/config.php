<?php
//网站信息
$config['sitename']='幼儿教育管理系统';
$config['seoname']='幼儿教育管理系统';
$config['siteurl']='';
$config['keywords']='';
$config['description']='考核系统';
$config['masteremail']='';
$config['copyright']='版权信息';
$config['ver'] = '1.0.7'; //版本号
$config['ver_name'] = '正式版'; //版本名称
$config['ver_date'] = '20130517'; //版本时间

//全局开关
$config['IP_STATUS']=false; //IP获取地址状态
$config['LANG_OPEN']=false; //多国语言开关
$config['URL_HTML_MODEL']='2'; //伪静态样式
$config['URL_PATHINFO_DEPR']='/';


//上传设置
$config['ACCESSPRY_SIZE']=2; //附件大小，单位M
$config['ACCESSPRY_NUM']=300; //上传数量
$config['ACCESSPRY_TYPE']='pdf,jpg,gif,png';//上传格式
$config['THUMBNAIL_SWIHCH']=true; //是否缩图
$config['THUMBNAIL_MAXWIDTH']=220; //缩图最大宽度
$config['THUMBNAIL_MAXHIGHT']=220; //最大高度
$config['WATERMARK_SWITCH']=false; //是否打水印
$config['WATERMARK_PLACE']=5; //水印位置
$config['WATERMARK_IMAGE']='logo.png'; //水印图片
$config['WATERMARK_CUTOUT']=true; //缩图方式


//日志和错误调试配置
$config['DEBUG']=true;	//是否开启调试模式，true开启，false关闭
$config['LOG_ON']=true;//是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH']='./data/log/';//出错信息存放的目录，出错信息以天为单位存放，一般不需要修改
$config['ERROR_URL']='';//出错信息重定向页面，为空采用默认的出错页面，一般不需要修改
$config['ERROR_HANDLE']=true;//是否启动CP内置的错误处理，如果开启了xdebug，建议设置为false


//伪静态
$config['URL_REWRITE_ON']=true;//是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR']='/';//模块分隔符，一般不需要修改
$config['URL_ACTION_DEPR']='/';//操作分隔符，一般不需要修改
$config['URL_PARAM_DEPR']='-';//参数分隔符，一般不需要修改
$config['URL_HTML_SUFFIX']='.html';//伪静态后缀设置，，例如 .html ，一般不需要修改
$config['URL_HTTP_HOST']='';//设置网址域名特殊


//模块配置
$config['MODULE_PATH']='./module/';//模块存放目录，一般不需要修改
$config['MODULE_SUFFIX']='Mod.class.php';//模块后缀，一般不需要修改
$config['MODULE_INIT']='init.php';//初始程序，一般不需要修改
$config['MODULE_DEFAULT']='index';//默认模块，一般不需要修改
$config['MODULE_EMPTY']='empty';//空模块	，一般不需要修改	
		
//操作配置
$config['ACTION_DEFAULT']='index';//默认操作，一般不需要修改
$config['ACTION_EMPTY']='_empty';//空操作，一般不需要修改

//静态页面缓存
$config['HTML_CACHE_ON']=false;//是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH']='./data/html_cache/';//静态页面缓存目录，一般不需要修改
$config['HTML_CACHE_SUFFIX']='.html';//静态页面缓存后缀，一般不需要修改
$config['HTML_CACHE_RULE']['index']['*']=25;//缓存时间,单位：秒
$config['HTML_CACHE_RULE']['empty']['*']=5000;//缓存时间,单位：秒

/*
缓存规则如下，可创建多条规则
$config['HTML_CACHE_RULE']['模块名']['操作名']=缓存时间;//单位：秒,可创建多条数据
$config['HTML_CACHE_RULE']['模块名1']['操作名1']=缓存时间;
$config['HTML_CACHE_RULE']['模块名1']['操作名2']=缓存时间;
$config['HTML_CACHE_RULE']['模块名2']['操作名1']=缓存时间;
$config['HTML_CACHE_RULE']['模块名2']['操作名2']=缓存时间;
*/

//数据库配置
$config['DB_TYPE']='mysql';//数据库类型，一般不需要修改
$config['DB_HOST']='127.0.0.1';//数据库主机，一般不需要修改
$config['DB_USER']='root';//数据库用户名
$config['DB_PWD']='wanghj010203';//数据库密码
$config['DB_PORT']=3306;//数据库端口，mysql默认是3306，一般不需要修改
$config['DB_NAME']='teachmis';//数据库名
$config['DB_CHARSET']='utf8';//数据库编码，一般不需要修改
$config['DB_PREFIX']='att_';//数据库前缀
$config['DB_PCONNECT']=false;//true表示使用永久连接，false表示不适用永久连接，一般不使用永久连接
$config['SPOT']='HN03B_';

$config['DB_CACHE_ON']=false;//是否开启数据库缓存，true开启，false不开启
$config['DB_CACHE_TYPE']='FileCache';///缓存类型，FileCache或Memcache或SaeMemcache
$config['DB_CACHE_PATH']='./data/db_cache/';//数据库查询内容缓存目录，地址相对于入口文件，一般不需要修改
$config['DB_CACHE_TIME']=600;//缓存时间,0不缓存，-1永久缓存
$config['DB_CACHE_CHECK']=false;//是否对缓存进行校验，一般不需要修改
$config['DB_CACHE_FILE']='cachedata';//缓存的数据文件名
$config['DB_CACHE_SIZE']='15M';//预设的缓存大小，最小为10M，最大为1G
$config['DB_CACHE_FLOCK']=true;//是否存在文件锁，设置为false，将模拟文件锁，一般不需要修改


//模板配置
$config['TPL_TEMPLATE_PATH']='template/default/';//模板目录，一般不需要修改
$config['TPL_TEMPLATE_SUFFIX']='.html';//模板后缀，一般不需要修改
$config['TPL_CACHE_ON']=false;//是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_TYPE']='';//数据缓存类型，为空或Memcache或SaeMemcache，其中为空为普通文件缓存
$config['TPL_CACHE_PATH']='./data/tpl_cache/';//模板缓存目录，一般不需要修改
$config['TPL_CACHE_SUFFIX']='.php';//模板缓存后缀,一般不需要修改

//邮件
$config['SMTP_HOST']='smtpcom.263xmail.com';//smtp服务器地址
$config['SMTP_PORT']=25;//smtp服务器端口
//$config['SMTP_SSL']=true;//是否启用SSL安全连接，gmail需要启用sll安全连接
$config['SMTP_USERNAME']='@aladdin-holdings.com';//smtp服务器帐号，如：你的qq邮箱
$config['SMTP_PASSWORD']='';//smtp服务器帐号密码，如你的qq邮箱密码
$config['SMTP_AUTH']=true;        //启用SMTP验证功能，一般需要开启
$config['SMTP_CHARSET']='utf-8';  //发送的邮件内容编码
$config['SMTP_FROM_TO']='aaron@aladdin-holdings.com';//发件人邮件地址
$config['SMTP_FROM_NAME']='中国教育渠道联盟';//发件人姓名
$config['SMTP_ceshi_NAME']='cnhonk@qq.com';//测试人邮件地址

//同步登录 Cookie 设置
//$cookiedomain = '/'; 			// cookie 作用域
//$cookiepath = 'http://localhost/qiche';			// cookie 作用路径

//审批流程类型
$config['type']=array('请假','物品领用','物品维修','用章申请','书籍借阅','采购申请');


?>
