jQuery(document).ready(function(){	
        
	jQuery("#wpp-review-already, #wpp-dismiss").click(function(){
		jQuery.ajax({
			type: "POST",
			async: true,
			data: { action: "wpp_dismiss_notice", nonce: wpp_ajax.nonce },
			url : wpp_ajax.ajaxurl,
			dataType: "html",
			success: function (data) {
				jQuery(".wpp-notice").hide();          
			},
			error: function (err)
				{ alert(err.responseText);}
		});
	});

	jQuery("#wpp-review-later").click(function(){
		jQuery.ajax({
			type: "POST",
			async: true,
			data: { action: "wpp_set_review_later", nonce: wpp_ajax.nonce },
			url : wpp_ajax.ajaxurl,
			dataType: "html",
			success: function (data) {
				jQuery(".wpp-notice").hide();          
			},
			error: function (err)
				{ alert(err.responseText);}
		});
	});

});
