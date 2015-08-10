/*
 *@needle jQuery
 *@title 购物车数据类(数据库版本)
 *@desc 购物车添加、修改、删除
 *@author gaorunqiao<goen88@163.com>
 *@date 2013/10/30
 *
 */

var ILC_CAR = {};//数据容器
ILC_CAR.base_url = '/'+base_script+'/shoppingcar/'; //购物车页面链接
ILC_CAR.targetCon = "#shoppingContainerCon"; //内容容器
ILC_CAR.items = []; //购物车容器 [{'goods_id':0,'goods_form':1,'brand_name':'','artist':'','goods_img':'','price':0,'amount':0},...]

ILC_CAR.add = function() //添加商品
{
	//检测登录
	var uid = parseInt(is_login);
	if(uid==0||uid==''){
		showLoginPopWindow();
		return false;
	}
	
	var optType = jQuery(this).attr('id'); //操作类型
	var is_gift_goods = jQuery('#is_gift_goods').val(); //操作类型
	
	jQuery.ajaxSetup({async:false});
	var _goods_id = jQuery("#cur_goods_id").val();
	var _goods_name = jQuery("#cur_goods_name").val();
	var _goods_name = _goods_name.replace(/\#/g,''); //wangjiaming by 2015-05-28 add 过滤‘#’
	var _shop_price = jQuery("#cur_shop_price").val();
	var _type_ids = jQuery("#cur_goods_type_ids").val();
	var _type_names = jQuery("#cur_goods_type_names").val();
	var _attr_ids = jQuery("#cur_goods_attr_ids").val();
	var _attr_names = jQuery("#cur_goods_attr_names").val();
	var _goods_sku_sn = jQuery("#cur_goods_sku_sn").val();
	var goodsAmount = jQuery("#goodsAmount").val();
	var _is_gift = jQuery('#cur_is_gift').val(); //包装纸ID
	
	

	//商品信息
	if(_type_ids==''&&_attr_ids==''){
		showMessageNote('请选择商品信息！');
		return false;
	}
	
	/*if(is_gift_goods==1&&_is_gift<=0){
		showMessageNote('请选择礼物包装纸！');
		return false;
	}*/
	//http://127.0.0.1:85/v/shoppingcar/?act=addCart&goods_id=224&goods_name=NaN345.88&type_ids=5,9&type_names=%E9%A2%9C%E8%89%B2,%E7%94%BB%E6%A1%86%E5%B0%BA%E7%A0%81&attr_ids=1,3&attr_names=%E7%BE%8E%E4%B8%BD%E7%9A%84%E9%A2%9C%E8%89%B2,%0A160x160&amount=1&wrapper_id=1
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=addCart&optType='+optType+'&goods_id='+_goods_id+'&goods_name='+_goods_name+'&shop_price='+_shop_price+'&type_ids='+_type_ids+'&type_names='+_type_names+'&attr_ids='+_attr_ids+'&attr_names='+_attr_names+'&amount='+goodsAmount+'&wrapper_id='+_is_gift+'&goods_sku_sn='+_goods_sku_sn),function(data){
		if(data.rs=='true'){
			if(optType=='buyNow'){//立即购买
				if(IS_Furnitured==true){
					showContractWin();
					jQuery("#conSubmit").click(function(){
						doPost(ILC_CAR.base_url+'submit/',{'payCartIds':data.cart_id,'payMoney':(parseFloat(_shop_price)*data.amount).toFixed(2),'payAmount':data.amount});
					});
				}else{
					doPost(ILC_CAR.base_url+'submit/',{'payCartIds':data.cart_id,'payMoney':(parseFloat(_shop_price)*data.amount).toFixed(2),'payAmount':data.amount});
				}
			}else{
				if(IS_Furnitured==true){ //如果家居商品
					showContractWin();
					jQuery("#conSubmit").click(function(){
						showMessageNote('加入购物车成功！');
						jQuery('#contract_1').hide();
						jQuery('.divLayer').hide();
					});
				}else{
					showMessageNote('加入购物车成功！');
				}
				ILC_CAR.list();	
			}
		}else{
			alert(data.msg);
			showLoginPopWindow();
		}
	});
	
}





ILC_CAR.list = function()
{
	var htmlData = '';
	var goodsAmountNum = 0;
	var goodsPriceNum = 0;
	var strc;
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=cartList&offset=3'),function(data){
		if(data.totalAmount!=0){
			for(var n in data.data){
				htmlData += ILC_CAR.addOneItem(data.data[n],n,data.data.length);
			}
		}
		if(data.total>0){
			strc='<span class="num">'+data.total+'</span><span class="colccc">购物车</span>';
			jQuery("#nullCar").addClass("hide");
			jQuery("#shoppingContainerCon").removeClass("hide");
			jQuery(".add-shop").html("查看我的购物车").attr("href","/"+base_script+"/shoppingcar/").attr("target","_blank");
		}else{
			strc='购物车';	
			jQuery("#shoppingContainerCon").addClass("hide");
			jQuery("#nullCar").removeClass("hide");
			jQuery(".add-shop").html("快去抢购良仓商品吧！").attr("href","/"+base_script+"/shop/");
		}
		jQuery(".cart-wrapper").html(strc);
		jQuery("#goodsPriceNum").html(data.totalMoney.toFixed(2));
		jQuery(ILC_CAR.targetCon).html(htmlData);
	});
	
}

ILC_CAR.addOneItem = function(data,n,len){
	var cls = "";
	
	if(n!=parseInt(len)-1) cls = 'bl';
	var wrapperInfo = '';
	if(data.wrapper_id>0) wrapperInfo="[礼物<!--：包装纸"+data.wrapper_id+"-->]";
	return '<dl>'+
				'<dt>'+
					'<a target="_blank" href="/'+base_script+'/goods/?id='+data.goods_id+'" target="_blank"><img src="'+data.goods_img_icon+'" /></a>'+
				'</dt>'+
				'<dd>'+
					'<p class="proTit"><a target="_blank" href="/'+base_script+'/goods/?id='+data.goods_id+'" target="_blank">'+data.goods_name+'</a></p>'+
				    '<p class="proColorSize">'+data.attr_names+wrapperInfo+'</p>'+
				    '<div class="proNum">数量：'+data.amount+'件<p class="proPrice">¥'+(data.shop_price*data.amount).toFixed(2)+'</p></div>'+
				'</dd><p class="clr"></p>'+
			'</dl>';
}





//检测是否有添加过商品
ILC_CAR.hasOne = function(goods_id/*商品ID*/,amount/*商品数量*/,goods_form/*商品形式：1－画廊；2－店铺*/,attr_key/*商品属性*/,attr_id/*商品属性ID*/){
	var goodsCartData = getCookie('ILC_GOODS_CART_ITEM');
	var isAdded = false;
	var tempArr = [];
	if(goodsCartData=='undefined')  goodsCartData = undefined;
	if(goodsCartData!=undefined&&goodsCartData!=''){
		var goodsCartList = goodsCartData.split('&');
		for(var n in goodsCartList){
			var item = goodsCartList[n];
			eval("var data="+item);
			if(data==undefined) continue;
			if( (data.goods_id==goods_id&&goods_form==1) || (data.goods_id==goods_id&&goods_form==2&&data.attr_key==attr_key&&data.attrid==attr_id) ){
				data.amount = parseInt(data.amount)+parseInt(amount);
				item = '{"goods_id":"'+data.goods_id+'","goods_name":"'+data.goods_name+'","shipway":"'+data.shipway+'","attr_name":"'+data.attr_name+'","attr_key":"'+data.attr_key+'","attrid":"'+data.attrid+'","attrid_name":"'+data.attrid_name+'","goods_form":"'+data.goods_form+'","brand_id":"'+data.brand_id+'","brand_name":"'+data.brand_name+'","goods_brief":"'+data.goods_brief+'","art_uid":"'+data.art_uid+'","artist":"'+data.artist+'","goods_img":"'+data.goods_img+'","price":"'+data.price+'","amount":"'+data.amount+'"}';
				isAdded = true;
			}
			tempArr.push(item);
		}
	}
	
	if(isAdded==true){ //如果添加重新修改产品数量即可
		addCookie('ILC_GOODS_CART_ITEM',tempArr.join('&'),720);
	}
	return isAdded;
}


//更新购物车信息
ILC_CAR.update = function(cart_id,k,v){
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=updateCart&cart_id='+cart_id+'&k='+k+"&v="+v),function(data){
		ILC_CAR.list2();
		ILC_CAR.list();	
	});
}


ILC_CAR.del = function(cart_id/*商品属性ID*/,callback/*回调*/) //从购物车移除
{
	if(confirm('确认从购物车移除吗?')==false) return ;
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=delOne&cart_id='+cart_id),function(data){
		if(data.rs=="true"){
			ILC_CAR.list();
			if(callback!=undefined){ //是否回调函数
				callback();
			}
		}else{
			alert(data.msg)
		}
	});
	return true;
}

ILC_CAR.clear = function() //清空购物车
{
	if(confirm('确认清空购物车吗？')==false){
		return false;
	}
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=clearCart'),function(data){
		ILC_CAR.list();
		
		if(callback!=undefined){ //是否回调函数
			callback();
		}
	});
	return true;
}


//购物车列表页商品列表
ILC_CAR.list2 = function()
{
	jQuery.ajaxSetup({async:false});
	var htmlData = '';
	
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=cartList&offset=50'),function(data){
		if(data.totalAmount!=0){
			for(var n in data.data){
				htmlData += ILC_CAR.addOneItem2(data.data[n],n,data.data.length);
			}
		}
	
		jQuery("#shoppingcarList table tr:first").siblings().remove();
		jQuery("#shoppingcarList table tr:first").after(htmlData);
	});
}

ILC_CAR.addOneItem2 = function(data,n,len){
	var htmlData = '';
	var amountInfo = '';
	var buyDisabled = ' name="car_items[]"  '; //' name="car_items[]" checked="checked" '; 
	data.goods_stock = parseInt(data.goods_stock);
	if(data.is_delete==1){
		amountInfo = '<p class="blue">已售罄，请移除购物车</p>';
		buyDisabled = 'disabled="disabled"';
	}else if(data.goods_stock<=0){
		amountInfo = '<p class="blue">已售罄，即将到货</p>';
		buyDisabled = 'disabled="disabled"';
	}else if(data.goods_stock<data.amount){
		amountInfo = '<p class="blue">库存不足</p>';
		buyDisabled = 'disabled="disabled"';
	}else if(data.goods_stock<=3){
		amountInfo = '<p class="blue">库存紧张<!--仅剩'+data.goods_stock+'件--></p>';
	}
	var wrapperInfo = '';
	if(data.wrapper_id>0) wrapperInfo="[礼物<!--：包装纸"+data.wrapper_id+"-->]";
	htmlData += '<tr class="itemlist2">'+
				    '<td   valign="top">'+
				    	'<input type="checkbox" class="chkbox" '+buyDisabled+' value="'+data.cart_id+'" amount="'+data.amount+'" price="'+data.shop_price+'" onclick="getTotalPayMoney()"/>'+
				        '<a href="/'+base_script+'/goods/?id='+data.goods_id+'" target="_blank" class="gimgCon"><img src="'+data.goods_img_icon+'"  /></a>'+
				    '<td class="txtl">'+
				    	'<p class="t"><a href="/'+base_script+'/goods/?id='+data.goods_id+'">'+data.goods_name+'</a></p>'+
				        '<p class="b">'+data.attr_names+wrapperInfo+'</p>'+
				    '</td>'+
				    '<td>'+
				    	'<span class="opt" onclick="addAmount(0,'+data.cart_id+')"><img src="'+static_host+'/images/default/minus.png"></span><input type="text" id="goodsAmount'+data.cart_id+'" readonly value="'+data.amount+'" class="num2"/><span class="opt" onclick="addAmount(1,'+data.cart_id+')"><img src="'+static_host+'/images/default/add.png"></span>'+
				    	amountInfo+
				    '</td>'+
				    '<td>'+data.shop_price+'</td>'+
				    '<td>'+(data.shop_price*data.amount).toFixed(2)+'</td>'+
				    '<td><a href="javascript:;" onclick="ILC_CAR.del('+data.cart_id+',ILC_CAR.list2);" class="blue">删除</a></td>'+
				'</tr>';
	return htmlData;
}


//选择要确定购买的商品列表
ILC_CAR.list3 = function(cart_ids)
{
	var htmlData = '';
	var goodsAmountNum = 0;
	var goodsPriceNum = 0;
	
	jQuery.getJSON(ILC_CAR.base_url,encodeURI('act=cartList&cart_ids='+cart_ids),function(data){
		
		if(data.totalAmount!=0){
			for(var n in data.data){
				htmlData += ILC_CAR.addOneItem3(data.data[n],n,data.data.length);
			}
		}
		
		jQuery('#shoppingCartBuyList').html(htmlData);
	});
	
}

ILC_CAR.addOneItem3 = function(data,n,len){
	var htmlData = '';
	var wrapperInfo = '';
	if(data.wrapper_id>0) wrapperInfo="[礼物<!--：包装纸"+data.wrapper_id+"-->]";
	
	var _funniturBtn = function(num){
		if(num==undefined) num = 0;
		if(data.is_furniture){ //如果是家具商品
			
			return '<span class="assignContr" id="furnitureBtn_'+data.cart_id+'_'+num+'"  onclick="showContr('+data.brand_id+',\''+data.brand_name+'\','+data.cart_id+','+num+')"></span>'+
			       '<input type="checkbox" name="contractKey_'+data.cart_id+'[]" id="contractKey_'+data.cart_id+'_'+num+'"  value="'+num+'" cart_id="'+data.cart_id+'" brand_id="'+data.brand_id+'" brand_name="'+data.brand_name+'" style="display:none;"/>'+
			       '<input type="hidden" name="contractMail_'+data.cart_id+'[]" id="contractMail_'+data.cart_id+'_'+num+'"  value=""/>';
		}else{
			return '';
		}
	};
	
	var _isHuadinNote = function(num){
		if(num==undefined) num = 0;
		if(data.brand_id==202||data.brand_id==223||data.brand_id==224){ //如果是家具商品
			return '<span class="huadianCls" id="huandian_'+data.cart_id+'_'+num+'" style="color:#0c79cc;">此商品只送北京地区</span>';
		}else{
			return '';
		}
	};
	
	
	if(data.is_gift==1){
		htmlData = '<li>'+
				'<div class="imgCon"><img src="'+data.goods_img_icon+'"></div>'+
			    '<div class="infoCon">'+
			    	'<div class="t">'+
			        	'<span style="width:590px;">'+data.brand_name+' ／ '+data.goods_name+'</span>'+
			            '<span style="width:60px;">x1</span>'+
			            '<span style="width:80px;">¥'+data.shop_price+'</span>'+
			            _funniturBtn(0)+
			            _isHuadinNote(0)+
			        '</div>'+
			        '<div class="b">'+
			        	'<span> <i class="icon-checkbox icon-check-empty" icon-check-empty data-display="0" data_id="'+data.cart_id+'" data-index="0" ></i><input type="hidden" name="giftKey_'+data.cart_id+'[]" id="giftKey_'+data.cart_id+'_0" disabled value="0"> 礼物包装 </span>'+
			            '<span style="margin-left:25px;">祝语：<input type="text" name="giftNote_'+data.cart_id+'[]" id="giftNote_'+data.cart_id+'_0" disabled  value="" class="inpt"  maxlength="350" placeholder="您可在此写下礼品赠言,不得超过350字符"/> </span>'+
			            '<span style="margin-left:50px;font-size:12px;display:none;" id="giftWrapPrice_'+data.cart_id+'_0">¥10.00</span>'+
			        '</div>'+
			    '</div>'+
			'</li>';
		if(data.amount>1){  //如果一个商品存在多个则拆开显示
			for(var i=1;i<data.amount;i++){
				htmlData += '<li>'+
								'<div class="imgCon"><img src="'+data.goods_img_icon+'"></div>'+
							    '<div class="infoCon">'+
							    	'<div class="t">'+
							        	'<span style="width:590px;">'+data.brand_name+' ／ '+data.goods_name+'</span>'+
							            '<span style="width:60px;">x1</span>'+
							            '<span style="width:80px;">¥'+data.shop_price+'</span>'+
							            _funniturBtn(i)+
							            _isHuadinNote(i)+
							        '</div>'+
							        '<div class="b">'+
							        	'<span> <i class="icon-checkbox icon-check-empty" icon-check-empty data-display="0" data_id="'+data.cart_id+'" data-index="'+i+'" ></i><input type="hidden" name="giftKey_'+data.cart_id+'[]" id="giftKey_'+data.cart_id+'_'+i+'" disabled value="'+i+'"> 礼物包装 </span>'+
							            '<span style="margin-left:25px;">祝语：<input type="text" name="giftNote_'+data.cart_id+'[]" id="giftNote_'+data.cart_id+'_'+i+'" disabled  value="" class="inpt" maxlength="350" placeholder="您可在此写下礼品赠言,不得超过350字符"/> </span>'+
							            '<span style="margin-left:50px;font-size:12px;display:none;" id="giftWrapPrice_'+data.cart_id+'_'+i+'">¥10.00</span>'+
							        '</div>'+
							    '</div>'+
							'</li>';
			}
		}
	}else{
		htmlData = '<li>'+
						'<div class="imgCon"><img src="'+data.goods_img_icon+'"></div>'+
					    '<div class="infoCon">'+
					    	'<div class="t" style="border:none; margin-top:25px;">'+
					        	'<span style="width:590px;">'+data.brand_name+' ／ '+data.goods_name+'</span>'+
					            '<span style="width:60px;">x'+data.amount+'</span>'+
					            '<span style="width:80px;">¥'+(data.shop_price*data.amount).toFixed(2)+'</span>'+
					            _funniturBtn(0)+
					            _isHuadinNote(0)+
					        '</div>'+
					    '</div>'+
					'</li>';
	}
	
	return htmlData;
}


//hack 双十二活动订单减免规则 2014-12-12
function checkActive1212(money,start_time,end_time){
	var dateObj = new Date();
	var y = dateObj.getFullYear();
	var m = dateObj.getMonth()+1;
	m = m<10?'0'+m:m;
	var d = dateObj.getDate();
	d = d<10?'0'+d:d;
	var curDT = y+"-"+m+"-"+d+" 00:00:00";
	var promotionMoney = 0;
	if(curDT>=start_time && curDT< end_time){ 
		if(money>=800&&money<1500){
			promotionMoney = 100;
		}else if(money>=1500&&money<2000){
			promotionMoney = 200;
		}else if(money>=2000){
			promotionMoney = 500;
		}
	}
	return promotionMoney;
}



//＝＝数据初始化＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
jQuery(document).ready(function(){
	var uid = parseInt(is_login);
	if(uid!=0||uid!=''){
		ILC_CAR.list();
	}
});

