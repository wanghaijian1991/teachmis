<?php
class editorMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取编辑器
    public function get_editor($id,$ajax=false) {
        $ext_str  = 'cssPath : \'' . __PUBLICURL__ . '/kindeditor/plugins/code/prettify.css\',';
        $ext_str .= 'fileManagerJson:\'' . __APP__ . '/editor_file_manage/index.html'. '\',';
        $ext_str .= 'uploadJson:\'' . __APP__ . '/editor_upload/index.html?key='.urlencode($this->config['SPOT'].$this->config['DB_NAME']) . '\',';

        $html="<script type=\"text/javascript\">";
        if($ajax){
        $html.="$(function() {";
        }else{
        $html.="KindEditor.ready(function() {";
        }
        $html.="editor_".$id." = KindEditor.create(";
        $html.="
                '#".$id."', 
                        {
                            ".$ext_str."
                            allowFileManager : true,
                            filterMode : false,
                            afterUpload : function(url,data) {
                                $('#file_id').val($('#file_id').val()+data.id+',');
                            }
                        });
                });";
        $html.="</script>\r\n";
        return $html;
    }

    //获取外部文件上传
    public function sapload($contentid) {
        $html='';
        $html.='<span id="sapload"></span>';
        $html.="
        <script type=text/javascript src=\"".__PUBLICURL__."/sapload/swfobject.js\"></script>
        <script type=\"text/javascript\">
            var flashvars = {};
            flashvars.types = '*';
            flashvars.args = 'myid=111;yid=222';
            flashvars.upUrl = '" . __APP__ . "/editor_upload/index.html?key=".urlencode($this->config['SPOT'].$this->config['DB_NAME']). "';
            flashvars.fileName = 'Filedata';
            flashvars.isGet = '1';
            flashvars.maxNum = '".$this->config['ACCESSPRY_NUM']."';
            flashvars.maxSize = '".$this->config['ACCESSPRY_SIZE']."';
            flashvars.etmsg = '1';
            flashvars.ltmsg = '1';
            var params = {};
            params.wmode = 'opaque';
            var attributes = {};
            swfobject.embedSWF('".__PUBLICURL__."/sapload/sapload.swf', 'sapload', '450', '25', '9.0.0', '', flashvars, params, attributes);
            function sapLoadMsg(t){
                json=eval('(' + t + ')');
                if(json.error==1){
                    $.dialog.tips(json.message, 3);
                }else{
                    var ext = json.url.split('.')[1];
                    switch (ext) {
                        ".$this->plus_hook('editor','sapload')."
                        default:
                            html='内容附件: <a href=\"' + json.url + '\">' + json.title + '</a></div>';
                        break;
                    }
                    ".$contentid.".insertHtml(html);
                    $('#file_id').val($('#file_id').val()+json.id+',');
                }
            }
        </script>
        ";
        return $html;
    }

    //远程存图
    public function get_remote_image(){
        $content=$_POST['content'];
        if(empty($content)){
            $this->msg('没有获取到内容！',0);
        }
        //文件路径
        $file_path = __ROOTDIR__ . '/upload/'.date('Y-m').'/'.date('d').'/';
        //文件URL路径
        $file_url = __ROOTURL__ . '/upload/'.date('Y-m').'/'.date('d').'/';
        $body=html_out($content);
        $img_array = array();
        preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|bmp|png))/isU",$body,$img_array);
        $img_array = array_unique($img_array[2]);
        set_time_limit(0);
        $milliSecond = date("dHis") . '_';
        if(!is_dir($file_path)) @mkdir($file_path,0777);
        foreach($img_array as $key =>$value)
        {
            $value = trim($value);
            $get_file = @Http::doGet($value,5);
            $rndFileName = $file_path.$milliSecond.$key.'.'.substr($value,-3,3);
            $fileurl = $file_url.$milliSecond.$key.'.'.substr($value,-3,3);
            if($get_file)
            {
                $status=@file_put_contents($rndFileName, $get_file);
                if($status){
                    $body = str_replace($value,$fileurl,$body);
                }
            }
            
        }
        $this->msg($body,1);
    }
    

    //获取图片上传
    public function get_image_upload($id,$url,$ajax=false,$editor='') {

        //$ext_str .= 'fileManagerJson:\'' . __APP__ . '/editor_file_manage/index.html'. '\',';
        $ext_str .= 'uploadJson:\'' . __APP__ . '/editor_upload/index.html' . '\',';

        $html="<script type=\"text/javascript\">";
        if($ajax){
        $html.="$(function() {";
        }else{
        $html.="KindEditor.ready(function() {";
        }
        $html.="
                var editor = KindEditor.editor({
                    ".$ext_str."
                    allowFileManager : true,
                    afterUpload : function(url,data) {
                        $('#file_id').val($('#file_id').val()+data.id+',');
        ";
        if(!empty($editor)){
            $html.=$editor.'.insertHtml(\'<img src="\'+data.original+\'" title="\'+data.title+\'" alt="\'+data.title+\'" />\');';
        }
        $html.="
                    }
                });
                KindEditor('#".$id."').click(function() {
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            imageUrl : KindEditor('#".$url."').val(),
                            clickFn : function(url, title, width, height, border, align) {
                                KindEditor('#".$url."').val(url);
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
            </script>\r\n";
        return $html;
    }

    //获取文件上传
    public function get_file_upload($id,$url,$ajax=false) {
        //$ext_str .= 'fileManagerJson:\'' . __APP__ . '/editor_file_manage/index.html'. '\',';
        $ext_str .= 'uploadJson:\'' . __APP__ . '/editor_upload/index.html'. '\',';

        $html="<script type=\"text/javascript\">";
        if($ajax){
        $html.="$(function() {";
        }else{
        $html.="KindEditor.ready(function() {";
        }
        $html.="
                var editor = KindEditor.editor({
                    ".$ext_str."
                    allowFileManager : true,
                    afterUpload : function(url,data) {
                        $('#file_id').val($('#file_id').val()+data.id+',');
                    }
                });
                KindEditor('#".$id."').click(function() {
                    editor.loadPlugin('insertfile', function() {
                        editor.plugin.fileDialog({
                            fileUrl : KindEditor('#".$url."').val(),
                            clickFn : function(url, title) {
                                KindEditor('#".$url."').val(url);
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
            </script>\r\n";
        return $html;
    }

    //获取组图上传
    public function get_images_upload($id,$ajax=false) {
        $ext_str .= 'uploadJson:\'' . __APP__ . '/editor_upload/index.html?key='.urlencode($this->config['SPOT'].$this->config['DB_NAME']). '\',';
        $html="<script type=\"text/javascript\">";
        if($ajax){
        $html.="$(function() {";
        }else{
        $html.="KindEditor.ready(function() {";
        }
        $html.="
                var editor = KindEditor.editor({
                    ".$ext_str."
                });
                KindEditor('#".$id."_button').click(function() {
                    editor.loadPlugin('multiimage', function() {
                        editor.plugin.multiImageDialog({
                            clickFn : function(urlList) {
                                var div = KindEditor('#".$id."_list');
                                KindEditor.each(urlList, function(i, data) {
                                    html=\"<li>\
                                    <div class='pic' id='images_button'>\
                                        <img src='\" + data.url + \"' width='125' height='105' />\
                                        <input  id='".$id."[]' name='".$id."[]' type='hidden' value='\" + data.url + \"' />\
                                        <input  id='".$id."_original[]' name='".$id."_original[]' type='hidden' value='\" + data.original + \"' />\
                                    </div>\
                                    <div class='title'>标题： <input name='".$id."_title[]' type='text' id='".$id."_title[]' value='' /></div>\
                                    <div class='title'>排序： <input id='".$id."_order[]' name='".$id."_order[]' value='0' type='text' style='width:50px;' /> <a href='javascript:void(0);' onclick='$(this).parent().parent().remove()'>删除</a></div>\
                                    </li>\
                                    \";
                                    div.append(html);
                                    $('#file_id').val($('#file_id').val()+data.id+',');
                                });
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
            </script>";
        return $html;
    }

    //获取文件选择
    public function get_file_manage($id,$url,$ajax=false) {
        $ext_str .= 'fileManagerJson:\'' . __APP__ . '/editor_file_manage/index.html'. '\'';
        $html="<script type=\"text/javascript\">";
        if($ajax){
        $html.="$(function() {";
        }else{
        $html.="KindEditor.ready(function() {";
        }
        $html.="
                var editor = KindEditor.editor({
                    ".$ext_str."
                });
                KindEditor('#".$id."').click(function() {
                    editor.loadPlugin('filemanager', function() {
                        editor.plugin.filemanagerDialog({
                            viewType : 'VIEW',
                            dirName : '../themes',
                            clickFn : function(url, title) {
                                KindEditor('#".$url."').val(url);
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
            </script>\r\n";
        return $html;
    }


}

?>