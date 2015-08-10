/**
 * 使用说明
 * 元素加validate属性，例：validate="规则"
 * !规则 == 任何时候都要判断
 * *规则 == 保存的时候不判断
 * 规则 == 不为空的时候判断
 *
 * 具体规则：
 * required:true
 * required:必须输入楼盘ID
 * length:1-3
 * cleng:1-3
 * alpha:1-3
 * chinese:1-3
 * digit:true
 * digits:1-3
 * float:true
 * range:1-10
 * range:0.5-20.5
 * max:10
 * min:1
 * mobile:true
 * email:true
 * url:true
 * date:true
 * datetime:true
 * equal:#selector
 * eaual:.selector
 * regexp:/^xx$/
 * regexp:/^xx$/:请输入正确的格式
 * function:funname
 * ajax:url
 * jsonp:url
 * checkbox:#selector:1-3
 * checkbox:.selector:1
 * radio:#selector
 */


(function($) {
	$.fn.validate = function(options) {
		if (this.length == 0) {
			return;
		}
		// Add novalidate tag if HTML5.
		// this.attr("novalidate", "novalidate");

		var validator = $.data(this[0], "validator");
		if (validator) {
			return validator;
		}

		validator = new $.validator(this[0], options);
		$.data(this[0], "validator", validator);
	}

	$.validator = function(form, options) {
		this.settings = $.extend({}, $.validator.defaults, options);
		this.form = $(form);
		this.init();
	}

	$.validator.rules = {
		"required" : [/^[\w\W]+$/, "必填字段"],
		"length" : ["/^[\\w\\W]{{0},{1}}$/","请输入{0}到{1}位任意字符","当前已输入{0}位"],
		"clength" : [
		    function(v,args){
		    	var t = args.split('-');
		    	var len = v.replace(/[\u0391-\uFFE5]/g, '**').length / 2;
		    	var status = len >= parseInt(t[0], 10) && len <= parseInt(t[1], 10) ? true : false;
		    	return [status,len];
			}, "请输入{0}到{1}位任意字","当前已输入{0}位"],
		"alpha" : ["/^[a-zA-Z]{{0},{1}}$/","请输入{0}到{1}位英文字母","当前已输入{0}位"],
		"alnum" : ["/^[a-zA-Z0-9]{{0},{1}}$/","请输入{0}到{1}位英文字母和数字","当前已输入{0}位"],
		"chinese" : ["/^[\\u0391-\\uFFE5]{{0},{1}}$/","请输入{0}到{1}位汉字","当前已输入{0}位"],
		"digit" : [/^[0-9]+$/, "请输入整数"],
		"digits" : ["/^[0-9]{{0},{1}}$/","请输入{0}到{1}位整数","当前已输入{0}位"],
		"float" : [/^[0-9\.]+$/, "请输入数字"],
		"range" : [function(v,args){var t = args.split('-'); return v >= parseInt(args[0], 10) && v <= parseInt(args[1]);},"请输入一个介于 {0} 和 {1} 之间的值"],
		"max" : [function(v,args){return v <= parseInt(args, 10);},"请输入一个最大为{0}的值"],
		"min" : [function(v,args){return v >= parseInt(args, 10);},"请输入一个最小为{0}的值"],
		"mobile" : [/^1[34578][0-9]{9}$/, "请输入正确的手机号码"],
		"email" : [/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+[._a-z\p{L}0-9-]*\.[a-z0-9]+$/i,"请输入正确的邮箱地址"],
		"url" : [/^[~:#,%&_=\(\)\.\? \+\-@\/a-zA-Z0-9]+$/,"请输入正确的网址"],
		"date" : [/^([0-9]{4})-((0?[1-9])|(1[0-2]))-((0?[1-9])|([1-2][0-9])|(3[01]))$/,"请输入正确的日期"],
		"datetime" : [/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/,"请输入正确的时间"],
		"equal" : [function(v,args){return $(args).val() == v;},"请再次输入相同的值"],
		"regexp" : ['', '请输入正确的格式'],
		"function" : ['', '请输入正确的格式'],
		"ajax" : [function(v,args){
				var ret = null;
				$.ajax({
					url:args,
		            dataType:"json",
		            async:false,
		            data:{v:v},
		            success:function(data){
		            	ret = [data.status, data.data];
		            }
		        });
				return ret;
			}, '请输入正确的格式'],
		"jsonp" : [function(v,args){
				var ret = null;
				$.ajax({
					url:args,
		            dataType:"jsonp",
		            jsonp:"jsonpcallback",
		            async:false,
		            data:{v:v},
		            success:function(data){
		            	ret = [data.status, data.data];
		            }
		        });
				return ret;
			}, '请输入正确的格式'],
		"checkbox" : [function(v,args){
		    	var t = args.split(':');
		    	var e = t[0].split('|');
		    	var selector = [];
		    	for (var i = 0; i < e.length; i++) {
		    		selector.push(':checked[name="' + e[i] + '"]');
		    	}
		    	var l = $(selector.join(',')).length;
		    	var n = t[1].split('-');
		    	var item = t[2] || ''
		    	if (n.length == 1) {
		    		return [l >= parseInt(n[0], 10), "请至少选择"+n[0]+"个" + item];
		    	} else {
		    		return [l >= parseInt(n[0], 10) && l <= parseInt(n[1], 10), "请至少选择"+n[0]+"到"+n[1]+"个" + item]
		    	}
			}, "请至少选择{0}个"],
		"radio" : [function(v,args){
				var t = args.split(':');
		    	var e = t[0].split('|');
		    	var selector = [];
		    	for (var i = 0; i < e.length; i++) {
		    		selector.push(':checked[name="' + e[i] + '"]');
		    	}
		    	var l = $(selector.join(',')).length;
		    	if (t.length == 2) {
		    		return [l >= 1, "请选择一个" + t[1]];
		    	} else {
		    		return l >= 1;
		    	}
			}, "请选择一个"]
	}

	$.validator.eval = function (str) {
		try {
			var tmp = eval('(' + str + ')');
		} catch (e) {
			var tmp = '';
		}
		return tmp;
	}

	$.validator.tipHandler = function(e, status, msg) {
		var curr = $(e);
		var tipele = curr.parent();
		if (status == 2) {
			tipele.addClass('has-success');
			tipele.removeClass('has-error');
			tipele.find('span').addClass('glyphicon-ok');
			tipele.find('span').removeClass('glyphicon-remove');
			tipele.parent().find('.validate_checktip').html('');
		} else if (status == 1) {
			tipele.addClass('has-success');
			tipele.removeClass('has-error');
			tipele.find('span').addClass('glyphicon-ok');
			tipele.find('span').removeClass('glyphicon-remove');
			tipele.parent().find('.validate_checktip').html('');
			tipele.parent().find('.validate_checktip').removeClass('bg-danger');
		} else {
			tipele.addClass('has-error');
			tipele.removeClass('has-success');
			tipele.find('span').addClass('glyphicon-remove');
			tipele.find('span').removeClass('glyphicon-ok');
			tipele.parent().find('.validate_checktip').html(msg);
		}
	}

	$.validator.defaults = {
		submitHandler : null,
		tipHandler : $.validator.tipHandler
	}

	$.validator.prototype = {
		submit: function() {
			var ok = true;
			var validator = $.data(this, 'validator');
			try {
				var element = null;
				var first = null;
				validator.form.find('[validate]').each(function(){
					element = this;
					if (! validator.check(this)) {
						if (first == null) {
							first = element;
						}
						ok = false;
					}
				});
			} catch (e) {
				ok == false;
				console.log(element, e);
			}

			if (ok == false) {
				$(first).focus();
				return false;
			}

			if (typeof validator.settings.submitHandler == 'function') {
				return validator.settings.submitHandler(this);
			}
			return true;
		},
		check: function(element) {
			// 如果是事件则，取目标源
			if (element && element.target) {
				element = element.target
			}

			var rule = $.data(element, 'rule') || $.data($(element).parents('[validate]')[0], 'rule');
			if (typeof rule != 'object') {
				return 1;
			}

			var form = $(element).parents('form')[0];
			var formmust = $(form).attr('must') || 0;
			var status = 1;
			var value = $.trim($(element).val());
			var match = rule.match;
			var tipmsg = rule.msg;
			var args = rule.args;
			var validator = $.data(form, 'validator');

			//非必镇字段，且值为空时，不校验
			if (value == '' && rule.must != '!') {
				if (rule.must == '' || rule.must == '*' && formmust == 0) {
					status = 2;
					validator.settings.tipHandler(element, status, tipmsg);
					return status;
				}
			}

			if (typeof rule.tip == 'string') {
				tipmsg += ',' + rule.tip;
			}
			if (match instanceof RegExp) {
				if (! match.test(value)) {
					status = 0;
				}
				tipmsg = tipmsg.replace(/\{0\}/g, value.length);
			} else if (typeof match == 'function') {
				var ret = match(value, args, element);
				if (typeof ret == 'object') {
					status = ret[0];
					if (typeof ret[1] == 'string') {
						tipmsg = ret[1];
					} else {
						tipmsg = tipmsg.replace(/\{0\}/g, ret[1]);
					}
				} else {
					status = ret;
				}
			}
			validator.settings.tipHandler(element, status, tipmsg);
			if (typeof status == "boolean") {
				status = status ? 1 : 0;
			}

			return status;
		},
		init: function() {
			var elements = this.form.find('[validate]');
	    	var selector = [];
			elements.each(function() {
				var attr = $(this).attr('validate');
				if (attr.length == 0) {
					return;
				}

				// 验证规则
				var attr = $.trim($(this).attr('validate'));
				var must = '';
				if (attr[0] == '*') {
					must = '*';
				} else if (attr[0] == '!') {
					must = '!';
				}
				if (must != '') {
					attr = attr.substr(1);
				}
				attr = attr.split(':');
				if (attr.length == 1) {
					console.log('Validate rule args error:' + $(this).attr('validate'));
					return;
				}

				var name = attr.shift();
				if (! $.validator.rules[name]) {
					console.log('Validate rule match error:' + $(this).attr('validate'));
					return;
				}

				var match = $.validator.rules[name][0];
				var msg = $.validator.rules[name][1];
				var args = attr.join(':');

				if ($(this).attr('placeholder')) {
					msg = $(this).attr('placeholder');
				}

				if (name == "checkbox" || name == "radio") {
					var t = args.split(':');
			    	var e = t[0].split('|');
			    	for (var i = 0; i < e.length; i++) {
			    		selector.push(':' + name + '[name="' + e[i] + '"]');
			    	}
				} else if (name == "required") {
					if (args != "true" && args != "1") {
						msg = args;
					}
				} else if (name == 'regexp') {
					match = args;
					msg = $(this).attr('placeholder') || $.validator.rules[name][1];
				} else if (name == 'function') {
					match = $.validator.eval(args);
					if (typeof match != 'function') {
						console.log('Validate rule match error:' + args + ' not found function!');
						return;
					}
				} else {
					var tmp = args.split('-');
					for (var i = 0; i < tmp.length; i++) {
						var regexp = $.validator.eval('/\\{' + i + '\\}/g');
						msg = msg.replace(regexp, tmp[i]);
						if (typeof match == 'string') {
							match = match.replace(regexp, tmp[i]);
						}
					}
				}

				if (typeof match == 'string') {
					var regexp = $.validator.eval(match);
					if (! regexp instanceof RegExp) {
						console.log('RegExp error:' + match);
						return false;
					}
					match = regexp;
				}

				var rule = {
					'name': name,
					'match': match,
					'msg': msg,
					'tip': $.validator.rules[name][2],
					'must': must,
					'args': args
				};
				// console.log(rule);
				$.data(this, 'rule', rule);
				$(this).attr('placeholder', rule.msg);
			});

			this.form.bind('submit', this.submit);
			elements.bind('click', this.check);
			elements.bind('change', this.check);
			elements.bind('keyup', this.check);
			//console.log(selector.join(','));
			if (selector.length > 0) {
				$(selector.join(',')).bind('click', this.check);
				$(selector.join(',')).bind('change', this.check);
				$(selector.join(',')).bind('keyup', this.check);
			}
		}
	}
})(jQuery);