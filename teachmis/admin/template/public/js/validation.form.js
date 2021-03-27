
function submitform()
{
    var userType=$("#userType").val();
    var user=$("#user").val();
    var password=$("#password").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!user)
    {
        layer.msg("用户名不能为空！");
        return false;
    }else if(pattern.test(user)){
        layer.msg("用户名存在非法字符！");
        return false;
    }
    if(!password)
    {
        layer.msg("密码不能为空！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"user="+user+"&password="+password+"&userType="+userType,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitteacherform()
{
    var teacherName=$("#teacherName").val();
    var teacherPassword=$("#teacherPassword").val();
    var teacherPhone=$("#teacherPhone").val();
    var teacherSex=$("#teacherSex").val();
    var teacherBirth=$("#teacherBirth").val();
    var teacherEdu=$("#teacherEdu").val();
    var teacherAddress=$("#teacherAddress").val();
    var teacherId=$("#teacherId").val();
    var gid=$("#gid").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!teacherName)
    {
        layer.msg("教师名称不能为空！");
        return false;
    }else if(pattern.test(teacherName)){
        layer.msg("用户名存在非法字符！");
        return false;
    }
    if(!teacherPhone)
    {
        layer.msg("联系方式不能为空！");
        return false;
    }else if(pattern.test(teacherPhone)){
        layer.msg("联系方式存在非法字符！");
        return false;
    }
    if(!teacherAddress)
    {
        layer.msg("教师住址不能为空！");
        return false;
    }else if(pattern.test(teacherAddress)){
        layer.msg("教师住址存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"teacherId="+teacherId+"&teacherName="+teacherName+"&teacherPassword="+teacherPassword+"&teacherPhone="+teacherPhone+"&teacherSex="+teacherSex+"&teacherBirth="+teacherBirth+"&teacherEdu="+teacherEdu+"&teacherAddress="+teacherAddress+"&gid="+gid,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitpositionsform()
{
    var positionsName=$("#positionsName").val();
    var positionsPassword=$("#positionsPassword").val();
    var positionsPhone=$("#positionsPhone").val();
    var positionsSex=$("#positionsSex").val();
    var positionsBirth=$("#positionsBirth").val();
    var positionsEdu=$("#positionsEdu").val();
    var positionsAddress=$("#positionsAddress").val();
    var positionsId=$("#positionsId").val();
    var gid=$("#gid").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!positionsName)
    {
        layer.msg("教师名称不能为空！");
        return false;
    }else if(pattern.test(positionsName)){
        layer.msg("用户名存在非法字符！");
        return false;
    }
    if(!positionsPhone)
    {
        layer.msg("联系方式不能为空！");
        return false;
    }else if(pattern.test(positionsPhone)){
        layer.msg("联系方式存在非法字符！");
        return false;
    }
    if(!positionsAddress)
    {
        layer.msg("职员住址不能为空！");
        return false;
    }else if(pattern.test(positionsAddress)){
        layer.msg("职员住址存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"positionsId="+positionsId+"&positionsName="+positionsName+"&positionsPassword="+positionsPassword+"&positionsPhone="+positionsPhone+"&positionsSex="+positionsSex+"&positionsBirth="+positionsBirth+"&positionsEdu="+positionsEdu+"&positionsAddress="+positionsAddress+"&gid="+gid,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitclassform()
{
    var className=$("#className").val();
    var classYear=$("#classYear").val();
    var gradeId=$("#gradeId").val();
    var classFew=$("#classFew").val();
    var teacherId=$("#teacherId").val();
    var classId=$("#classId").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!className)
    {
        layer.msg("班级名称不能为空！");
        return false;
    }else if(pattern.test(className)){
        layer.msg("班级名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"classId="+classId+"&className="+className+"&classYear="+classYear+"&gradeId="+gradeId+"&classFew="+classFew+"&teacherId="+teacherId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitusersform()
{
    var usersName=$("#usersName").val();
    var usersPassword=$("#usersPassword").val();
    var studentName=$("#studentName").val();
    var studentAge=$("#studentAge").val();
    var studentSex=$("#studentSex").val();
    var classId=$("#classId").val();
    var gradeId=$("#gradeId").val();
    var usersId=$("#usersId").val();
    var gid=$("#gid").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!usersName)
    {
        layer.msg("班级名称不能为空！");
        return false;
    }else if(pattern.test(usersName)){
        layer.msg("班级名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"classId="+classId+"&usersName="+usersName+"&usersPassword="+usersPassword+"&studentName="+studentName+"&studentAge="+studentAge+"&studentSex="+studentSex+"&usersId="+usersId+"&gid="+gid+"&gradeId="+gradeId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitgradeform()
{
    var gradeName=$("#gradeName").val();
    var gradeId=$("#gradeId").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!gradeName)
    {
        layer.msg("年级名称不能为空！");
        return false;
    }else if(pattern.test(gradeName)){
        layer.msg("年级名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"gradeId="+gradeId+"&gradeName="+gradeName,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitbookform()
{
    var gradeId=$("#gradeId").val();
    var bookChaptersName=$("#bookChaptersName").val();
    var bookChaptersFid=$("#bookChaptersFid").val();
    var bookChaptersId=$("#bookChaptersId").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!bookChaptersName)
    {
        layer.msg("年级名称不能为空！");
        return false;
    }else if(pattern.test(bookChaptersName)){
        layer.msg("年级名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"gradeId="+gradeId+"&bookChaptersName="+bookChaptersName+"&bookChaptersFid="+bookChaptersFid+"&bookChaptersId="+bookChaptersId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submitquestionsform()
{
    var questionsName=$("#questionsName").val();
    var questionsType=$("#questionsType").val();
    var gradeId=$("#gradeId").val();
    var questionsDifficulty=$("#questionsDifficulty").val();
    var bookChaptersNum=$("#bookChaptersNum").val();
    var str=",";
    for(var i=0;i<=bookChaptersNum*1;i++)
    {
        if($("#bookChapters"+i).val())
        {
            var bookChapters=$("#bookChapters"+i).val();
            str=str+bookChapters+",";
        }else{
            layer.msg("课程章节不能为空");
            return false;
        }
    }
    var answerNum=$("#answerNum").val();
    var answerstr="";
    for(var s=1;s<=answerNum*1;s++)
    {
        if($("#answerName"+s).val())
        {
            var answerName=$("#answerName"+s).val();
            var answerOptions=$("#answerOptions"+s).val();
            answerstr=answerstr+answerName+","+answerOptions+";";
        }else{
            layer.msg("试题选项不能为空");
            return false;
        }
    }
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"questionsName="+questionsName+"&questionsType="+questionsType+"&gradeId="+gradeId+"&questionsDifficulty="+questionsDifficulty+"&bookChaptersId="+str+"&answerstr="+answerstr,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
function submiteditquestionsform()
{
    var questionsName=$("#questionsName").val();
    var questionsType=$("#questionsType").val();
    var gradeId=$("#gradeId").val();
    var questionsDifficulty=$("#questionsDifficulty").val();
    var bookChaptersNum=$("#bookChaptersNum").val();
    var questionsId=$("#questionsId").val();
    var str=",";
    for(var i=0;i<=bookChaptersNum*1;i++)
    {
        if($("#bookChapters"+i).val())
        {
            var bookChapters=$("#bookChapters"+i).val();
            str=str+bookChapters+",";
        }else{
            layer.msg("课程章节不能为空");
            return false;
        }
    }
    var answerNum=$("#answerNum").val();
    var answerstr="";
    for(var s=0;s<=answerNum*1;s++)
    {
        if($("#answerName"+s).val())
        {
            var answerId=$("#answerId"+s).val();
            var answerName=$("#answerName"+s).val();
            var answerOptions=$("#answerOptions"+s).val();
            answerstr=answerstr+answerName+","+answerOptions+","+answerId+";";
        }else{
            layer.msg("试题选项不能为空");
            return false;
        }
    }
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"questionsName="+questionsName+"&questionsType="+questionsType+"&gradeId="+gradeId+"&questionsDifficulty="+questionsDifficulty+"&bookChaptersId="+str+"&answerstr="+answerstr+"&questionsId="+questionsId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}

function submitPaper()
{
    var paperName=$("#paperName").val();
    var questionsTypeNum=$("#questionsTypeNum").val();
    var qstr=',';
    var questionsType='';
    for(var i=1;i<=questionsTypeNum;i++)
    {
        var num=$("#questionsTypeNum"+i).val();
        if(num)
        {
            for(var qi=0;qi<num;qi++)
            {
                if($("#questionsId"+i+"_"+qi).val())
                {
                    qstr=qstr+$("#questionsId"+i+"_"+qi).val()+",";
                }else{
                    layer.msg("请先点击生成试卷！"+"#questionsId"+i+"_"+qi);
                    return false;
                }
            }
            if(i!=questionsTypeNum)
            {
                qstr=qstr+";,";
            }
            if(questionsType)
            {
                questionsType=questionsType+","+i;
            }else{
                questionsType=i;
            }
        }
    }
    var gradeId=$("#gradeId").val();
    var str=',';
    $('input[name="classId"]:checked').each(function(){
        str=str+$(this).val()+',';
    });
    var questionsDifficulty=$("#questionsDifficulty").val();
    var startTime=$("#startTime").val();
    var endTime=$("#endTime").val();
    if(startTime>=endTime)
    {
        layer.msg("开始时间"+startTime+"不能大于结束时间"+endTime+"！");
        return false;
    }
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"paperName="+paperName+"&questionsIds="+qstr+"&gradeId="+gradeId+"&classId="+str+"&questionsDifficulty="+questionsDifficulty+"&startTime="+startTime+"&endTime="+endTime+"&questionsTypes="+questionsType,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}

function selectSearchList(type)
{
    if(type>0)
    {
        var page=$("#pageNum").val();
        if(type==1)
            page=page*1+1;
        if(type==2)
            page=page*1-1;
    }else{
        var page=1;
    }
    var searchStr=$("#searchStr").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"searchStr="+searchStr+"&page="+page,
        dataType:'json',
        success:function(d){
            if(d.list)
            {
                var str='';
                $.each(d.list,function(name,value){
                    str=str+'<tr id="'+value.paperId+'" class="active unread checked"><td class="hidden-xs"><input type="checkbox" name="paperId" value="'+value.paperId+'" class="checkbox"> </td> <td>'+value.paperName+'</td> <td>'+value.gradeName+'</td> <td>'+value.className+'</td> <td>'+value.teacherName+'</td> <td>'+value.startTime+'</td> <td>'+value.endTime+'</td> <td> <a href="javascript:void(0)" onclick="del('+value.paperId+')">删除</a></td> </tr>';
                });
                $(".table tbody").html(str);
            }
            if(page==1)
            {
                $(".btn-default-left").hide();
                if(d.page>1)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }else{
                $(".btn-default-left").show();
                if(d.page>page)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }
            $("#pageNum").val(page);
            $(".pageNumStr").html('Showing '+page+' of '+d.page+' ');
        }
    });
}

function selectMyOperationList(type)
{
    if(type>0)
    {
        var page=$("#pageNum").val();
        if(type==1)
            page=page*1+1;
        if(type==2)
            page=page*1-1;
    }else{
        var page=1;
    }
    var searchStr=$("#searchStr").val();
    var app=$("#app").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"searchStr="+searchStr+"&page="+page,
        dataType:'json',
        success:function(d){
            if(d.list)
            {
                var str='';
                $.each(d.list,function(name,value){
                    if(value.status==1)
                    {
                        value.status='已参考';
                        var url='';
                    }else{
                        var url='<a href="'+app+'myOperation/info/id-'+value.paperId+'">考试</a>';
                        value.status='未参考';
                    }
                    str=str+'<tr id="'+value.paperId+'" class="active unread checked"><td class="hidden-xs"><input type="checkbox" name="paperId" value="'+value.paperId+'" class="checkbox"> </td> <td>'+value.paperName+'</td> <td>'+value.gradeName+'</td> <td>'+value.className+'</td> <td>'+value.teacherName+'</td> <td>'+value.status+'</td> <td>'+value.startTime+'</td> <td>'+value.endTime+'</td> <td>'+url+'</td> </tr>';
                });
                $(".table tbody").html(str);
            }
            if(page==1)
            {
                $(".btn-default-left").hide();
                if(d.page>1)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }else{
                $(".btn-default-left").show();
                if(d.page>page)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }
            $("#pageNum").val(page);
            $(".pageNumStr").html('Showing '+page+' of '+d.page+' ');
        }
    });
}

function selectmyPractiseList(type)
{
    if(type>0)
    {
        var page=$("#pageNum").val();
        if(type==1)
            page=page*1+1;
        if(type==2)
            page=page*1-1;
    }else{
        var page=1;
    }
    var searchStr=$("#searchStr").val();
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"searchStr="+searchStr+"&page="+page,
        dataType:'json',
        success:function(d){
            if(d.list)
            {
                var str='';
                $.each(d.list,function(name,value){
                    str=str+'<tr id="'+value.userQuestionId+'" class="active unread checked"><td class="hidden-xs"><input type="checkbox" name="userQuestionId" value="'+value.userQuestionId+'" class="checkbox"> </td> <td>'+value.questionsName+'</td> <td>'+value.questionsType+'</td> <td>'+value.questionsDifficulty+'</td> <td>'+value.status+'</td> <td>'+value.createTime+'</td><td><a href="__APP__/myPractise/edit/id-'+value.userQuestionId+'">查看</a></td> </tr>';
                });
                $(".table tbody").html(str);
            }
            if(page==1)
            {
                $(".btn-default-left").hide();
                if(d.page>1)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }else{
                $(".btn-default-left").show();
                if(d.page>page)
                {
                    $(".btn-default-right").show();
                }else{
                    $(".btn-default-right").hide();
                }
            }
            $("#pageNum").val(page);
            $(".pageNumStr").html('Showing '+page+' of '+d.page+' ');
        }
    });
}
function submitcurriculumform()
{
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var classId=$("#classId").val();
    var week=$("#week").val();
    var weekSort=$("#weekSort").val();
    var startTime=$("#startTime").val();
    var endTime=$("#endTime").val();
    var courseName=$("#courseName").val();
    var teacherId=$("#teacherId").val();
    var curriculumId=$("#curriculumId").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!courseName)
    {
        layer.msg("课程表名称不能为空！");
        return false;
    }else if(pattern.test(courseName)){
        layer.msg("课程表名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"classId="+classId+"&week="+week+"&weekSort="+weekSort+"&startTime="+startTime+"&endTime="+endTime+"&courseName="+courseName+"&teacherId="+teacherId+"&curriculumId="+curriculumId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl+"add?curriculumId="+d.data;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
//部门管理提交表单
function submitdepartmentIdform()
{
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var fid=$("#fid").val();
    var departmentName=$("#departmentName").val();
    var departmentId=$("#departmentId").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!departmentName)
    {
        layer.msg("部门名称不能为空！");
        return false;
    }else if(pattern.test(departmentName)){
        layer.msg("部门名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"fid="+fid+"&departmentName="+departmentName+"&id="+departmentId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}
//审批流程提交表单
function submitexaminationtypeform()
{
    var formUrl=$("#formUrl").val();
    var formUrlName=$("#formUrlName").val();
    var type=$("#type").val();
    var typeName=$("#typeName").val();
    var auditArchitecture=$("#auditArchitecture").val();
    var teacherId1=$("#teacherId1").val();
    var teacherId2=$("#teacherId2").val();
    var teacherId3=$("#teacherId3").val();
    var examinationtypeId=$("#examinationtypeId").val();
    var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
    if(!typeName)
    {
        layer.msg("审批流程名称不能为空！");
        return false;
    }else if(pattern.test(typeName)){
        layer.msg("审批流程名称存在非法字符！");
        return false;
    }
    $.ajax({
        url:formUrl+formUrlName,
        type:'post',
        data:"type="+type+"&typeName="+typeName+"&auditArchitecture="+auditArchitecture+"&teacherId1="+teacherId1+"&teacherId2="+teacherId2+"&teacherId3="+teacherId3+"&id="+examinationtypeId,
        dataType:'json',
        success:function(d){
            if(d.status==0){
                layer.msg(d.message);
                window.location.href=formUrl;
            }else{
                layer.msg(d.message);
            }
        }
    });
}