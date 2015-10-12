$('.plupload').each(function() {
	var ele = this;
	var ccuploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : ele,
		url : sUploadUrl,
		multipart_params: {'from': 'uploadjs'}, //设置你的参数
		flash_swf_url : static_url + '/plupload/Moxie.swf',
		silverlight_xap_url : static_url + '/plupload/Moxie.xap',
		filters : {
			max_file_size : '2mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,jpeg,gif,png"}
			]
		},
		init: {
			FilesAdded: function(up, files) {
				ccuploader.start();
			},
			FileUploaded: function(up, file, ret) {
				eval('var tmp=' + ret.response);
				if (tmp.status == false) {
					alert(tmp.data);
					return false;
				}
				console.log(tmp);
				var file = tmp.data;
				if ($(ele).data('target')) {
					$($(ele).data('target')).val(file);
				}
				if ($(ele).data('img')) {
					var height = $(ele).data('height') || 0;
					var width = $(ele).data('width') || 0;
					$($(ele).data('img')).attr('src', getDFSViewURL(file,width,height));
				}
				if ($(ele).data('callback')) {
					eval($(ele).data('callback') + '(\'' + file + '\','+tmp.file.iWidth+', '+tmp.file.iHeight+')');
				}
			}
		}
	});
	ccuploader.init();
});

function getDFSViewURL(p_sFileKey, p_iWidth, p_iHeight, p_sOption, p_biz) {
    if(!p_sFileKey) {
        return '';
    }
	return sDfsViewUrl+'/'+p_sFileKey;
}