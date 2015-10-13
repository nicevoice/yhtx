/**
 * the anmination of home page
 * author:by gaorunqiao 
 * date:2012/11/04
 */


//首页幻灯
//var theSlider = {
//		interval:3000 //时间间隔
//		,indx: 0 //索引
//		,perLen:1000 //每次移动长度
//		,pageCount:3 //图片总数
//		,id_slider_handle:null
//		,init:function(){
//			jQuery('#actionContainer ul').width(theSlider.perLen*theSlider.pageCount);
//			id_slider_handle = window.setInterval( theSlider.theSliderShow,theSlider.interval);
//		}
//		,theSliderShow:function(){
//			//alert(theSlider.perLen*(theSlider.indx-1))
//			if(theSlider.indx>=theSlider.pageCount-1){
//				theSlider.indx = 0;
//				 //window.clearInterval(id_slider_handle);
//				//jQuery('#actionContainer ul').animate({'margin-left':-(theSlider.perLen*(theSlider.indx))+'px'},1000);
//			}else{
//				theSlider.indx++;
//			}
//			jQuery('#actionContainer ul').animate({'margin-left':-(theSlider.perLen*(theSlider.indx))+'px'},1000);
//		}
//		,pre:function(){
//			
//		}
//		,next:function(){
//			
//		}
//};


$(function () {
	var carousel = $('#mycarousel'),
		slides = carousel.children('li'),
		references =$('#actionOpt').find('a');
		
	if(references.length<=1) return;

	function updateReferences(index) {
		references.removeClass('checked2');
		references.eq(index).addClass('checked2');
	}
	carousel.jcarousel({
		auto: 5,
		scroll: 1,
		wrap: 'circular',
		buttonNextHTML: '<a href="javascript:;" id="nextCircle2" class="actArrow"> </a>',
		buttonPrevHTML: '<a href="javascript:;" id="preCircle2" class="actArrow"> </a>',
		initCallback: function (jc) {
			references.each(function (i) {
				slides.eq(i).attr('ref', i);
				$(this)
					.one('click', function (e) {
						jc.options.auto = 0;
					})
					.click(function (e) {
						e.preventDefault();
						updateReferences(i);
						jc.scroll(i+1);
					});
			});
		},
		itemVisibleInCallback: {
			onBeforeAnimation: function (jc, li, index, action) {
				var ref_index = $(li).attr('ref');
				references.removeClass('checked2');
				references.eq(ref_index).addClass('checked2');
			}
		}
	});
});