//导航高亮
function highlight_subnav(nav_url){
    $('.am-nav >li').find('a[href="'+nav_url+'"]').closest('li').addClass('am-active');
}
//子导航高亮
function highlight_subnav_child(url){
    $('.am-dropdown-content >li.active').removeClass('am-active');
    $('.am-dropdown-content >li').find('a[href="'+url+'"]').closest('li').addClass('am-active');
}
//图片无效默认图片
$(document).ready(function(){
    $("img").error(function() {
        $(this).attr("src", '/Public/Common/image/zwtp.jpg');
    });
});
