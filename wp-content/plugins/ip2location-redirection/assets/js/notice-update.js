jQuery(document).ready(function($) {

    $('#ip2location-redirection-notice').click(function() {

        $.post(ajaxurl, {
            action: 'ip2location_redirection_admin_notice',
            ip2location_redirection_admin_nonce: ip2location_redirection_admin.ip2location_redirection_admin_nonce
        });

        event.preventDefault();

        return false;
    });

});