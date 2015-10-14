function www_5ucms_com(id){return document.getElementById(id);}
function show(o){document.getElementById(o).style.display="block";}
function hide(o){document.getElementById(o).style.display="none";}
function geturl(url,id){
var http=false;
www_5ucms_com(id).innerHTML='<span class="loading">&nbsp;&nbsp;</span>';
if(window.XMLHttpRequest){http=new XMLHttpRequest();if(http.overrideMimeType){http.overrideMimeType('text/plain');}}else if(window.ActiveXObject){try{http=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){try{http=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}}}
if(!http){alert('Cannot send an XMLHTTP request');return false;}
http.onreadystatechange=function(){if(http.readyState==4){www_5ucms_com(id).innerHTML=http.responseText;}}
http.open("get", url, true);
http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
http.send(null);
}

function size(id,width,height,nwin){
	if(!width) width=680;
	if(!height) height=800;
	if(nwin){nwin=true;}else{nwin=false;}
	if(id.width>width || id.height>height){
		if(id.width/id.height>width/height){
			id.width=width;  
		}else{
			id.height=height;
		}
		if(nwin){
			id.title="ÐÂ´°¿Úä¯ÀÀ";
			id.onclick=function(e){window.open(this.src);}
			id.style.cursor="pointer";
		}
	}
}