<?php

// Require Merlin autoloader
require_once get_parent_theme_file_path( '/admin/merlin/vendor/autoload.php' );

// Require Merlin main class
require_once get_parent_theme_file_path( '/admin/merlin/class-merlin.php' );

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin( 
	$config = array(
		'directory'            => 'admin/merlin', // Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'merlin', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => true, // Enable development mode for testing.
		'license_step'         => false, // EDD license activation step.
		'license_required'     => false, // Require the license activation step.
		'license_help_url'     => '', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
		'ready_big_button_url' => '', // Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup', 'foundry' ),
		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'foundry' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'foundry' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'foundry' ),
		'btn-skip'                 => esc_html__( 'Skip', 'foundry' ),
		'btn-next'                 => esc_html__( 'Next', 'foundry' ),
		'btn-start'                => esc_html__( 'Start', 'foundry' ),
		'btn-no'                   => esc_html__( 'Cancel', 'foundry' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'foundry' ),
		'btn-child-install'        => esc_html__( 'Install', 'foundry' ),
		'btn-content-install'      => esc_html__( 'Install', 'foundry' ),
		'btn-import'               => esc_html__( 'Import', 'foundry' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'foundry' ),
		'btn-license-skip'         => esc_html__( 'Later', 'foundry' ),
		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'foundry' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'foundry' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'foundry' ),
		'license-label'            => esc_html__( 'License key', 'foundry' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'foundry' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'foundry' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'foundry' ),
		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'foundry' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'foundry' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'foundry' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'foundry' ),
		'child-header'             => esc_html__( 'Install Child Theme', 'foundry' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'foundry' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'foundry' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'foundry' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'foundry' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'foundry' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'foundry' ),
		'plugins-header'           => esc_html__( 'Install Plugins', 'foundry' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'foundry' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'foundry' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'foundry' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'foundry' ),
		'import-header'            => esc_html__( 'Import Content', 'foundry' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'foundry' ),
		'import-action-link'       => esc_html__( 'Advanced', 'foundry' ),
		'ready-header'             => esc_html__( 'All done. Have fun!', 'foundry' ),
		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'foundry' ),
		'ready-action-link'        => esc_html__( 'Extras', 'foundry' ),
		'ready-big-button'         => esc_html__( 'View your website', 'foundry' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'foundry' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.tommusrhodus.com/contact/', esc_html__( 'Get Theme Support', 'foundry' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'foundry' ) ),
	)
);

/**
* Setup Demo Data Files
*/
function tommusrhodus_setup_merlin_local_import_files() {

		$import_notice_vc = '
			<h3>Ready to Import Stack + Visual Composer Demo Data</h3>
			<p>Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.</p>
			<p>Since you\'re importing Stack + Visual Composer Demo Data, please ensure Visual Composer is enabled in "plugins" and Variant Page Builder is either uninstalled, or disabled.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
		
		$import_notice_variant = '
			<h3>Ready to Import Stack + Variant Demo Data</h3>
			<p>Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.</p>
			<p>Since you\'re importing Stack + Variant Demo Data, please ensure Variant Page Builder is enabled in "plugins" and Visual Composer is either uninstalled, or disabled.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
		
		$import_notice_variant_images = '
			<h3>Ready to Import Variant Demo Images ONLY</h3>
			<p>This will import the required demo images for Variant Page Builder only. This will not add any page or post data.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
				
	    return array(
	        array(
	            'import_file_name'             => 'Stack + Visual Composer Demo Data',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-vc.xml',
	            'import_widget_file_url'       => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-widgets.wie',
	            'import_customizer_file_url'   => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-options.dat',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-vc.png',
	            'import_notice'                => $import_notice_vc,
	        ),
	        array(
	            'import_file_name'             => 'Stack + Variant Demo Data',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-variant.xml',
	            'import_widget_file_url'       => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-widgets.wie',
	            'import_customizer_file_url'   => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-options.dat',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-variant.png',
	            'import_notice'                => $import_notice_variant,
	        ),
	        array(
	            'import_file_name'             => 'Variant Demo Images ONLY',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-variant-images.xml',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-variant.png',
	            'import_notice'                => $import_notice_variant_images,
	        )
	    );
	    
}
add_filter( 'merlin_import_files', 'tommusrhodus_setup_merlin_local_import_files' );

function tommusrhodus_merlin_after_import_setup() {
	
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Standard Navigation', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Sidebar Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
    $top_bar_menu = get_term_by( 'name', 'Top Bar', 'nav_menu' );
    $vertical_menu = get_term_by( 'name', 'Vertical Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary'  => $main_menu->term_id,
            'sidebar'  => $side_menu->term_id,
            'footer'   => $footer_menu->term_id,
            'top-bar'  => $top_bar_menu->term_id,
            'vertical' => $vertical_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home - Landing 1' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}

add_action( 'merlin_after_all_import', 'tommusrhodus_merlin_after_import_setup' );