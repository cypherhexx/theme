jQuery(document).ready(function($) {

	$('#ip2location-tags-notice').click(function() {

		data = {
			action: 'ip2location_tags_admin_notice',
			ip2location_tags_admin_nonce: ip2location_tags_admin.ip2location_tags_admin_nonce
		};

		$.post( ajaxurl, data );
		
		event.preventDefault();

		return false;
	});
	
});