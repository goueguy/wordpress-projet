<div class="bar__module centered-right-menu">
	<?php  
		if ( has_nav_menu( 'primary' ) ){
		    wp_nav_menu( 
		    	array(
			        'theme_location'    => 'centered-inline-right',
			        'depth'             => 3,
			        'container'         => false,
			        'container_class'   => false,
			        'menu_class'        => 'menu-horizontal text-left',
			        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
			        'walker'            => new ebor_medium_rare_bootstrap_navwalker()
		        )
		    ); 
		}
	?>
</div>