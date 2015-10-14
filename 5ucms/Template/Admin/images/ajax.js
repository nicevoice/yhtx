var http_request = false;
var callobj;
var lastScript,h_=document.getElementsByTagName("head")[0];
function makeRequest(url, functionName, httpType, sendData) {
	http_request = false;
	if (!httpType) httpType = "GET";
	if (window.XMLHttpRequest) { 
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			http_request.overrideMimeType('text/plain');
		}
		} else if (window.ActiveXObject) {
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) {
		alert('Cannot send an XMLHTTP request');
		return false;
	}
	var changefunc="http_request.onreadystatechange = "+functionName;
	eval (changefunc);
	http_request.open(httpType, url, true);
	http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http_request.send(sendData);
}

function getReturnedText () {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			var http_msg = http_request.responseText;
			if (http_msg=='0') {
				frm.submits.disabled="disabled";
				show.style.display='';
			}else{
				frm.submits.disabled="";
				show.style.display='none';
			}
		}
	}
}
function $$(objname){return document.getElementById(objname);}
function ShowObj(objname){var obj = $$(objname);obj.style.display = "block";}
function HideObj(objname){var obj = $$(objname);obj.style.display = "none";}
function s(o){var obj = $$(o);obj.style.display = "block";}
function h(o){var obj = $$(o);obj.style.display = "none";}

// 处理图片
function zoom(zid,zimg) {
	var div = $$(zid);
	div.style.position = 'absolute';
	div.innerHTML = "<img src=" + zimg + " style='padding:1px;border:1px solid #9FD253;background-color:#fff;margin:18px 0px 0px 0px;'>"
}
// 发布内容
function ChkPost(){
	var chkok = true;
	if (chkok) {if (frm.oTitle.value.length<1) {alert('文章标题不能为空!');chkok=false;}}
	if (chkok) {if (frm.oCid.value==0) {alert('请选择栏目!');chkok=false;}}
	if (chkok) {if (frm.oCid.value==-1) {alert('大栏目不允许发布文章!');chkok=false;}}
	if (chkok) {if (frm.oCid.value==-2) {alert('你没有当前栏目的管理权限!');chkok=false;}}
	if (chkok) {frm.submit();}
}
// 检测标题
function ChkTitle(){
	var aTitle = $$("oTitle").value;
	if (aTitle.length>0){makeRequest("Ajax.Asp?Act=ChkTitle&Key=" + escape(aTitle) + '&Rand=' + Math.random(),"ReturnOnlyAlert","get","");}
}
// 检测自定义文件名
function ChkDiyname(aID){
	var aDiyname = $$("oDiyname").value;
	if (aDiyname.length>0){makeRequest("Ajax.Asp?Act=ChkDiyname&Key=" + escape(aDiyname) + '&ID=' + aID + '&Rand=' + Math.random(),"ReturnOnlyAlert","get","");}
}
// 内容推荐 150822
function ContentCommend(ID){
	callobj = $$("ContentCommend" + ID);
	makeRequest("/admin.php/Content/contentcommend/id/" + ID + "/Rand/" + Math.random(),"ReturnInnerHTML","get","");
}
// 内容状态 150822
function ContentState(ID){
	callobj = $$("ContentState" + ID);
	makeRequest("/admin.php/Content/contentstate/id/" + ID + "/Rand/" + Math.random(),"ReturnInnerHTML","get","");
}
// 内链窗口 150822
function sitelinktarget(ID){
	callobj = $$("sitelinktarget" + ID);
	makeRequest("/admin.php/Sitelink/sitelinktarget/id/" + ID + "/Rand/" + Math.random(),"ReturnInnerHTML","get","");
}
// 内链状态 150822
function sitelinkstate(ID){
	callobj = $$("sitelinkstate" + ID);
	makeRequest("/admin.php/Sitelink/sitelinkstate/id/" + ID + "/Rand/" + Math.random(),"ReturnInnerHTML","get","");
}
// 安装插件
function PlusInstall(PlusName){
	callobj = $$("plus" + PlusName);
	makeRequest("Ajax.Asp?Act=plusinstall&plusname=" + PlusName + "&Rand=" + Math.random(),"ReturnInnerHTML","get","");
}
// 卸载插件
function PlusUnInstall(PlusName){
	callobj = $$("plus" + PlusName);
	makeRequest("Ajax.Asp?Act=plusuninstall&plusname=" + PlusName + "&Rand=" + Math.random(),"ReturnInnerHTML","get","");
}
// 卸载插件
function modeext(cid,id){
	callobj = $$('_modeindex');
	makeRequest("Ajax.Asp?Act=modeext&cid=" + cid + "&id=" + id + "&Rand=" + Math.random(),"ReturnInnerHTMLMODEEXT","get","");
}
// 返回: 返回正常数据写入对象,返回失败就ALERT报错
function ReturnInnerHTMLMODEEXT(){
	if (http_request.readyState == 4) {
		if (http_request.status == 200){
			var vback=http_request.responseText.split('|');
			if(vback[0]=='False'){alert(vback[1]);}else{callobj.innerHTML=vback[1].replace(/\#｜\#/g,'|');}
			if(callobj.innerHTML==''){
				h('_modeindexs');
			}else{
				s('_modeindexs');
			}
		}
	}
}

// 返回操作(AJAX) ///////////////////

// 返回: 仅出错提示用
function ReturnOnlyAlert(){
	if (http_request.readyState == 4) {
		if (http_request.status == 200){
			var vback=http_request.responseText.split('|');
			if(vback[0]=='False'){alert(vback[1]);}
		}
	}
}
// 返回: 返回正常数据写入对象,返回失败就ALERT报错
function ReturnInnerHTML(){
	if (http_request.readyState == 4) {
		if (http_request.status == 200){
			var vback=http_request.responseText.split('|');
			if(vback[0]=='False'){alert(vback[1]);}else{callobj.innerHTML=vback[1];}
		}
	}
}

// 批量操作  ///////////////////

// 选择
function CheckAll(){
	var chk = $$("chkall").checked;
	var checkboxs = document.getElementsByName('ids[]');
	for(var i = 0; i < checkboxs.length; i++){checkboxs[i].checked=chk;} 
}
function contentdo(val){
	var id='';
	var runit=false; 
	var checkboxs = document.getElementsByName('ids[]');
	frm.action= '/admin.php/Content/' +val;
	for(var i = 0; i < checkboxs.length; i++){
		if(checkboxs[i].checked){runit=true;}
	}  
	if(runit){
		if(val=='del'){
			if(confirm('您确定要删除这些记录吗?')){frm.submit();}
		} else {
			frm.submit();
		}
	}
} 
