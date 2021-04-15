<?php 

$alt = get_option( 'stack_logo_alt_text', 'logo' ); 
$mobile_logo = get_option( 'stack_logo_mobile', '' ); 

?>

<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-holder">
	<?php if(!empty($mobile_logo)) { ?>
		<img class="logo logo-mobile" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url($mobile_logo); ?>" />
	<?php } ?>
	<img class="logo logo-dark" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url(get_option('stack_logo_dark', EBOR_THEME_DIRECTORY . 'style/img/logo-dark.png')); ?>" />
	<img class="logo logo-light" alt="<?php echo esc_attr($alt); ?>" src="<?php echo esc_url(get_option('stack_logo_light', EBOR_THEME_DIRECTORY . 'style/img/logo-light.png')); ?>" />
</a>