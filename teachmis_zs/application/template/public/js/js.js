// JavaScript Document

//导航菜单下拉效果
function dropMenu(obj){
	
	$(obj).each(function(){
		var theSpan = $(this);
		var theMenu = theSpan.find(".submenu");
		var tarHeight = theMenu.height();
		theMenu.css({height:0,opacity:0});
		theSpan.hover(
			function(){
				$(this).addClass("selected");
				theMenu.stop().show().animate({height:tarHeight,opacity:1},400);
			},
			function(){
				$(this).removeClass("selected");
				theMenu.stop().animate({height:0,opacity:0},400,function(){
					$(this).css({display:"none"});
				});
			}
		);
	});
}

//左侧导航 A 链接颜色
$(document).ready(function(){
	$(".ald_subnav_warp").mouseover(function(){
		$(this).children("a").css("color","#FFF");
	})
	
	$(".ald_subnav_warp").mouseout(function(){
		$(this).children("a").css("color","#444");
	})
})


/** ------------- 从业年限加减 -------------------- **/
/* reduce_add */
var setAmount = {
    min:1,
    max:50,
    reg:function(x) {
        return new RegExp("^[1-9]\\d*$").test(x);
    },
    amount:function(obj, mode) {
        var x = $(obj).val();
        if (this.reg(x)) {
            if (mode) {
                x++;
            } else {
                x--;
            }
        } else {
            alert("请输入正确的从业年限！");
            $(obj).val(1);
            $(obj).focus();
        }
        return x;
    },
    reduce:function(obj) {
        var x = this.amount(obj, false);
        if (x >= this.min) {
            $(obj).val(x);
        } else {
            alert("从业年限最少为" + this.min);
            $(obj).val(1);
            $(obj).focus();
        }
    },
    add:function(obj) {
        var x = this.amount(obj, true);
        if (x <= this.max) {
            $(obj).val(x);
        } else {
            alert("从业年限最多为" + this.max);
            $(obj).val(50);
            $(obj).focus();
        }
    },
    modify:function(obj) {
        var x = $(obj).val();
        if (!this.reg(x)) {
            alert("请输入正确的从业年限！");
            $(obj).val(1);
            $(obj).focus();
        }else if(x > this.max){
			alert("从业年限最多为" + this.max);
            $(obj).val(50);
            $(obj).focus();
			}
    }
}





// JavaScript Document