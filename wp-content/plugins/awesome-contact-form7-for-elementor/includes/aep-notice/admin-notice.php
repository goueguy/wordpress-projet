<?php

if( get_option( 'aep_notice' ) != 'never_show' ) {
    add_action( 'admin_notices', 'aep_notice_options' );
}

function aep_notice_options() {
      ?>

      <div class='notice aep-notice'>
        <div class="aep-notice-logo">
            <img src="<?php echo plugins_url( '/', __FILE__ ) .'/img/aep-inf-img.jpg'?>" >
        </div>
        <div class="aep-notice-content">
            <h3>Awesome Contact Form7 for Elementor</h3>
            <p>Thank you for using <strong>Awesome Contact Form7 for Elementor</strong>. If you love this plugin please make a small donation! Your small donation will help us in this COVID 19 situation. Or give us a five-star review of our motivation. Thanks.</p>
            
			<p class="aep-links"><a href="https://www.paypal.com/paypalme/HHaq" class="donate"> <i class="icon-donation"></i> Donate</a>  | 
            <a href="https://wordpress.org/support/plugin/awesome-contact-form7-for-elementor/reviews/?filter=5" class="review">
            <i class="icon-star-empty"></i> 
            Leave a Review</a> | 
            <a href="javascript:void()" class="never-show"><i class="icon-cancel-circle"></i> Never Show</a>

            <!--a href="#review_link" class="later">Maybe later</a-->
            </p>
        </div>
      </div>
<?php
}

add_action( 'admin_enqueue_scripts', 'add_script' );
function add_script() {
	wp_register_script( 'notice-update',  plugins_url( '/', __FILE__ ) . '/js/update-notice.js','','1.0', false );
	wp_enqueue_style( 'notice-update-css',  plugins_url( '/', __FILE__ ) . '/css/aep-notice.css',array());
	
	wp_localize_script( 'notice-update', 'notice_params', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));
	
	wp_enqueue_script(  'notice-update' );
}
add_action( 'wp_ajax_never_show', 'never_show' );

function never_show() {
    update_option( 'aep_notice', 'never_show' );
}


add_action( 'wp_ajax_later', 'later' );

function later() {
     update_option( 'aep_notice', 'later' );
}