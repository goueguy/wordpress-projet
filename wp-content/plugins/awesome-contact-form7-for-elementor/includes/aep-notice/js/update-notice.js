jQuery( document ).ready( function() {

	jQuery( document ).on( 'click', 'a', function(event) {
        if( jQuery(event.target).hasClass('never-show')) {
            var data = {
                action: 'never_show',
            };
        }
        /*if( jQuery(event.target).hasClass('later')) {
            var data = {
                action: 'later',
            };
        }*/
		
		jQuery.post( notice_params.ajaxurl, data, function() {
            if(data){
                jQuery('.aep-notice').fadeOut();
            }
		});
    });

});