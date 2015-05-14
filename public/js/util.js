var util = {
	toUrl : function(url) {
		location.href = url;
	},
	
	ajax : function(url, data, callback, method, cache) {
		data = data || {};
		callback = callback || null;
		method = method || 'get';
		cache = (typeof cache != 'undefined')? cache : true;
		$.ajax({
			url : url,
			dataType : 'html',
			data: data,
			async : true,
			cache : cache,
			type: method,
			success : callback,
		});			
	},
	triggerBtn : function(e, target) {
		if (e.keyCode == 13) {
			$(target).click();	
			e.preventDefault();
		}		
	},
	
	moveTo : function(id) {
		$('html, body').animate({
			scrollTop: $(id).offset().top-70
		}, 300);
	}
}