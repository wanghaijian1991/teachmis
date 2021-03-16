

function snapuptime(id) {
	mh1=$("#mh1").val();
	mh2=$("#mh2").val();
	$.get("__URL__/snapuptime",{id:id,starttime:mh1,endtime:mh2},function(data){
		alert("限时抢购设置成功！");
		});
}
function nosnapuptime(id) {
	mh1=0;
	mh2=0;
	$.get("__URL__/snapuptime",{id:id,starttime:mh1,endtime:mh2},function(data){
		alert("限时抢购设置成功！");
		});
}
 	function tiqu(id)
	{
		$.get("__URL__/ld",{fid:id},function(data)
		{
			$("#cxselectItem #selectSub").html(data);
		});
	}
	function ldf(id)
	{
		cxname=$("#"+id).val();
		$("#cxname").val(cxname);
		$("#cxid").val(id);
		$("#cxselectItem").hide();
	}
	function changeadd()
	{
		changenum=$("#changenum").val();
		$("#changeadd").append('<tr><td>过户主体</td> <td><input type="text" name="changeid'+changenum+'" value="{$chvo.changeid}" style="display:none"><select name="changename" id="changename'+changenum+'" style="width:60px;"><option>个人</option><option>公司</option> </select> </td><td>过户时间</td><td colspan="2"><select name="changetime'+changenum+'" id="changetime" style="width:60px;"><?php for($i=1991;$i<2500;$i++){ echo "<option>".$i."</option>"; }?></select><select name="changetime1'+changenum+'" id="changetime1'+changenum+'" style="width:60px;"><?php for($i=1;$i<13;$i++){ echo "<option>".$i."</option>"; }?> </select><select name="changetime2'+changenum+'" id="changetime2'+changenum+'" style="width:60px;"><?php for($i=1;$i<32;$i++){ echo "<option>".$i."</option>"; }?></select></td><td>过户方式</td><td><select name="changeclass'+changenum+'" id="changeclass" style="width:60px;"><option>购买</option></select> </td> </tr>');
		changenum=changenum*1+1;
		$("#changenum").attr("value",changenum);
	}
 	jQuery.fn.selectCity = function(targetId) 
	{
		var _seft = this;
		var targetId = $(targetId);
	
		this.click(function()
		{
			var A_top = $(this).offset().top + $(this).outerHeight(true);  //  1
			var A_left =  $(this).offset().left;
			targetId.bgiframe();
			targetId.show().css({"position":"absolute","top":A_top+"px" ,"left":A_left+"px"});
		});
	
		targetId.find("#selectItemClose").click(function()
		{
			targetId.hide();
		});
	
		targetId.find("#selectSub :checkbox").click(function()
		{		
			targetId.find(":checkbox").attr("checked",false);
			$(this).attr("checked",true);	
			_seft.val( $(this).val() );
			targetId.hide();
		});
	
		$(document).click(function(event)
		{
			if(event.target.id!=_seft.selector.substring(1))
			{
				targetId.hide();	
			}
		});
	
		targetId.click(function(e){
			e.stopPropagation(); //  2
		});
	
		return this;
	}
	 
	$(function()
	{
		$("#name").selectCity("#selectItem");
		$("#cxname").selectCity("#cxselectItem");
		$("#qcxname").selectCity("#qcxselectItem");
	});