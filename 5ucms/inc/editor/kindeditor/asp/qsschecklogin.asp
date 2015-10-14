<!--#include file="../../../../cacheflag.asp"-->
<% 
' 安全验证
function chklogin(byval Level)
	if len(getlogin("admin", "username")) = 0 then
		response.write "对不起，您没有上传权限！请先登陆后台再进行操作！": response.end
	end if
end function


' 获取登录记忆
Public function getlogin(byval strType,byval strName)
	strType = lcase(strType)
	strName = lcase(strName)
	if strType = "" or strType <> "user" then strType = "admin"
	'if LoginMemory = 1 then
	'	getlogin = Request.Cookies(Cacheflag)("login_" & strType & "_" & strName)
	'else
		getlogin = Session(Cacheflag & "_login_" & strType & "_" & strName)
	'end if
end function
%>


