//页面初始化
jQuery(document).ready(function(){
	Utils.placeholder(document.getElementById('glb_keyword'),'#ffffff');
	bindGoodsFunc();
	jQuery('.cart-wrapper').click(function(event){
		var uid = parseInt(is_login);
		
		if(uid==0||uid==''){
			showLoginPopWindow();
			return;
		}
	});
});

//隐藏导航下拉列表
function navListHide(listName){
	jQuery("#"+listName+"Container").hide();
}

//显示导航下拉列表
function navListShow(listName){
	jQuery("#"+listName+"Container").show();
}

//搜索函数
function navSearch(type){
	var keywords = jQuery.trim(jQuery("#glb_keyword").val()); //关键词
	if(keywords==''||keywords=='search...'){
		showMessageNote('请输入搜索内容！');
		return;
	}
	var url = '';//搜索内容链接
	if(type=="qz"){
		url = 'search/quanzi';
	}else if(type=="dr"){
		url = 'search/daren';
	}else{
		url = 'search/';
	}
	window.location.href='/'+base_script+'/'+url+"?keywords="+encodeURI(keywords);
	
}
function search(type){
	var keywords = jQuery.trim(jQuery("#glb_keyword").val()); //关键词
	if(keywords==''||keywords=='search...'){
		showMessageNote('请输入搜索内容！');
		return;
	}
	var url = '';//搜索内容链接
	if(type=="qz"){
		url = 'search/quanzi'
	}else if(type=="dr"){
		url = 'search/daren'
	}else{
		url = 'search/'
	}
	window.location.href='/'+base_script+'/'+url+"?keywords="+encodeURI(keywords);
}

//搜索回车监控
function enterSearchLister(e){
	var ent = window.event?window.event:e;
	if(ent.keyCode==13) {
		search();
	}else{
		var keywords = jQuery.trim(jQuery("#glb_keyword").val()); //关键词
		if(keywords==''||keywords=='search...'){
			jQuery("#glb_keyword").parent().removeClass('nav_search01');
			jQuery("#glb_keyword").parent().addClass('nav_search');
		}else{
			jQuery("#glb_keyword").parent().removeClass('nav_search');
			jQuery("#glb_keyword").parent().addClass('nav_search01');
		}
	}
}

//显示商品功能按钮,绑定事件
function bindGoodsFunc(){
	//显示商品功能操作按钮
	jQuery(".goodsItem .bindEvent,.goodsItem244 .bindEvent").die().live('mousemove',function(){
		jQuery(this).find('div.goodsFuncCon').show();
		//jQuery(this).prev('div.goodInfo').find('.goodsDetail a.favcount').addClass('favChked');
	}).live('mouseout',function(){
		jQuery(this).find('div.goodsFuncCon').hide();
		//jQuery(this).prev('div.goodInfo').find('.goodsDetail a.favcount').removeClass('favChked');
	});
	
	//添加商品收藏事件
	jQuery(".goodsItem .goodInfo .favcount,.goodsItem244 .goodInfo .favcount").die().live('click',goodsAddFavour);
	//添加商品评论事件
	jQuery(".goodsItem .bindEvent .goodsFunc .msgIcon,.goodsItem244 .bindEvent .goodsFunc .msgIcon").die().live('click',goodsAddMsg);
	//添加商品转发事件
	jQuery(".goodsItem .bindEvent .goodsFunc .sendIcon,.goodsItem244 .bindEvent .goodsFunc .sendIcon").die().live('click',goodsSend);
	//添加商品添加购物车事件
	//jQuery(".goodsItem .bindEvent .goodsFunc .buyIcon,.goodsItem244 .bindEvent .goodsFunc .buyIcon").die().live('click',goodsBuy);
	//添加删除商品事件
	jQuery(".goodsItem .bindEvent .goodsFunc .deleteIcon,.goodsItem244 .bindEvent .goodsFunc .deleteIcon,#goodsListCon .deleteIcon").die().live('click',deleteGoods);
	// 添加点击购物车未登录弹出框
	jQuery('#enterShopingBox').click(function(event){
			
			var uid = parseInt(is_login);
			if(uid==0||uid==''){
				showLoginPopWindow();
				return;
			}else{
				window.location.href='/'+base_script+'/'+'shoppingcar';
			}
		});
	
	jQuery('#shoppingContainerCon dl').click(function(event){
		//console.log(123);
		event.preventDefault();
		event.stopPropagation();
	});
	//绑定关注事件
	jQuery(".followBtn52,.followBtn63").die().live('click',userAddFollow);
	jQuery(".unfollowBtn").die().live('click',userRemoveFollow);
	
}

//商品添加喜欢（收藏）
function goodsAddFavour(e,domObj){
	var curObj = (domObj==undefined)?this:domObj;
	var goods_id = jQuery(curObj).attr('goodsid');
	var goods_uid = jQuery(curObj).attr('goods_uid'); //商品的用户ID
	var uid = parseInt(is_login);
	if(uid==0||uid==''){
		showLoginPopWindow();
		return;
	}else if(goods_uid==uid){
		showMessageNote("这是您自己的良品哦，不能这么操作哦！");
		return;
	}
	jQuery.getJSON('/'+base_script+'/goods/?act=addFavour&goods_id='+goods_id,function(json){
		if(json.rs=='true'){
			if(domObj!=undefined){ //商品详情页面的收藏按钮
				if(json.type=='add'){
					jQuery(curObj).removeClass().addClass('goodsFavCountChk')
				}else if(json.type=='remove'){
					jQuery(curObj).removeClass().addClass('goodsFavCount')
				}
				jQuery(curObj).html(json.favour_count);
			}else{
				if(json.type=='add'){
					jQuery(curObj).addClass('favChked')
				}else if(json.type=='remove'){
					jQuery(curObj).removeClass('favChked')
				}
				jQuery(curObj).html(json.favour_count);
			}
		}else{
			showMessageNote(json.msg);//alert(json.msg)
		}
	});
}


function deleteGoods(e,domObj){
	var curObj = (domObj==undefined)?this:domObj;
	var uid = parseInt(is_login);
	if(uid==0||uid==''){
		showLoginPopWindow();
		return;
	}
	var goods_id = jQuery(curObj).attr('goodsid');
	
	if(confirm('确认删除此商品吗？')==false)  return;
	jQuery.getJSON('/'+base_script+'/goods/?act=delGoods&goods_id='+goods_id,function(json){
		if(json.rs=='true'){
			if(domObj!=undefined){ //商品详情页面的收藏按钮
				window.location.href='/'+base_script+'/home/';
			}else{
				 jQuery(curObj).parent().parent().parent().parent().remove();
				 window.location.reload();
			}
		}else{
			showMessageNote(json.msg);//alert(json.msg)
		}
	});
}


//添加商品评论
function goodsAddMsg(e,domObj){
	var goods_id = jQuery(this).attr('goodsid');
	var uid = parseInt(is_login);
	//alert('hello msg')
}

//转发
function goodsSend(e,domObj){
	var curObj = (domObj==undefined)?this:domObj;
    
    //HACK(2014/2/9:zakk): 情人节专题分享
    var text = jQuery(curObj).attr('to_share_text'); 
	
    var goods_id = jQuery(curObj).attr('goodsid');
	var goods_name = jQuery(curObj).attr('goodsname');
	var goods_img = jQuery(curObj).attr('goodsimg');

    //HACK(2014/2/9:zakk): 情人节专题分享
    if (text){
        showPostToWin('', goods_img, 'http://www.iliangcang.com/i/topic/20140214', text);
    }
    else{
        var url="http://www.iliangcang.com/"+base_script+"/goods/?id="+goods_id;
        showPostToWin(goods_name,goods_img,url);
    }
}

//添加购物车
function goodsBuy(){
	var goods_id = jQuery(this).attr('goodsid');
	var uid = parseInt(is_login);
	//alert('hello goods '+goods_id)
}


//添加关注
function userAddFollow()
{
	var type = jQuery(this).attr("f-type"); //关注类型：user->用户，quanzi->圈子
	var target_id = parseInt( jQuery(this).attr("f-id") );//要关注ID
	var uid = parseInt(is_login); //当前用户的ID
	var curObj = this;
	
	if(uid==0||uid==''){
		showLoginPopWindow();
		return;
	}else if(target_id==uid){
		if(type=="user"){
			showMessageNote("这是您自己哦，不能这么操作哦！");
		}else if(type=="quanzi"){
			showMessageNote("这是您自己的圈子，不能这么操作哦！");
		}
		return;
	}
	jQuery.getJSON('/'+base_script+'/daren/?act=addFollow&id='+target_id+"&type="+type,function(json){
		if(json.rs=='true'){
			jQuery(curObj).removeClass().addClass("unfollowBtn").die().live('click',userRemoveFollow);
		}else{
			showMessageNote(json.msg);
		}
	});
}

//取消关注
function userRemoveFollow(){
	var type = jQuery(this).attr("f-type"); //关注类型：user->用户，quanzi->圈子
	var target_id = parseInt( jQuery(this).attr("f-id") );//要关注ID
	var uid = parseInt(is_login); //当前用户的ID
	var curObj = this;
	
	if(uid==0||uid==''){
		showLoginPopWindow();
		return;
	}else if(target_id==uid){
		if(type=="user"){
			showMessageNote("这是您自己哦，不能这么操作哦！");
		}else if(type=="quanzi"){
			showMessageNote("这是您自己的圈子，不能这么操作哦！");
		}
		return;
	}
	jQuery.getJSON('/'+base_script+'/daren/?act=removeFollow&id='+target_id+"&type="+type,function(json){
		if(json.rs=='true'){
			jQuery(curObj).removeClass().addClass("followBtn52").die().live('click',userAddFollow);
		}else{
			showMessageNote(json.msg);
		}
	});
}

/*/分页函数
function pager(handle,currentPage,totalPages,totalCount)
{
		 var htmlData = "";
		 //alert(currentPage)
	     htmlData += ' 共<b>'+totalCount+'</b>条记录 第<b>'+currentPage+'</b>页/共<b>'+totalPages+'</b>页  &nbsp;&nbsp;';
	     if(currentPage==1)
	     {
	    	 
	     }else{
	    	 htmlData += ' <input type="button"  class="pre" onclick="'+handle+'('+(currentPage-1)+');"/>  ';
	     }
	     //分页条目
	     var allPages = totalPages;	//总共的页数
	     var pageItem = 15;							//每页显示的数目
	     var start = 1;								//起始页码
	     var end = 0;								//终navSearch止页码 currentPage
	     if(allPages<=pageItem){
	     	start = 1;
	     	end = allPages;
	     	
	     }else if( currentPage < Math.ceil(pageItem/2) ){
	     	start = 1
	     	end = pageItem;
	     }else if( (allPages-currentPage) <= pageItem ){
	     	
	     	start = allPages-pageItem;
	     	end = allPages;
	     }else{
	     	start = currentPage-Math.floor(pageItem/2);
	     	end = pageItem+currentPage-1;
	     }
	     if(allPages!=1){ //如果只有一页
	    	 for(var i =start ; i<=end;i++)
		     {
		     	if(i==currentPage)
		     	{
		     		 htmlData += '<a href="#" class="checked">'+i+'</a> ';
		     	}else{
		     		 htmlData += '<a href="javascript:'+handle+'('+i+');">'+i+'</a> ';
		     	}
		     }
	     }
	    
	     if(totalPages==currentPage)
	     {
	  
	     }else{
	    	 htmlData += ' <input type="button"  class="next" onclick="'+handle+'('+(currentPage+1)+');"/>  ';
	     }
	     return htmlData;
}*/

//商品左右移动操作
function pagesMoveLR(){
	
	var curPage = parseInt(jQuery(this).attr('page')); //当前页
	var pageType = jQuery(this).attr('type'); //分页类型：：L->向前，R－>向后
	var blank = parseInt(jQuery(this).nextAll('.allGoodsList').attr('thisBlk'));  //图片间隔
	var count = parseInt(jQuery(this).nextAll('.allGoodsList').attr('count'));  //总的图片数目
	var perW = parseInt(jQuery(this).nextAll('.allGoodsList').attr('perW'));  //每个模块的宽度
	var pageW= parseInt(jQuery(this).nextAll('.allGoodsList').attr('thisW'));  //每页的宽度
	var pageCount = parseInt(pageW/perW); //每页显示数目
	var pages  = Math.ceil(count/pageCount); //总的页数
	var allW = count>0?(count*perW+blank*(count-1)):0;
	//alert(count)
	jQuery(this).nextAll('.allGoodsList').width(allW);
	if(pages<=1) return;
	var perMove = (pageCount*perW+blank*(pageCount));
	if(pageType=='L'&&curPage>0){//向前翻页
		jQuery(this).attr('page',curPage-1);
		jQuery(this).next().attr('page',parseInt(curPage));
	}else if(pageType=='R'&&curPage<pages-1){ //向后翻页
		jQuery(this).attr('page',curPage+1);
		jQuery(this).prev().attr('page',parseInt(curPage));
	}
	jQuery(this).nextAll('.allGoodsList').animate({left:-(curPage*perMove)+"px"},1000);
}

//分页条转
function gotoPage(e,obj,pages,perPage,url,other){
	//alert(url+other)
	var pageNum = jQuery(obj).prev().val();
	if(!Utils.isInt(pageNum)||pageNum>pages) {
		alert("请输入正确的页码");
		return;
	}
	var start = perPage*(pageNum-1);
	window.location.href=url+"&offset="+start+other;
}
//分页跳转监听
function goPageListener(e,obj){
	var ent = window.event?window.event:e;
	if(ent.keyCode==13){
		jQuery(obj).next().click();
	}
}


//添加层蒙版
function addDivLayer()
{
	if(jQuery('#sysMessageWin').height()){
		return false;
	}
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var html = "";
	var style = 'width:'+winW+'px;height:'+winH+'px;';
	html = '<div class="divLayer" style="'+style+'"></div>';
	jQuery('body').append(html);
}
//移出蒙版
function removeDivLayer(){
	jQuery('.divLayer').remove();
}

//系统消息弹出窗<!-- 消息弹窗--> 
function showMessageNote(msg){
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery(window).scrollTop();
	var left = (winW-433)/2;
	var top = scrlTop;
	if(jQuery('#sysMessageWin').height()){
		top = (winH-parseInt(jQuery("#sysMessageWin").height()))/2
		jQuery("#sysMessageWin").css({"top":top+'px'});
		return false;
	}
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="sysMessageWin" style="'+style+'">';
	html += '<div id="countdown-hide" style="display:none;"></div>';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#sysMessageWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<div class="lcMsgNote">';
	if(msg!=undefined)html += msg;
	html += '</div>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
	top = (winH-parseInt(jQuery("#sysMessageWin").height()))/2
	jQuery("#sysMessageWin").css({"top":top+'px'}).show();
	
	//3秒后自动关闭
	var shortly = new Date(); 
	shortly.setSeconds(shortly.getSeconds() + 2.9); 
	jQuery("#countdown-hide").countdown({until: shortly,onExpiry: function(){jQuery('#sysMessageWin').remove();removeDivLayer();}, onTick: function(){}});
//	jQuery("#countdown-hide").countdown('pause');
//	setTimeout(function(){
//		jQuery("#countdown-hide").countdown('resume');						
//	},800);
}

//私信提示
function sixinMsgNoteShow(){
	jQuery('#sixinMsgNote').show();
	jQuery('.divLayer').show();
}

//弹出窗口<!-- 发送私信弹窗-->
function showSendMsgWin(username,user_id){
	if(user_id!="1640"&&(is_login==0||is_login=='')){
		showLoginPopWindow();
		return;
	}

	if(user_id=="1640"){
		//这个依赖第三方

		//doyoo.util.openChat('g=58308');
		//mechatClick();
		//sixinMsgNoteShow();
		//return false;
	}

	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-433)/2;
	var top = (winH-351)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="sysSendMsgWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#sysSendMsgWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content" >'; //onclick="jQuery(\'#sxUserSearchList\').hide();">';
	html += '<form id="popSendMsgForm">';
	html += '<table class="regTable" >';
	html += '<tr valign="middle"  height="50">';
	html += '<th align="left" width="70">发送私信给</th>';
	html += '<td align="left" width="270" style="position: relative;">';
	html += '<input type="text" class="inpt" name="username" id="s_username"  onkeyup="getUserList();" value="'+(username?username:'')+'"  placeholder="用户昵称"/>';
	html += '<input type="hidden"  id="s_selected_user_id" value="'+(user_id?user_id:'')+'" >';
	html += '<div class="sxUserSearchList" id="sxUserSearchList">';
	html += '<ul>';
	html += '</ul>';
	html += '</div>';
	html += '</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="150">';
	html += '<th align="left" width="70" valign="top">内容</th>';
	html += '<td align="left" width=""><textarea class="txtArea" id="s_conent" onfocus="jQuery(\'#sxUserSearchList\').hide();"></textarea></td>';
	html += '</tr>';
	html += '<tr valign="center" align="middle" height="50">';
	html += '<th align="right" colspan="2" > <input class="confirmBtn" id="sendMsgBtn"  type="button" onclick="sendMessage();"  align="absmiddle" style="margin-right:15px;"/></th>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
}

//取得私信用户列表
function getUserList(is_daren)
{
	var keyword = jQuery.trim( jQuery("#s_username").val() );
	if(keyword=='') {
		jQuery("#sxUserSearchList").hide();
		return;
	}
	var curObj = this;
	var html = "";
	jQuery('#s_selected_user_id').val('');
	var queryStr = '';
	if(is_daren!=undefined){//是否是达人
		queryStr = '&is_daren='+is_daren;
	}
	jQuery.getJSON('/'+base_script+'/usermain/?act=getUserList&keyword='+keyword+queryStr,function(json){
		
		if(json!=-1){
			for(n in json){
				html += '<li onclick="selectUser(this);" uid="'+json[n].uid+'" uname="'+json[n].username+'">'+(json[n].nickname?json[n].nickname:json[n].username)+'('+json[n].username+')</li>';
			}
			jQuery("#sxUserSearchList").show();
			jQuery("#sxUserSearchList ul").html(html);
		}else{
			jQuery("#sxUserSearchList").hide();
		}
		
	});
}
function selectUser(obj){
	jQuery('#s_username').val(obj.innerHTML);
	jQuery('#s_selected_user_id').val(obj.getAttribute('uid'));
	jQuery("#sxUserSearchList").hide();
}

//发送私信
function sendMessage(){
	var uid = jQuery.trim(jQuery("#s_selected_user_id").val()); //是否选中了用户
	var content = jQuery.trim(jQuery("#s_conent").val());
	if(uid==''){
		showMessageNote("请输入您要发送的用户！");
		return;
	}
	jQuery('#sendBtn52').attr('disabled',true);
	if(content==''){
		alert("请输入私信内容，私信内容不能为空！");
		return;
	}
	jQuery.getJSON(encodeURI('/'+base_script+'/usermain/?act=sendMsg&uid='+uid+'&content='+content),function(json){
		if(json.rs=='true') {
			jQuery('#sysSendMsgWin').remove();
		}
		showMessageNote(json.msg);
		jQuery('#sendBtn52').attr('disabled',false);
	});
}

 
//弹出窗口<!-- 发送私信弹窗--> 
function showReplyMsgWin(to_uid,username){
	if(is_login==0||is_login==''){
		showLoginPopWindow();
		return;
	}
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-433)/2;
	var top = (winH-351)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="sysReplyWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#sysReplyWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<form id="popReplyMsgForm">';
	html += '<table class="regTable" >';
	html += '<tr valign="middle"  height="50">';
	html += '<td align="left" width="">';
	html += '<div class="replyCon"><span style="display:inline-block;">回复给'+username+'：</span><input type="text" class="inpt" name="replymsg" align="absmiddle" id="replymsg"  placeholder=""/></div>';
	html += ' <input type="hidden" name="r_to_uid" value="'+to_uid+'" />';
	html += '</td>';
	html += '</tr>';
	html += '<tr valign="center" align="middle" height="50">';
	html += '<th align="right"  > <input class="confirmBtn" id="sxReplyBtn"  type="button" onclick="submitReplyMsg('+to_uid+');"  align="absmiddle" style="margin-right:15px;"/></th>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
	var nameW = jQuery("#sysReplyWin .replyCon span").width();
	jQuery("#replymsg").width(350-nameW-2);
}

//提交私信回复
function submitReplyMsg(to_uid,type) //type表示通过页面提交
{
	var content = jQuery.trim( jQuery("#replymsg").val() );
	if(content==''){
		if(type){showMessageNote("请输入回复内容，回复内容不能为空！");}else{alert("请输入回复内容，回复内容不能为空！");}
		return;
	}
	jQuery("#sxReplyBtn").attr('disabled',true);
	jQuery.getJSON(encodeURI('/'+base_script+'/usermain/?act=replyMsg&id='+to_uid+'&content='+content),function(json){
		if(json.rs=='true') {
			jQuery('#sysReplyWin').remove();
		}
		showMessageNote(json.msg);
		if(type){
			var html = "";
			html += '<li class="me">';
			html += '<div class="msgMain">';
			html += '<div class="top"></div>';
			html += '<div class="right">';
			html += '<div class="content">';
			html += '<p>我：'+json.data.content+'</p>';
			html += '<p>'+json.data.add_time+'</p>';
			html += '</div>';
			html += '</div>';
			html += '<div class="bottom"></div>';
			html += '</div>';
			html += '<div class="headImg"><a href="/'+base_script+'/usermain/?id='+json.data.userInfo.uid+'"><img src="'+json.data.userInfo.user_head_big+'" /></a></div>';
			html += '</li>';
			jQuery("#sxList").prepend(html);
			jQuery("#replymsg").val('');
		}else{
			window.location.reload();
		}
		jQuery("#sxReplyBtn").attr('disabled',false);
	});
}
 

//弹出窗口<!-- 回复评论弹窗--> 
function showReplyCommentMsgWin(goods_id,pid,puid,username){
	if(is_login==0||is_login==''){
		showLoginPopWindow();
		return;
	}
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-433)/2;
	var top = (winH-351)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="commentReplyWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#commentReplyWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<form id="popReplyMsgForm">';
	html += '<table class="regTable" >';
	html += '<tr valign="middle"  height="50">';
	html += '<td align="left" width="">';
	html += '<div class="replyCon"><span style="display:inline-block;">回复给'+username+'：</span><input type="text" class="inpt" name="commreplymsg" align="absmiddle" id="commreplymsg"  placeholder=""/></div>';
	html += ' <input type="hidden" name="rc_to_uid" value="'+puid+'" />';
	html += '</td>';
	html += '</tr>';
	html += '<tr valign="center" align="middle" height="50">';
	html += '<th align="right"  > <input class="confirmBtn"  type="button" onclick="submitReplyComment('+goods_id+','+pid+','+puid+');"  align="absmiddle" style="margin-right:15px;"/></th>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
	var nameW = jQuery("#commentReplyWin .replyCon span").width();
	jQuery("#commreplymsg").width(350-nameW-2);
}
//提交回复评论
function submitReplyComment(goods_id,pid,puid) //type表示通过页面提交
{
	var content = jQuery.trim( jQuery("#commreplymsg").val() );
	if(content==''){
		alert("请输入回复内容，回复内容不能为空！");
		return;
	}
	jQuery.getJSON(encodeURI('/'+base_script+'/goods/?act=addComment&goods_id='+goods_id+'&content='+content+'&pid='+pid+"&puid="+puid),function(json){
		if(json.rs=='true') {
			jQuery('#commentReplyWin').remove();
			removeDivLayer();
		}else{
			alert('回复失败');
		}
	});
}


//
//系统消息弹出窗<!-- 登录弹窗口--> 
function logBlock(){
	jQuery("#regCont").addClass("hide");
	jQuery("#logCont").removeClass("hide");
}
function regBlock(){
	getnewimg();
	jQuery("#regCont").removeClass("hide");
	jQuery("#logCont").addClass("hide");
}
function showLoginPopWindow(){
	//addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-652)/2;
	var top = (winH-323)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;_position:absolute;";
	var html = "";
	html += '<div style="width:100%;height:100%;" class="divLayer" onclick="jQuery(\'.popWindow\').remove();removeDivLayer();"></div>';
	html += '<div class="popWindow" id="loginPopWin" style="'+style+'">';
	html += '<div id="logCont">';
	html += '<form id="popLoginForm">';
	html += '<div class="top">登录良仓<p class="closed" title="关闭" onclick="jQuery(\'.popWindow\').remove();removeDivLayer();"></p></div>';
	html += '<div class="fl leftRig">';
	html += '<div class="itemLog"><label for="username">手机号/邮箱/用户名</label><input class="inputsr" type="text" name="username" id="p-uname" /></div>';
	html += '<div class="itemLog"><label for="p-pwd">密码</label><input class="inputsr" type="password" name="password" id="p-pwd" /></div>';
	html += '<div class="textsm">';
	html += '<div class="fl"><input type="checkbox" class="vertPos" /><label class="vertPos padl6">7天内自动登录</label></div>';
	html += '<div class="fr"><a href="/'+base_script+'/ufindpwd/" class="colfff vertPos">忘记密码？</a></div>';
	html += '<p class="clr"></p>';
	html += '</div>';
	html += '<input type="button" class="inp_butt" value="登录良仓" class="loginBtn" onclick="uLogin();"/>';
	html += '<a class="menu" href="javascript:;" onclick="regBlock()">注册良仓</a>';
	html += '</div>';
	html += '<div class="fr leftRig">';
	html += '<div class="thirdParty">';
	html += '<p class="toptit">第三方登录：</p>';
	html += '<a class="qqwb" href="/'+base_script+'/oauth/tqq/"></a>';
	html += '<a class="QQ" href="/'+base_script+'/oauth/qq/"></a>';
	html += '<a class="weibo" href="/'+base_script+'/oauth/weibo/"></a>';
	html += '<a class="douban" href="/'+base_script+'/oauth/douban/"></a>';
	html += '<p class="clr"></p>';
	html += '</div>';
	html += '</div>';
	html += '<p class="clr"></p>';
	html += '</form>';
	html += '</div>';
	html += '<div id="regCont" class="hide">';
	html += '<form id="uregPopForm"><input type="hidden" name="act" id="act"  value="useradd"/><input type="hidden" name="sex" value="0" /><input type="hidden" name="invite_uid" value="0" />';
	html += '<div class="top">注册良仓<p class="closed" title="关闭" onclick="jQuery(\'.popWindow\').remove();removeDivLayer();"></p></div>';
	html += '<div class="fl leftRig">';
	html += '<p class="textsm">手机号</p>';
	html += '<div class="itemLog"><label for="mobile_phone">请输入手机号</label><input type="text" class="inputsr" id="mobile_phone" name="mobile_phone" autocomplete="off" value=" " onchange="ShowAuthCode();" /></div>';
	html += '<p class="textsm">创建密码</p>';
	html += '<div class="itemLog"><label for="pwd">6－16位字符组成，区分大小写</label><input type="password" class="inputsr" id="pwd" name="pwd" autocomplete="off" onkeyup="pwStrength(this.value);" /></div>';
	html += '<div class="hei44"><div id="pwdInfo"><div id="pwdStrong" class="content"></div><div class="info"><span class="passLeft">弱</span><span>中</span><span class="passRight">强</span></div></div></div>';
	
	html += '<div class="textsm mrt33">';
	html += '<input type="checkbox" class="vertPos" id="ourtreaty" checked="checked"><label class="vertPos padl6" for="ourtreaty">同意</label><a target="_blank" class="vertPos col0c7" href="http://www.iliangcang.com/i/our/service">&nbsp;良仓注册条款</a>';
	html += '</div>';
	html += '<input type="button" class="inp_butt" value="注册良仓" onclick="regCheck(\'passive\');" />';
	html += '<a class="menu" href="javascript:;" onclick="logBlock()">登录良仓</a>';
	html += '</div>';
	html += '<div class="fr leftRig">';
	
	html += '<div id="authcodeDiv">';
	html += '<p class="textsm">验证码</p>';
	html += '<div class="itemLog"><input type="text" class="regInptCode innerPro inputsr"  name="authcode" id="authcode" maxlength="4" oninput="HideAuthCode(this)" onpropertychange="HideAuthCode(this)" />';
	html += '<img src="'+base_script+'/images/default/authcode.png" id="authimg"  align="absmiddle" /><a class="nextVert" onclick="getnewimg();" href="javascript:;">看不清，换一张</a></div>';
	html += '</div>';
	
	html += '<div style="display:none;" id="PhoneCodeDiv" >';
	html += '<p class="textsm">手机验证码</p>';
	html += '<div class="itemLog"><input type="text" name="chkPhoneCode" id="chkPhoneCode" class="regInptCode innerPro inputsr" style="width:126px;" autocomplete="off" value="" />';
	html += '<input type="button" class="mobile_button"  id="mobile_button" onclick="getMobileAuthCode(\'reg\',this);" value="免费获取验证码" /></div>';
	html += '</div>';
	
	
	html += '<p class="textsm">密码确认</p>';
	html += '<div class="itemLog"><label for="repwd">密码确认</label><input type="password" class="inputsr" id="repwd" name="repwd" autocomplete="off" /></div>';
	html += '<div class="thirdParty">';
	html += '<p class="toptit">第三方登录：</p>';
	html += '<a class="qqwb" href="/'+base_script+'/oauth/tqq/"></a>';
	html += '<a class="QQ" href="/'+base_script+'/oauth/qq/"></a>';
	html += '<a class="weibo" href="/'+base_script+'/oauth/weibo/"></a>';
	html += '<a class="douban" href="/'+base_script+'/oauth/douban/"></a>';
	html += '<p class="clr"></p>';
	html += '</div>';
	html += '</div>';
	html += '<p class="clr"></p>';
	html += '</form>';
	html += '</div>';
	html += '</div>';
	jQuery('body').append(html);
	getnewimg();
	jQuery('#mobile_phone').focus(function(){
    	jQuery(this).prev('label').text('');
    }).blur(function(){
    	if(jQuery.trim(this.value)==''){
    		jQuery(this).prev('label').text('请输入手机号');
    	}
    });
 	jQuery('#pwd').focus(function(){
    	jQuery(this).prev('label').text('');
    }).blur(function(){
    	if(this.value==''){
    		jQuery(this).prev('label').text('6－16位字符组成，区分大小写');
    	}
    });
    jQuery('#repwd').focus(function(){
		    	jQuery(this).prev('label').text('');
		    }).blur(function(){
		    	if(jQuery.trim(this.value)==''){
		    		jQuery(this).prev('label').text('密码确认');
		    	}
	});
//	jQuery('#username').focus(function(){
//		 jQuery(this).prev('label').text('');
//	}).blur(function(){
//		if(this.value==''){
//		  jQuery(this).prev('label').text('20个字符内');
//		}
//	});
	jQuery('.itemLog label').click(function(){
		jQuery(this).next('input').focus();
	})
	setTimeout(function(){
		if(jQuery('#p-uname').val()!='' && jQuery('#p-pwd').val()!=''){
			jQuery('#p-uname').prev('label').text('');
			jQuery('#p-pwd').prev('label').text('');
		}
		jQuery('#p-uname').focus(function(){
	    	jQuery(this).prev('label').text('');
	    }).blur(function(){
	    	if(jQuery.trim(this.value)==''){
	    		jQuery(this).prev('label').text('用户名/邮箱/手机号');
	    	}
	    });
	 	jQuery('#p-pwd').focus(function(){
	    	jQuery(this).prev('label').text('');
	    }).blur(function(){
	    	if(this.value==''){
	    		jQuery(this).prev('label').text('密码');
	    	}
	    });
	},100)
}

//窗口登录
function uLogin(){
	var loginFlag = false;
	jQuery.ajaxSetup({async:false});
	var username = jQuery("#p-uname").val();
	var password = jQuery("#p-pwd").val();
	if(username==''){
		showMessageNote("请输入您的登录名！");
		return;
	}
	if(password==''){
		showMessageNote("请输入您的登录密码！");
		return;
	}
	jQuery.post("/"+base_script+"/login/",{act:'chklogin',username:username,password:password},function(json){
		if(json.rs==1) loginFlag= true;
	},'json');
	
	if(loginFlag==true){
		 jQuery.post("/"+base_script+"/home/",{username:username,password:password},function(rs){
			    window.location.reload();
		 });
	}else{
		showMessageNote("登录失败，用户名或密码错误！");
		return;
	}
}


//
//系统消息弹出窗<!-- 注册弹窗--> 
function showRegPopWindow(){
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-776)/2;
	var top = (winH-403)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="loginPopWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'.popWindow\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<form id="popUregForm">';
	html += '<table class="regTable" >';
	html += '<tr valign="middle"  height="50">';
	html += '<th align="left" width="70">我的邮箱</th>';
	html += '<td align="left" width="270"><input type="text" class="regInpt" name="loginname" id="loginname"  placeholder="请输入常用邮箱"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="50">';
	html += '<th align="left" width="70">创建密码</th>';
	html += '<td align="left" width=""><input type="text" class="regInpt" name="pwd" id="pwd" placeholder="6-16位字符组成，区分大小写" /></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="100">';
	html += '<th align="left" width="70">密码确认</th>';
	html += '<td align="left" width="">';
	html += '<input type="text" class="regInpt" name="repwd" id="repwd" />';
	html += '<div id="pwdInfo" style="margin:10px 0px">';
	html += '	<div class="content high"></div>';
	html += '<div class="info">';
	html += '<span>弱</span>';
	html += '<span>中</span>';
	html += '<span>强</span>';
	html += '</div>';
	html += '</div>';
	html += '</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="50">';
	html += '<th align="left" width="70">昵称</th>';
	html += '<td align="left" width=""><input type="text" class="regInpt" name="nickname" id="nickname" placeholder="4-15位字符，包含英文、数字和中文"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="50">';
	html += '<th align="left" width="70">验证码</th>';
	html += '<td align="left" width=""><input type="text" class="regInptCode" name="authcode" id="authcode" />';
	html += '<img src="images/default/authcode.png" id="authcode"  align="absmiddle" />';
	html += '</td>';
	html += '<td align="left"> <a href="#" >换一张</a> </td>';
	html += '</tr>';
	html += '<tr valign="center" align="middle" height="50">';
	html += '<th align="left" width="70"> <input class="regBtn"  type="button"  align="absmiddle"/></th>';
	html += '<td align="left" width="">';
	html += '<input  type="checkbox"  />同意<a href="#"style="color:#5B6E92;">良仓注册条款</a>';
	html += '</td>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>';
	jQuery('body').append(html);
}


//====新版本登录注册弹出窗口==============================================================
var SysSecond;
var InterValObj;
var PlatForm = "";

//开始倒计时
function startRemainTime() {
	//$('#remainTimeCon').show();
	SysSecond = parseInt(jQuery("#remainSeconds").html()); //这里获取倒计时的起始时间
	jQuery("#getSureCodeBtn").unbind('click').css({background:'#ccc',cursor:'default'}).html("剩余("+SysSecond+"s)");
	InterValObj = window.setInterval(SetRemainTime, 1000); //间隔函数，1秒执行
}

function SetRemainTime(){
	if (SysSecond > 0){
		SysSecond = SysSecond - 1;
		var second = Math.floor(SysSecond % 60);
//		if( second<10){
//			return second =  '0'+second;
//		}
		var minite = Math.floor((SysSecond / 60) % 60);
//		if( parseInt(minite)<10){
//			return minite= '0'+ minite;
//		}
		//$("#getSureCodeBtn").html( minite + ":" + second );
		jQuery("#getSureCodeBtn").html( "剩余("+SysSecond+"s)" );
	} else {
		_func = uGetPhoneCode;
		if(PlatForm=='wap') _func = mobGetPhoneCode;
		jQuery("#getSureCodeBtn").unbind().bind('click',_func).css({background:'#58a0c8',cursor:'pointer'}).html('获取验证码');;
		
		window.clearInterval(InterValObj);
	}
}


//获取手机验证码
function getSMSCode(){ 
	var send_phone_num = jQuery.trim(jQuery("#mobile_phone").val());
	var SMS_Token = jQuery.trim(jQuery("#SMS_Token").val());
	if(send_phone_num==''){
		alert("手机号码不能为空") 
		return false;
	}else if(Utils.isPhone(send_phone_num)==false){
		alert("请输入正确的手机号码") 
		return false;
	}
	jQuery.post('/'+base_script+'/authcode/',{act:"sendSMS",'send_phone_num':send_phone_num,'token':SMS_Token},function(data){
		startRemainTime();
	});
}

//弹出窗口-登录
function showLoginNewWin(){
	jQuery('#registerPopWin').remove()
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-963)/2;
	var top = (winH-314)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;position:fixed;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="landing" id="landPopWin" style="'+style+'">';
	html += '<div class="landingIn " >';
	html += '<p>登录良仓</p>';
	html += '<div class="loginCon clearfix">';
	html += '<div class="l loginMsg">';
	html += '<label>';
	html += '<input class="input" id="p-uname" type="text" placeholder="用户名/邮箱/手机号">';
	html += '</label>';
	html += '<label>';
	html += '<input class="input" id="p-pwd" type="password" placeholder="密码" >';
	html += '</label>';
	html += '<label class="clearfix">';
	html += '<input class="l" type="checkbox"><span>7天内自动登录</span>';
	html += '<a class="r forgetP" href="/'+base_script+'/ufindpwd/" target="_blank">忘记密码?</a>';
	html += '</label>';
	html += '<label class="clearfix">';
	html += '<a class="l landingBtn" href="javascript:void(0)" onclick="uLogin();">登录良仓</a>';
	html += '<a class="r registerBtn" href="javascript:void(0)" onclick="showRegisterNewWin();">注册良仓</a>';
	html += '</label>';
	html += '</div>';
	html += '<div class="l friend">';
	html += '<p>第三方登录</p>';
	html += '<a class="tencen" href="/'+base_script+'/oauth/tqq/"></a>';
	html += '<a class="qq" href="/'+base_script+'/oauth/qq/"></a>';
	html += '<a class="sina" href="/'+base_script+'/oauth/weibo/"></a>';
	html += '<a class="doub" href="/'+base_script+'/oauth/douban/"></a>';
	html += '</div>';
	html += '</div>';
	html += '<div class="close" onclick="jQuery(\'#landPopWin\').remove();removeDivLayer();"><img src="/images/default/newpoplogin/close.png"></div>';
	html += '</div>  ';
	html += '</div>  ';
	jQuery('body').append(html);
}


//弹出窗口--注册
function showRegisterNewWin(){
//	if(is_login==0||is_login==''){
//		showLoginPopWindow();
//		return;
	jQuery('#landPopWin').remove();
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-963)/2;
	var top = (winH-314)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;position:fixed;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="landing register" id="registerPopWin" style="'+style+'">';
	html += '<form method="post" action="/'+base_script+'/ureg/" id="uregPopForm"><div class="landingIn">';
	html += '<p>注册良仓</p>';
	html += '<div class="loginCon clearfix">';
	html += '<div class="l loginMsg">';
	html += '<label>';
	html += '<input class="input" name="mobile_phone" id="mobile_phone" type="text" placeholder="手机号">';
	html += '</label>';
	html += '<label>';
	html += '<input class="input writeCodeInput" name="authcode" id="authcode" type="text" placeholder="输入图形验证吗">';
	html += '<img src="/images/default/authcode.png" width="64" height="28" id="authimg" style="left:0px;" >';
	html += '</label>';
	html += '<label>';
	html += '<input class="input" type="text" name="phoneauthcode" id="phoneauthcode" placeholder="输入您的手机验证码" >';
	html += '</label>';
	html += '<label class="clearfix landingBtnG">';
	html += '<a class="l landingBtn" href="javascript:void(0)" onclick="uRegCheck();">注册良仓</a>';
	html += '<a class="r registerBtn" href="javascript:void(0)" onclick="showLoginNewWin();">登录良仓</a>';
	html += '</label>';
	html += '</div>';
	html += '<div class="l getSureCode">';
	html += '<input class="input checked" name="pwd" id="pwd"  type="password" placeholder="密码">';
	html += '<p><a href="javascript:;" onclick="getnewimg();" >看不清,换一张</a></p>';
	html += '<div class="remainTimeCode">';
	html += '<div id="remainSeconds" >60</div>';
	html += '<div class="time" id="remainTimeCon" >还剩';
	html += '<span id="remainTime">00:00</span>';
	html += '</div>';
	html += '<div class="pastCode">你的验证码已过期,请重新输入验证码</div>';
	html += '</div>';
	html += '<a href="javascript:void(0)" class="getSureCodeBtn" id="getSureCodeBtn" >获取验证码</a>';
	html += '</div>';
	html += '</div>';
	html +='<div class="close" onclick="jQuery(\'#registerPopWin\').remove();removeDivLayer();"><img src="/images/default/newpoplogin/close.png"></div>';
	html += '<input type="hidden" name="act" value="useradd" />';
	html += '<input type="hidden" name="sex" value="0" />';
	html += '<input type="hidden" name="invite_uid" value="0" />';
	html += '</form></div>  ';
	html += '</div>';
	jQuery('body').append(html);
	getnewimg();
	jQuery("#getSureCodeBtn").unbind().bind('click',uGetPhoneCode);
	//startRemainTime();
}

//获取有机验证码
function uGetPhoneCode(){
	//手机号唯一性检测
	if(checkMobilePhone()==false) return false;
	
	//图形验证码测
	if(checkAuthCode()==false) return false;

	//获取手机验证码发送到手机
	getSMSCode();
}

//弹出窗口注册
function uRegCheck(){
	//手机号唯一性检测
	if(checkMobilePhone()==false) return false;
	//手机验证码检测
	if(checkPhoneAuthCode()==false) return false;
	
	var pwd = jQuery.trim(jQuery('#pwd').val());
	if(pwd==''){
		alert('密码不能为空。');
		return false;
	}else if(pwd.length<6||pwd.length>32){
		alert('输入密码需在6位到32位之间。');
		return false;
	}
	document.getElementById("uregPopForm").submit();
	return true;
}



//弹出窗口<!-- 举报弹窗--> 
function reportMsgWin(){
	if(is_login==0||is_login==''){
		showLoginPopWindow();
		return;
	}
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-433)/2;
	var top = (winH-351)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="reportMsgWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#reportMsgWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<form id="reportMsgForm">';
	html += '<table class="regTable" >';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left" width="30"><input type="radio" name="item" value="1"/></td>';
	html += '<td align="left" width="335">广告或垃圾信息</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left" width=""><input type="radio" name="item" value="2"/></td>';
	html += '<td align="left" width="">色情、淫秽、低俗内容</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left" width=""><input type="radio" name="item" value="3"/></td>';
	html += '<td align="left" width="">骚扰或攻击信息</td>';
	html += '</tr>';
	html += '<tr valign="middle"   height="25">';
	html += '<td align="left" width=""><input type="radio" name="item" value="4"/></td>';
	html += '<td align="left" width="">激进时政或意识形态话题</td>';
	html += '</tr>';
	html += '<tr valign="middle"   height="25">';
	html += '<td align="left" width=""><input type="radio" name="item" value="5"/></td>';
	html += '<td align="left" width="">其他原因</td>';
	html += '</tr>';
	html += '<tr valign="center"  align="middle" >';
	html += '<td align="right"  colspan="2" style="position:relative;"><div id="reportConNote" onclick="hideReportNote();">详细情况（请如实填写）</div><textarea id="reportContent" onfocus="hideReportNote();" onblur="showReportNote();"></textarea></td>';
	html += '</tr>';
	html += '<tr valign="center"  align="right" height="30">';
	html += '<td align="right"  colspan=2 > <input class="confirmBtn"  type="button" onclick="doReport();"  align="absmiddle"/></td>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
}


//弹出窗口<!-- 认领商品和店铺窗口--> 
var IS_RL_ED = 0; //是否认领打回
function renlingPopWin(brand_id,renlingid){
	jQuery.ajaxSetup({async:false});
	if(is_login==0||is_login==''){
		showLoginPopWindow();
		addDivLayer();
		return;
	}
	
	//是否是认领打回，重新审核
	var truename = '';
	var mobile = '';
	var duty = '';
	var mail = '';
	var reason = '';
	var note = '';
	var cardImg = '/images/default/headImgTmp239.png';
	var authNote = "";
	if(renlingid!=undefined&&renlingid>0){
		IS_RL_ED = 1;
		jQuery.getJSON("/"+base_script+"/shop/?act=getOneClaim&sbcid="+renlingid,function(json){
			 truename = json.truename;
			 mobile = json.phone;
			 duty = json.duty;
			 mail = json.email;
			 reason = json.reason;
			 note = json.note;
			 cardImg = json.card_img;
			 authNote = "<p style='color:red;font-weight:bold;'><b>提示：</b>"+json.auth_note+"</p>";
		});
	}
	
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-440)/2;
	var top = have_ads>0?400:104;//(winH-351)/2;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="renlingPopWin" style="'+style+'">';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#renlingPopWin\').remove();/*removeDivLayer();*/"></span></div>';
	html += '<div class="content">';
	html += '<div class="l-arrow"></div>';
	html += '<div class="tle">'+authNote+'如果你是此品牌或店铺的相关人员，欢迎认领，享有更多管理功能。</div>';
	html += '<form id="renlingMsgForm"  name="renlingMsgForm" method="post" action="" target="tmpUpFrame">';
	html += '<table class="logTable">';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>真实姓名</p><input  class="loginInpt" id="truename" name="truename" value="'+truename+'"  placeholder="请填写真实姓名，必填项"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>手机</p><input  class="loginInpt" id="mobile" name="mobile"  value="'+mobile+'" placeholder="请填写真实手机号码，手机只会用于认证用途，必填项"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>职位</p><input  class="loginInpt" id="duty" name="duty"  value="'+duty+'" placeholder="比如：品牌经理、产品经理，必填项"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>认领邮箱</p><input  class="loginInpt" id="mail" name="mail"  value="'+mail+'" placeholder="请填写工作邮箱，以便加快资料审核进度，必填项"/></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>认领理由</p><textarea  class="texArea" id="reason" name="reason"  placeholder="请填写认证理由，必填项">'+reason+'</textarea></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><p>备注</p><textarea  class="texArea" id="note" name="note"  placeholder="其它能帮助我们完成认证的信息都可以在此填写， 比如：">'+note+'</textarea></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="120">';
	html += '<td align="left" style="position:relative;"><p>上传名片</p>';
	html += '<img src="'+cardImg+'" id="cardTmpImg" style="margin-top:10px;" onmouseover="showBigCardImg(1)" onmouseout="showBigCardImg(0)"/>';
	html += '<img src="'+cardImg+'" id="bigCardTmpImg" style="margin-top:10px;"/>';
	html += '</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="30">';
	html += '<td align="left">请上传工作证或名片，以便我们确认其真实性</td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="30">';
	html += '<td align="left"><input type="button" class="uploadBtn" onclick="cardPopWin();"  align="absmiddle"/>&nbsp;&nbsp;&nbsp;&nbsp;请上传jpg/png/gif格式文件，文件小于2mb</td>';
	html += '</tr>';
	html += '<tr valign="bottom"  height="45">';
	html += '<td align="left"><input type="hidden" name="sbcid" value="'+renlingid+'"/><input type="hidden" name="brand_id" value="'+brand_id+'"/><input type="hidden" id="is_uploadimg" name="is_uploadimg" value="0"/><input type="hidden" name="act" value="addRenlingMsg"/><input type="button" class="submitBtn"  align="absmiddle" onclick="doRenlingSubmit();"/></td>';
	html += '</tr>';
	html += '</table>';
	html += '</form>';
	html += '</div>';
	//html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
}

function doRenlingSubmit(){
	var rlForm = document.renlingMsgForm;
	var truename = jQuery.trim(rlForm.truename.value);
	var mobile = jQuery.trim(rlForm.mobile.value);
	var duty = jQuery.trim(rlForm.duty.value);
	var mail = jQuery.trim(rlForm.mail.value);
	var reason = jQuery.trim(rlForm.reason.value);
	var note = jQuery.trim(rlForm.note.value);
	var is_uploadimg = jQuery.trim(rlForm.is_uploadimg.value);
	
	if(truename==''){ //真实姓名
		showMessageNote("请输入真实姓名！");
		return false;
	}
	if(mobile==''){ //用户管理
		showMessageNote("请输入您的手机号码！");
		return false;
	}else if(!Utils.isTel(mobile)){
		showMessageNote("手机格式不正确,如：188 8888 8888！");
		return false;
	}
	if(duty==''){ //用户管理
		showMessageNote("请输入职位！");
		return false;
	}
	if(mail==''){ //用户管理
		showMessageNote("请输入认领邮箱！");
		return false;
	}else if(!Utils.isEmail(mail)){
		showMessageNote("邮箱格式不正确，如：exp@iliangcang.com！");
		return false;
	}
	if(reason==''){ //用户管理
		showMessageNote("请输入认领理由！");
		return false;
	}
	if(IS_RL_ED==0&&is_uploadimg==0){ //是否上传图片
		showMessageNote("请上传上传您的名片！");
		return false;
	}
	rlForm.submit();
	return true;
}
//查看证件大图
function showBigCardImg(is_show){
	if(is_show==1){
		jQuery("#bigCardTmpImg").show(200);
	}else{
		jQuery("#bigCardTmpImg").hide(200);
	}
}

//弹出窗口<!-- 用户上传证件--> 
function cardPopWin(){
	/*if(is_login==0||is_login==''){
		showLoginPopWindow();
		return;
	}*/
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery('body').scrollTop();
	var left = (winW-392)/2;
	var top = (winH-431)/2;
	var style = "";
	style = "position:fixed;left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="cardPopWin" style="'+style+'">';
	html += '<div class="content">';
	html += '<div class="l-arrow"></div>';
	html += '<div class="tle">上传名片</div>';
	html += '<form id="cardImgForm" method="post" action=""  enctype="multipart/form-data" target="tmpUpFrame">';
	html += '<table class="logTable">';
	html += '<tr valign="middle"  height="25">';
	html += '<td align="left"><img class="cardimg" src="/images/default/headImgTmp239.png" id="cardTmpImgBig" /></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="30">';
	html += '<td align="left"><div class="upOpt"><input type="button" class="uploadBtn"  align="absmiddle"/><input type="file" name="headimg" id="headimg" onchange="uploadCardTmpImg()" class="fileInpt"></div></td>';
	html += '</tr>';
	html += '<tr valign="middle"  height="30">';
	html += '<td align="left">仅限jpg、gif、png图片文件，小于2M</td>';
	html += '</tr>';
	html += '<tr valign="bottom"  height="45">';
	html += '<td align="left"><input type="button" class="saveBtn" onclick="saveCardTmpImg();"  align="absmiddle"/> <input type="button" class="cancelBtn"  align="absmiddle" onclick="jQuery(\'#cardPopWin\').remove();removeDivLayer();"/></td>';
	html += '</tr>';
	html += '</table>';
	html += '<input type="hidden" name="act" value="addCardTmpImg" />';
	html += '</form>';
	html += '</div>';
	//html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
}


function uploadCardTmpImg(){
	document.getElementById('cardImgForm').submit();
}

function saveCardTmpImg(){
	jQuery("#is_uploadimg").val('1');
	jQuery("#cardTmpImg,#bigCardTmpImg").attr('src',data.tmp_img);
	jQuery('#cardPopWin').remove();
	removeDivLayer();
}


function hideReportNote(){
	jQuery('#reportConNote').hide();
	document.getElementById('reportContent').focus();
}
function showReportNote(){
	var rConent = jQuery.trim(jQuery("#reportContent").val());
	if(rConent!=''){
		return false;
	}else{
		jQuery('#reportConNote').show();
	}
}
//提交举报内容
function doReport(){
	//alert("举报信息成功！");
	var uid = is_login; //当前举报用户ID
	var rType = jQuery("#reportMsgForm input[name='item']:checked").val();//举报类型
	if(rType==undefined){
		alert(" 请选择举报信息类型！");
		return false;
	}
	var rUrl = location.href; //当前页面地址
	var rConent = jQuery.trim(jQuery("#reportContent").val());
	if(rConent==''){
		alert(" 请填写举报详细内容！");
		return false;
	}
	jQuery("#reportMsgForm .confirmBtn").attr('disabled',true);
	jQuery.getJSON(encodeURI('/'+base_script+'/usermain/?act=reportMsg&uid='+uid+"&type="+rType+"&url="+rUrl+"&content="+rConent),function(json){
		showMessageNote(json.msg);
	});
	jQuery('#reportMsgWin').remove();removeDivLayer();
}

//举报按钮
function reportFunc(){
	var bRight = (jQuery(document).width()-1000)/2 -32;
	var reportTopEle = jQuery('<div class="reportTxt">举报</div>').appendTo(jQuery("#wrapper")).css("right",bRight+"px")
					   .click(function(){reportMsgWin();});
    //IE6下的定位
    if (!window.XMLHttpRequest) {
    	var st = jQuery(document).scrollTop(), winh = jQuery(window).height();
    	reportTopEle.css("top", st + winh - 50);    
    }
}


//系统消息弹出窗<!-- 转发弹窗--> 
function showPostToWin(goods_name,goods_img,url,text){
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery(window).scrollTop();
	var left = (winW-433)/2;
	var top = scrlTop;
	if(jQuery('#postToWin').height()){
		top = (winH-parseInt(jQuery("#postToWin").height()))/2
		jQuery("#postToWin").css({"top":top+'px'});
		return false;
	}
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="postToWin" style="'+style+'">';
	html += '<div id="countdown-hide" style="display:none;"></div>';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#postToWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<div class="lcMsgNote">';
	html += '<span class="colorStyle1">你通过以下方式分享：</span><br/>';
    
    //HACK(2014/2/9:zakk): 情人节专题分享
    text = typeof text !== 'undefined' ? text : '';
    html += ' <a href="javascript:;" onclick="postToTQQ(\''+goods_name+'\',\''+goods_img+'\',\''+url+'\')" class="quikLoginIcon icon_qqwb_36" style="margin-right:20px;"></a>';
    html += ' <a href="javascript:;" onclick="postToQQ(\''+goods_name+'\',\''+goods_img+'\',\''+url+'\')" class="quikLoginIcon icon_QQ_36" style="margin-right:20px;"></a>';
    if(text){
        html += ' <a href="javascript:;" onclick="postToSina20140214(\''+text+'\',\''+goods_img+'\',\''+url+'\')" class="quikLoginIcon icon_sina_36" style="margin-right:20px;"></a>';
    }
    else{
        html += ' <a href="javascript:;" onclick="postToSina(\''+goods_name+'\',\''+goods_img+'\',\''+url+'\')" class="quikLoginIcon icon_sina_36" style="margin-right:20px;"></a>';
    }
	html += ' <a href="javascript:;" onclick="postToDouban(\''+goods_name+'\',\''+goods_img+'\',\''+url+'\')" class="quikLoginIcon icon_douban_36"></a>';
	html += '</div>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
	top = (winH-parseInt(jQuery("#postToWin").height()))/2
	jQuery("#postToWin").css({"top":top+'px'}).show();
}

//系统消息弹出窗<!-- 消息弹窗--> 
function payMessageNote(){
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery(window).scrollTop();
	var left = (winW-433)/2;
	var top = scrlTop;
	if(jQuery('#sysMessageWin').height()){
		top = (winH-parseInt(jQuery("#sysMessageWin").height()))/2
		jQuery("#sysMessageWin").css({"top":top+'px'});
		return false;
	}
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
	html += '<div class="popWindow" id="sysMessageWin" style="'+style+'">';
	html += '<div id="countdown-hide" style="display:none;"></div>';
	html += '<div class="popHeader"><span class="close" title="关闭" onclick="jQuery(\'#sysMessageWin\').remove();removeDivLayer();"></span></div>';
	html += '<div class="content">';
	html += '<div class="lcMsgNote">';
	html += '<img src="/images/default/loading_0001.gif"/> <br>支付完成前，请不要关闭此支付验证窗口。<br>支付完成后，请根据您支付的情况点击下面按钮。';
	html += '<p style="padding:15px 0px;"><a href="/'+base_script+'/myorder/pay/" class="nobtn">支付失败</a><a href="/'+base_script+'/myorder/" class="okbtn">支付完成</a></p>';
	html += '</div>';
	html += '</div>';
	html += '<div class="popFooter"></div>';
	html += '</div>  ';
	jQuery('body').append(html);
	top = (winH-parseInt(jQuery("#sysMessageWin").height()))/2
	jQuery("#sysMessageWin").css({"top":top+'px'}).show();
	
}


//弹出窗<!-- 视频弹窗--> 
function playVideoWin(video_path,cover){
	addDivLayer();
	var winW = jQuery(window).width();
	var winH = jQuery(window).height();
	var scrlTop = jQuery(window).scrollTop();
	var left = (winW-720)/2;
	var top = 100;
	var style = "";
	style = "left:"+left+"px;top:"+top+"px;z-index: 100000000;_position:absolute;";
	var html = "";
		html += '<div class="popWindow videoPopWin" id="videoPopWin" style="'+style+'">';
		html += '<div class="content">';
		html += '<div class="closeG14" title="关闭" onclick="jQuery(\'#videoPopWin\').remove();removeDivLayer();"></div>';
		html += '<div class="video" id="myPopVideo"></div>';
		html += '</div>';
		html += '</div>';
		html += '</div>  ';
	jQuery('body').append(html);
	top = (winH-parseInt(jQuery("#videoPopWin").height()))/2
	jQuery("#videoPopWin").css({"top":top+'px'}).show();
	setVideoPlayer(video_path,cover,'myPopVideo');
}
function setVideoPlayer(video_path,cover,DomID){
	if(DomID==undefined) DomID = "myPopVideo";
	jwplayer(DomID).setup({
		width:720,
		height:480,
		autostart:true,
		file: video_path,//"http://image.iliangcang.com/ware/video/topic/nousau_process03_0607.mp4",
		image: cover//"http://images.sowhatshop.net/ware/orig/2/1/1490.jpg"
	});	
}


//JS构造POST请求
function doPost(URL, PARAMS) {        
    var temp = document.createElement("form");        
    temp.action = URL;        
    temp.method = "post";        
    temp.style.display = "none";        
    for (var x in PARAMS) {        
        var opt = document.createElement("textarea");        
        opt.name = x;        
        opt.value = PARAMS[x];        
        // alert(opt.name)        
        temp.appendChild(opt);        
    }        
    document.body.appendChild(temp);        
    temp.submit();        
    return temp;        
}


//set cookie
function addCookie(objName,objValue,objHours){//添加cookie
	var str = objName + "=" + escape(objValue);
	if(objHours > 0){//为0时不设定过期时间，浏览器关闭时cookie自动消失
	var date = new Date();
	var ms = objHours*3600*1000;
	date.setTime(date.getTime() + ms);
	str += ";path=/; expires=" + date.toGMTString();
	}
	document.cookie = str;
}

//get cookie
function getCookie(objName){//获取指定名称的cookie的值
	var arrStr = document.cookie.split("; ");
	for(var i = 0;i < arrStr.length;i ++){
		var temp = arrStr[i].split("=");
		if(temp[0] == objName) return unescape(temp[1]);
	} 
}

//del cookie
function deleteCookie(name){
    var date=new Date();
    date.setTime(date.getTime()-10000);
    document.cookie=name+"=; expire="+date.toGMTString()+"; path=/";
}


function postToSina(title,pic,site)
{
	 var param = {
		url:site,
		type:'5',
		count:'', /**是否显示分享数，1显示(可选)*/
		appkey:'4177348326', /**您申请的应用appkey,显示分享来源(可选)*/
		title:encodeURI(title+"，来自良仓－生活家的良品分享 @i良仓 购买链接："), /**分享的文字内容(可选，默认为所在页面的title)*/
		pic:pic, /**分享图片的路径(可选)*/
		ralateUid:'', /**关联用户的UID，分享微博会@该用户(可选)*/
		language:'zh_cn', /**设置语言，zh_cn|zh_tw(可选)*/
		rnd:new Date().valueOf()
	  }
	  var temp = [];
	  var s = window.screen; 
	  for( var p in param ){
		temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
	  }
	  var url = "http://service.weibo.com/share/share.php?"+temp.join("&");
	  window.open(url,"_blank",['toolbar=0,status=0,resizable=1,width=600,height=500,left=',(s.width-440)/2,',top=',(s.height-430)/2].join(''));
	  jQuery('#postToWin').remove();removeDivLayer();
}

function postToSina20140214(text,pic,site)
{
	 var param = {
		url:site,
		type:'5',
		count:'', /**是否显示分享数，1显示(可选)*/
		appkey:'4177348326', /**您申请的应用appkey,显示分享来源(可选)*/
		title: text,
		pic:pic, /**分享图片的路径(可选)*/
		ralateUid:'', /**关联用户的UID，分享微博会@该用户(可选)*/
		language:'zh_cn', /**设置语言，zh_cn|zh_tw(可选)*/
		rnd:new Date().valueOf()
	  }
	  var temp = [];
	  var s = window.screen; 
	  for( var p in param ){
		temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
	  }
	  var url = "http://service.weibo.com/share/share.php?"+temp.join("&");
	  window.open(url,"_blank",['toolbar=0,status=0,resizable=1,width=600,height=500,left=',(s.width-440)/2,',top=',(s.height-430)/2].join(''));
	  jQuery('#postToWin').remove();removeDivLayer();
}


function postToQQ(title,pic,site){
		var p = {
			url:site,
			showcount:'0',/*是否显示分享总数,显示：'1'，不显示：'0' */
			desc:title+"  ",/*默认分享理由(可选) 来自www.iliangcang.com*/
			summary:'',/*分享摘要(可选)*/
			title:'',/*分享标题(可选)*/
			site:encodeURI('良仓－iliangcang.com'),/*分享来源 如：腾讯网(可选)*/
			pics:pic, /*分享图片的路径(可选)*/
			style:'203',
			width:98,
			height:22,
			otype:'share'
		};
		 var s = window.screen;
		var param = [];
		for(var i in p){
			param.push(i + '=' + encodeURIComponent(p[i]||''));
		}
		
		var url = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?"+param.join("&");
		window.open(url,"_blank",['toolbar=0,status=0,resizable=1,width=600,height=500,left=',(s.width-440)/2,',top=',(s.height-430)/2].join(''));
		jQuery('#postToWin').remove();removeDivLayer();
}

function postToTQQ(title,pic,site){
	var _t =  encodeURI(title+"，来自良仓－生活家的良品分享 @i良仓 购买链接："); //来自www.iliangcang.com 
	var _url = encodeURI(document.location); 
	var _appkey =encodeURI('801302873'); //encodeURI("appkey");//你从腾讯获得的appkey 
	var _pic = pic;//encodeURI('');//（列如：var _pic='图片url1|图片url2|图片url3....） 
	var _site = site;//你的网站地址 
	var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; 
	window.open( _u,'转播到腾讯微博', 'width=600, height=500, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' ); 
	jQuery('#postToWin').remove();removeDivLayer();
}

function postToDouban(title,pic,site)
{
		var d=document,
		e=encodeURIComponent,
		s1=window.getSelection,
		s2=d.getSelection,
		s3=d.selection,
		s=s1?s1():s2?s2():s3?s3.createRange().text:'',
		sre = window.screen,
		r='http://www.douban.com/recommend/?url='+e(site)+'&title='+e(title+" ，来自良仓－生活家的良品分享 ")+'&sel='+e(s)+'&v=1',
		x=function(){
			if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330,left='+(sre.width-440)/2+',top='+(sre.height-430)/2))location.href=r+'&r=1'
		};
		if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}
		jQuery('#postToWin').remove();removeDivLayer();
}



//分页函数
function pager(func,currentPage,totalPages,totalCount)
{
		 var htmlData = "";
	     htmlData += ' 共<b>'+totalCount+'</b>条记录 第<b>'+currentPage+'</b>页/共<b>'+totalPages+'</b>页 ';
	     if(currentPage==1)
	     {
	     	 htmlData += ' <<  ';
	     	 htmlData += ' <  ';
	     }else{
	     	 htmlData += '<a href="javascript:'+func+'(1);"> << </a> ';
	     	 htmlData += '<a href="javascript:'+func+'('+(currentPage-1)+');"> < </a> ';
	     }
	     //分页条目
	     var allPages = totalPages;	//总共的页数
	     var pageItem = 5;							//每页显示的数目
	     var start = 1;								//起始页码
	     var end = 0;								//终止页码 currentPage
	     if(allPages<=pageItem){
	     	start = 1;
	     	end = allPages;
	     }else if( currentPage < Math.ceil(pageItem/2) ){
	     	start = 1
	     	end = pageItem;
	     }else if( (allPages-currentPage) <= pageItem ){
	     	start = allPages-pageItem;
	     	end = allPages;
	     }else{
	     	start = currentPage-Math.floor(pageItem/2);
	     	end = pageItem+currentPage-1;
	     }
	     for(var i =start ; i<=end;i++)
	     {
	     	if(i==currentPage)
	     	{
	     		 htmlData += '<b style="color:red">'+i+'</b> ';
	     	}else{
	     		 htmlData += '<a href="javascript:'+func+'('+i+');">'+i+'</a> ';
	     	}
	     }
	     if(totalPages==currentPage)
	     {
	     	 htmlData += ' >  ';
	     	 htmlData += ' >> ';
	     }else{
	     	 htmlData += '<a href="javascript:'+func+'('+(currentPage+1)+');"> > </a> ';
	    	 htmlData += '<a href="javascript:'+func+'('+totalPages+');"> >> </a> ';
	     }
	     return htmlData;
}

/**是否手机或者移动终端**/
function is_mobile(){
	var is_mob = false;
	var ua = navigator.userAgent.toLowerCase(); 
	var uaArr = ["240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel",
	                  "amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532",
	                  "asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry",
	                  "blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine",
	                  "eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente",
	                  "grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno",
	                  "ipad","iphone","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ",
	                  "lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo",
	                  "mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-",
	                  "moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia",
	                  "nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-",
	                  "playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo",
	                  "samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank",
	                  "sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit",
	                  "tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-",
	                  "voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda",
	                  "xde","zte"];
	for(var n in uaArr){
		if( ua.indexOf(uaArr[n])>=0 ){
			is_mob = true;
			break;
		}
	}
	
	return is_mob;
}

//根据不同的平台下载不同的版本的APP
function downloadAPP(){
    var browser = {
        versions : function() {
            var u = navigator.userAgent.toLowerCase();
            return {                                                           
                // mobile : !!u.match(/applewebkit.*mobile.*/)|| !!u.match(/applewebk.it/), //是否为移动终端                                               
                android : u.indexOf('android') > -1, //android终端
                // blackBerry: u.indexOf('bb10') > -1||u.indexOf('playbook') > -1, //黑莓                            
                iPhone : u.indexOf('iphone') > -1||u.indexOf('ipad')>-1 //iphone
                // windowsphone:u.indexOf('windows phone')>-1
            };
        }()
    }
    
    if(is_mobile()){
        if(browser.versions.iPhone){
            window.location.href='https://itunes.apple.com/cn/app/id680599201?mt=8';
        }else if(browser.versions.android){
            window.location.href='http://iliangcang.com/Liangcang20140721_general.apk';
        }else{
            alert('暂不支持该平台下载');
        }
    }else{
        window.location.href='http://www.iliangcang.com';
    }
}
//新版头尾js交互2015-02-11
function mobileOpen(){
    var browser = {
        versions : function() {
            var u = navigator.userAgent.toLowerCase();
            return {                                                           
                // mobile : !!u.match(/applewebkit.*mobile.*/)|| !!u.match(/applewebk.it/), //是否为移动终端                                               
                android : u.indexOf('android') > -1, //android终端
                // blackBerry: u.indexOf('bb10') > -1||u.indexOf('playbook') > -1, //黑莓                            
                iPhone : u.indexOf('iphone') > -1||u.indexOf('ipad')>-1, //iphone
                // windowsphone:u.indexOf('windows phone')>-1
            };
        }()
    }
    if(is_mobile()){
    	if(window.screen.width>414){
    		jQuery("body").append("<div id='headerm'><img src='/images/wap/mob_top1242.png' /></div><div id='hdLike'><a href='javascript:void(0);' id='appDownTxt' style='float:left;width:66%;'><a href='/"+base_script+"/shop/' style='width:33.4%;float:right;'></div>");
    	}else if(window.screen.width>320 && window.screen.width<=414){
    		jQuery("body").append("<div id='headerm'><img src='/images/wap/mob_top750.png' /></div><div id='hdLike'><a href='javascript:void(0);' id='appDownTxt' style='float:left;width:62%;'><a href='/"+base_script+"/shop/' style='width:37.5%;float:right;'></div>");
    	}else{
    		jQuery("body").append("<div id='headerm'><img src='/images/wap/mob_top640.png' /></div><div id='hdLike'><a href='javascript:void(0);' id='appDownTxt' style='float:left;width:62%;'><a href='/"+base_script+"/shop/' style='width:37.5%;float:right;'></div>");
    	}
        
        if(browser.versions.iPhone){
        	document.getElementById('appDownTxt').addEventListener('click',function(){
        		window.location.href="http://a.app.qq.com/o/simple.jsp?pkgname=com.liangcang&g_f=991653";
        	});
        	setTimeout(function(){
            	var imghei=(jQuery("#headerm img").height()-1)+"px 0 0 0";
            	jQuery("#hdLike").css("height",jQuery("#headerm img").height());
            	jQuery("body").css("padding",imghei);
        	},3000);        	
        }else if(browser.versions.android){
        	document.getElementById('appDownTxt').addEventListener('click',function(){
        		window.location.href='http://iliangcang.com/Liangcang20140721_general.apk';
        	});
        	setTimeout(function(){
            	var imghei=(jQuery("#headerm img").height()-1)+"px 0 0 0";
            	jQuery("#hdLike").css("height",jQuery("#headerm img").height());
            	jQuery("body").css("padding",imghei);
        	},3000);
        }else{
        	//document.getElementById('headerm').style.display="none";
            //alert('暂不支持该平台下载');
        	jQuery("#headerm").remove();
        }
    }
}
jQuery(document).ready(function(){
	mobileOpen();
		jQuery("#nav li").hover(function(){
			if(!jQuery(this).hasClass("add")){
			jQuery(this).addClass("liback");
			}
			jQuery(this).find(".fdDiv").show();
		},function(){
			jQuery(this).removeClass("liback");
			jQuery(this).find(".fdDiv").hide();	
		})
		jQuery(".logo").hover(function(){
            jQuery(this).stop().animate({'opacity':.8},200);
        },function(){
            jQuery(this).stop().animate({'opacity':1},200);
        });
//        二级导航鼠标移入效果
		var subLi;
		jQuery(".subMenu li").each(function(){
			if(jQuery(this).find("a").hasClass("active")){
				subLi=jQuery(this).index();
			}
			jQuery(this).bind('mouseover',function(){
				jQuery(".subMenu li").children('a').removeClass("active");
				jQuery(this).children('a').addClass("active");
				var sub_id=jQuery(this).attr("sub_id");
				console.log(sub_id);
				if(sub_id){
				var n =0;
				if(sub_id==1 || sub_id==2){
					n =jQuery(".submHide").eq(sub_id).height()+73;	
				}else{
					n =jQuery(".submHide").eq(sub_id).height()+73*1;
				}
	            jQuery(".submHide").hide();
				
	            jQuery(".submHide").eq(sub_id).show();
	            jQuery("#header-subnav-wrapper").stop(!0,!1).animate({
	                height: n
	            },
	            200);
	            jQuery(".submHide").mouseover(function() {
	                jQuery("#header-subnav-wrapper").stop(!0,!1).animate({
	                    height: n
	                },
	                500)
	            });
	            jQuery(".submHide").mouseout(function() {
	                jQuery("#header-subnav-wrapper").stop(!0,!1).animate({
	                    height: "0"
	                },
	                500)
	            });
	            jQuery(".subMenu ul").mouseout(function() {
	                jQuery("#header-subnav-wrapper").stop(!0,!1).animate({
	                    height: "0"
	                },
	                500)
	            })
				}
			});
			jQuery(this).bind('mouseout',function(){
				jQuery(".submHide").hide();
				jQuery(".subMenu li").children('a').removeClass("active");
				jQuery(".subMenu li").eq(subLi).children('a').addClass("active");	
			});
		});
		
		jQuery(".submHide").each(function(idx){
					jQuery(this).hover(function(){
						jQuery(".subMenu li").children('a').removeClass("active");
						jQuery(".subMenu li.subLi").eq(idx).children('a').addClass("active");		
					},function(){
						jQuery(".subMenu li").children('a').removeClass("active");
						jQuery(".subMenu li").eq(subLi).children('a').addClass("active");
					});
				});
//   已优化一次性查询出所有数据  wangjiaming by 2015-06-17
//		jQuery(".storeLeft dt a.ctxt").each(function(){
//			var cat_id = jQuery(this).attr('cat_id');
//			navOurCatList(cat_id);
//		});
		//购物车下拉经过变色
		jQuery(".cart_list dl").live('mouseover',function(){
			jQuery(this).addClass("back2e");
		})
		jQuery(".cart_list dl").live('mouseout',function(){
			jQuery(this).removeClass("back2e");
		})
//       搜索框移入移出效果
        var searchs = jQuery('.search .search-input');
        var search_input = jQuery('.search .search-input input');
        var search_icon = jQuery('.search .search-icon');
        search_icon.mouseover(function(){
            jQuery(this).stop().animate({'opacity':.7});
        });
        search_icon.mouseout(function(){
            jQuery(this).stop().animate({'opacity':1});
        });
        search_icon.toggle(function(){
            searchs.stop().animate({'left':'14px'});
            jQuery('.search input').focus();
            return false;
        },function(){
            if(search_input.val() == '' && search_input.attr('placeholder')=='search...'){
            	search_input.trigger("blur");
                searchs.stop().animate({'left':'280px'});
                return false;
            }
            else{
               // searchs.css({'left':'14px'});
                navSearch("gs");
                return false;
            }
        });
        jQuery('.search-input').click(function(){
            searchs.css({'left':'14px'});
            jQuery('.search input').focus();
            return false;
        });
        jQuery('body').click(function(){
        	search_input.trigger("blur");
        	searchs.stop().animate({'left':'280px'});
        });

//        向上滚动时二级导航固定
        var prev_top = 0;
        var pre_space = 0;
        jQuery(window).scroll(function () {
            var cur_top = jQuery(document).scrollTop();
            var space = 1;
            if(cur_top>115){
                if(cur_top>prev_top){// 向下滚动则次导航栏固定
                    space = -space;
                }
                if(cur_top<prev_top){//向上滚动则主导航次导航都固定定位
                    space = space;
                }
                if(space-pre_space>0 ){//第一次向下滚动
                    jQuery('#header').stop().animate({'top':'0px'},250);
                }
                if(space-pre_space<0 ){//第一次向上滚动
                    jQuery('#header').stop().animate({'top':'-56px'},250);
                }
            }
            else{
                jQuery('#header').stop().animate({'top':'0px'},250);
            }
            jQuery("object#looyuShare").css("display","none");
            prev_top = cur_top;
            pre_space = space;
        });
		
        function moveLeft(){
			jQuery('.hand').animate({'left':'-15px'},400);
			}
		function moveRight(){
			jQuery('.hand').animate({'left':'0'},400);
			}
		var timer1 = null;
		var timer2 = null;
		var timer3 = null;
		jQuery('#service a').live('mouseover',function(){
            moveLeft();
            timer1=setTimeout(moveRight(),400);
            clearTimeout(timer1);
            timer2=setTimeout(moveLeft(),800);
            clearTimeout(timer2);
            timer3=setTimeout(moveRight(),1200);
            clearTimeout(timer3);
        });
		jQuery('#service a').live('mouseout',function(){
			moveRight();
        });
//      页脚二维码处文字过渡效果
        jQuery('.download').hover(function(){
           // jQuery('.download p').stop().animate({'opacity':'1'},200);
            jQuery('.download-code').fadeIn(200);
			
        },function(){
          //  jQuery('.download p').stop().animate({'opacity':'.6'},200)
            jQuery('.download-code').fadeOut(200);
        });
        jQuery('.commun a').hover(function(){
            jQuery(this).stop().animate({'opacity':'1'},200);
			if(jQuery(this).hasClass("wechat")){
				jQuery('.wechat-code').fadeIn(200);
			}
        },function(){
            jQuery(this).stop().animate({'opacity':'.2'},200)
			if(jQuery(this).hasClass("wechat")){
				jQuery('.wechat-code').fadeOut(200);
			}
        });
        
    });

//分类数据
	function navOurCatList(pid){
		var htmlData = '';
		jQuery.getJSON("/"+base_script+"/shop/?act=catList&parent_id="+pid,function(data){
			if(data.length>0){
				for(n in data){
					htmlData+= '<dd><a href="{/literal}{$base_url_price}{literal}cat_id='+data[n].cat_code+'">'+data[n].cat_name+'</a></dd>';
				}
				jQuery('#navOurCatP_'+pid).append(htmlData);
			}
		});
	}

