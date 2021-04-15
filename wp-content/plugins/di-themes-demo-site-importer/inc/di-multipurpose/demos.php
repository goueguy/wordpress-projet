<?php

/**
 * Return if direct access.
 */
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define class if not exist.
 */
if ( ! class_exists( 'DTDSI_Demos' ) ) {

	/**
	 * The DTDSI_Demos class.
	 */
	class DTDSI_Demos {

		/**
		 * [__construct description]
		 */
		public function __construct() {

			// Return if not admin screen and customize preview screen.
			if ( ! is_admin() || is_customize_preview() ) {
				return;
			}

			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				require_once( DTDSI_PATH .'/inc/di-multipurpose/importers/class-helpers.php' );
				require_once( DTDSI_PATH .'/inc/di-multipurpose/class-install-demos.php' );
			}
			
			add_action( 'admin_init', array( $this, 'init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_filter( 'upload_mimes', array( $this, 'allow_xml_uploads' ) );
			add_action( 'admin_footer', array( $this, 'popup' ) );
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, array( $this, 'plugin_action_links' ), 10, 4 );

		}

		/**
		 * Ajax callbacks.
		 * @return [type] [description]
		 */
		public function init() {

			// try to set no limit to execution time.
			set_time_limit( 0 );

			// Demos popup ajax
			add_action( 'wp_ajax_dmdi_ajax_get_demo_data', array( $this, 'ajax_demo_data' ) );
			add_action( 'wp_ajax_dmdi_ajax_required_plugins_activate', array( $this, 'ajax_required_plugins_activate' ) );

			// Get data to import
			add_action( 'wp_ajax_dmdi_ajax_get_import_data', array( $this, 'ajax_get_import_data' ) );

			// Import XML file
			add_action( 'wp_ajax_dmdi_ajax_import_xml', array( $this, 'ajax_import_xml' ) );

			// Import customizer settings
			add_action( 'wp_ajax_dmdi_ajax_import_theme_settings', array( $this, 'ajax_import_theme_settings' ) );

			// Import widgets
			add_action( 'wp_ajax_dmdi_ajax_import_widgets', array( $this, 'ajax_import_widgets' ) );

			// After import
			add_action( 'wp_ajax_dmdi_after_import', array( $this, 'ajax_after_import' ) );

		}

		/**
		 * Load scripts on install demo page only
		 *
		 */
		public static function scripts() {

			global $pagenow;

			if ( 'themes.php' == $pagenow && isset( $_GET['page'] )  && 'dmdi-panel-install-demos' == $_GET['page'] ) {

				// CSS
				wp_enqueue_style( 'dmdi-demos-style', DTDSI_URL . 'assets/css/demos.css', array(), DTDSI_VERSION, 'all' );

				// JS
				wp_enqueue_script( 'dmdi-demos-js', DTDSI_URL . 'assets/js/demos.js', array( 'jquery', 'wp-util', 'updates' ), DTDSI_VERSION, true );

				wp_localize_script( 'dmdi-demos-js', 'dmdiDemos', array(
					'ajaxurl' 					=> admin_url( 'admin-ajax.php' ),
					'demo_data_nonce' 			=> wp_create_nonce( 'get-demo-data' ),
					'dmdi_import_data_nonce' 	=> wp_create_nonce( 'dmdi_import_data_nonce' ),
					'content_importing_error' 	=> esc_html__( 'There was a problem during the importing process resulting in the following error from your server:', 'di-themes-demo-site-importer' ),
					'button_activating' 		=> esc_html__( 'Activating', 'di-themes-demo-site-importer' ) . '&hellip;',
					'button_active' 			=> esc_html__( 'Active', 'di-themes-demo-site-importer' ),
				) );

			}

		}

		/**
		 * Allows xml uploads so we can import from server
		 *
		 */
		public function allow_xml_uploads( $mimes ) {
			$mimes = array_merge( $mimes, array(
				'xml' 	=> 'application/xml'
			) );
			return $mimes;
		}

		/**
		 * Available demos - Get demos data to add them in the Demo Import
		 *
		 */
		public static function get_demos_data() {

			// Demos url
			$url = 'http://demo.dithemes.com/di-multipurpose/demo-files/';

			$data = array(

				'construction' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'construction/construction.xml',
					'theme_settings' 	=> $url . 'construction/construction.dat',
					'widgets_file'  	=> $url . 'construction/construction.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'real-estate' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'real-estate/real-estate.xml',
					'theme_settings' 	=> $url . 'real-estate/real-estate.dat',
					'widgets_file'  	=> $url . 'real-estate/real-estate.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'agency' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'agency/agency.xml',
					'theme_settings' 	=> $url . 'agency/agency.dat',
					'widgets_file'  	=> $url . 'agency/agency.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'event-planner' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'event-planner/event-planner.xml',
					'theme_settings' 	=> $url . 'event-planner/event-planner.dat',
					'widgets_file'  	=> $url . 'event-planner/event-planner.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'fashion-designer' => array(
					'categories'        => array( 'Business', 'eCommerce' ),
					'xml_file'     		=> $url . 'fashion-designer/fashion-designer.xml',
					'theme_settings' 	=> $url . 'fashion-designer/fashion-designer.dat',
					'widgets_file'  	=> $url . 'fashion-designer/fashion-designer.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'yes', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'woocommerce',
								'init'  	=> 'woocommerce/woocommerce.php',
								'name'  	=> 'WooCommerce',
							),
							array(
								'slug'  	=> 'yith-woocommerce-quick-view',
								'init'  	=> 'yith-woocommerce-quick-view/init.php',
								'name'  	=> 'WooCommerce Quick View',
							),

							array(
								'slug'  	=> 'ti-woocommerce-wishlist',
								'init'  	=> 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php',
								'name'  	=> 'WooCommerce Wishlist',
							),
						),
					),
				),

				'interior' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'interior/interior.xml',
					'theme_settings' 	=> $url . 'interior/interior.dat',
					'widgets_file'  	=> $url . 'interior/interior.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'mobile-app' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'mobile-app/mobile-app.xml',
					'theme_settings' 	=> $url . 'mobile-app/mobile-app.dat',
					'widgets_file'  	=> $url . 'mobile-app/mobile-app.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'vacation' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'vacation/vacation.xml',
					'theme_settings' 	=> $url . 'vacation/vacation.dat',
					'widgets_file'  	=> $url . 'vacation/vacation.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'vacation' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'vacation/vacation.xml',
					'theme_settings' 	=> $url . 'vacation/vacation.dat',
					'widgets_file'  	=> $url . 'vacation/vacation.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'bakery' => array(
					'categories'        => array( 'Business', 'eCommerce' ),
					'xml_file'     		=> $url . 'bakery/bakery.xml',
					'theme_settings' 	=> $url . 'bakery/bakery.dat',
					'widgets_file'  	=> $url . 'bakery/bakery.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'yes', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'woocommerce',
								'init'  	=> 'woocommerce/woocommerce.php',
								'name'  	=> 'WooCommerce',
							),
							array(
								'slug'  	=> 'yith-woocommerce-quick-view',
								'init'  	=> 'yith-woocommerce-quick-view/init.php',
								'name'  	=> 'WooCommerce Quick View',
							),

							array(
								'slug'  	=> 'ti-woocommerce-wishlist',
								'init'  	=> 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php',
								'name'  	=> 'WooCommerce Wishlist',
							),
						),
					),
				),

				'salon-spa' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'salon-spa/salon-spa.xml',
					'theme_settings' 	=> $url . 'salon-spa/salon-spa.dat',
					'widgets_file'  	=> $url . 'salon-spa/salon-spa.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'jewellery' => array(
					'categories'        => array( 'Business', 'eCommerce' ),
					'xml_file'     		=> $url . 'jewellery/jewellery.xml',
					'theme_settings' 	=> $url . 'jewellery/jewellery.dat',
					'widgets_file'  	=> $url . 'jewellery/jewellery.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'yes', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'woocommerce',
								'init'  	=> 'woocommerce/woocommerce.php',
								'name'  	=> 'WooCommerce',
							),
							array(
								'slug'  	=> 'yith-woocommerce-quick-view',
								'init'  	=> 'yith-woocommerce-quick-view/init.php',
								'name'  	=> 'WooCommerce Quick View',
							),

							array(
								'slug'  	=> 'ti-woocommerce-wishlist',
								'init'  	=> 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php',
								'name'  	=> 'WooCommerce Wishlist',
							),
						),
					),
				),

				'forum' => array(
					'categories'        => array( 'Forum' ),
					'xml_file'     		=> $url . 'forum/forum.xml',
					'theme_settings' 	=> $url . 'forum/forum.dat',
					'widgets_file'  	=> $url . 'forum/forum.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'bbpress',
								'init'  	=> 'bbpress/bbpress.php',
								'name'  	=> 'bbPress',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'architecture' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'architecture/architecture.xml',
					'theme_settings' 	=> $url . 'architecture/architecture.dat',
					'widgets_file'  	=> $url . 'architecture/architecture.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'studio' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'studio/studio.xml',
					'theme_settings' 	=> $url . 'studio/studio.dat',
					'widgets_file'  	=> $url . 'studio/studio.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'personal-trainer' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'personal-trainer/personal-trainer.xml',
					'theme_settings' 	=> $url . 'personal-trainer/personal-trainer.dat',
					'widgets_file'  	=> $url . 'personal-trainer/personal-trainer.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'one-page-agency' => array(
					'categories'        => array( 'Business', 'One Page' ),
					'xml_file'     		=> $url . 'one-page-agency/one-page-agency.xml',
					'theme_settings' 	=> $url . 'one-page-agency/one-page-agency.dat',
					'widgets_file'  	=> $url . 'one-page-agency/one-page-agency.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'one-page-fitness' => array(
					'categories'        => array( 'Business', 'One Page' ),
					'xml_file'     		=> $url . 'one-page-fitness/one-page-fitness.xml',
					'theme_settings' 	=> $url . 'one-page-fitness/one-page-fitness.dat',
					'widgets_file'  	=> $url . 'one-page-fitness/one-page-fitness.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'one-page-shop' => array(
					'categories'        => array( 'Business', 'eCommerce', 'One Page' ),
					'xml_file'     		=> $url . 'one-page-shop/one-page-shop.xml',
					'theme_settings' 	=> $url . 'one-page-shop/one-page-shop.dat',
					'widgets_file'  	=> $url . 'one-page-shop/one-page-shop.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'yes', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'woocommerce',
								'init'  	=> 'woocommerce/woocommerce.php',
								'name'  	=> 'WooCommerce',
							),
							array(
								'slug'  	=> 'yith-woocommerce-quick-view',
								'init'  	=> 'yith-woocommerce-quick-view/init.php',
								'name'  	=> 'WooCommerce Quick View',
							),

							array(
								'slug'  	=> 'ti-woocommerce-wishlist',
								'init'  	=> 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php',
								'name'  	=> 'WooCommerce Wishlist',
							),
						),
					),
				),

				'one-page-studio' => array(
					'categories'        => array( 'Business', 'One Page' ),
					'xml_file'     		=> $url . 'one-page-studio/one-page-studio.xml',
					'theme_settings' 	=> $url . 'one-page-studio/one-page-studio.dat',
					'widgets_file'  	=> $url . 'one-page-studio/one-page-studio.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'one-page-portfolio' => array(
					'categories'        => array( 'Business', 'One Page' ),
					'xml_file'     		=> $url . 'one-page-portfolio/one-page-portfolio.xml',
					'theme_settings' 	=> $url . 'one-page-portfolio/one-page-portfolio.dat',
					'widgets_file'  	=> $url . 'one-page-portfolio/one-page-portfolio.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'clinic' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'clinic/clinic.xml',
					'theme_settings' 	=> $url . 'clinic/clinic.dat',
					'widgets_file'  	=> $url . 'clinic/clinic.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'law-office' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'law-office/law-office.xml',
					'theme_settings' 	=> $url . 'law-office/law-office.dat',
					'widgets_file'  	=> $url . 'law-office/law-office.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'yoga' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'yoga/yoga.xml',
					'theme_settings' 	=> $url . 'yoga/yoga.dat',
					'widgets_file'  	=> $url . 'yoga/yoga.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'blog' => array(
					'categories'        => array( 'Business', 'Blog' ),
					'xml_file'     		=> $url . 'blog/blog.xml',
					'theme_settings' 	=> $url . 'blog/blog.dat',
					'widgets_file'  	=> $url . 'blog/blog.wie',
					'front_is'			=> 'posts', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'box-blog' => array(
					'categories'        => array( 'Business', 'Blog' ),
					'xml_file'     		=> $url . 'box-blog/box-blog.xml',
					'theme_settings' 	=> $url . 'box-blog/box-blog.dat',
					'widgets_file'  	=> $url . 'box-blog/box-blog.wie',
					'front_is'			=> 'posts', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'adventure-tours' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'adventure-tours/adventure-tours.xml',
					'theme_settings' 	=> $url . 'adventure-tours/adventure-tours.dat',
					'widgets_file'  	=> $url . 'adventure-tours/adventure-tours.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'repairing-cleaning' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'repairing-cleaning/repairing-cleaning.xml',
					'theme_settings' 	=> $url . 'repairing-cleaning/repairing-cleaning.dat',
					'widgets_file'  	=> $url . 'repairing-cleaning/repairing-cleaning.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'advertising-agency' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'advertising-agency/advertising-agency.xml',
					'theme_settings' 	=> $url . 'advertising-agency/advertising-agency.dat',
					'widgets_file'  	=> $url . 'advertising-agency/advertising-agency.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'night-club' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'night-club/night-club.xml',
					'theme_settings' 	=> $url . 'night-club/night-club.dat',
					'widgets_file'  	=> $url . 'night-club/night-club.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'sport-event' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'sport-event/sport-event.xml',
					'theme_settings' 	=> $url . 'sport-event/sport-event.dat',
					'widgets_file'  	=> $url . 'sport-event/sport-event.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'hotel-resort' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'hotel-resort/hotel-resort.xml',
					'theme_settings' 	=> $url . 'hotel-resort/hotel-resort.dat',
					'widgets_file'  	=> $url . 'hotel-resort/hotel-resort.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'opal-hotel-room-booking',
								'init'  	=> 'opal-hotel-room-booking/opal-hotel-room-booking.php',
								'name'  	=> 'Opal Hotel Room Booking',
							),
						),
					),
				),

				'bar-restaurant' => array(
					'categories'        => array( 'Business', 'eCommerce' ),
					'xml_file'     		=> $url . 'bar-restaurant/bar-restaurant.xml',
					'theme_settings' 	=> $url . 'bar-restaurant/bar-restaurant.dat',
					'widgets_file'  	=> $url . 'bar-restaurant/bar-restaurant.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'yes', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'woocommerce',
								'init'  	=> 'woocommerce/woocommerce.php',
								'name'  	=> 'WooCommerce',
							),
							array(
								'slug'  	=> 'restaurant-addon-for-elementor',
								'init'  	=> 'restaurant-addon-for-elementor/restaurant-addon-for-elementor.php',
								'name'  	=> 'Restaurant Addon for Elementor',
							),
						),
					),
				),

				'consulting' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'consulting/consulting.xml',
					'theme_settings' 	=> $url . 'consulting/consulting.dat',
					'widgets_file'  	=> $url . 'consulting/consulting.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
						),
					),
				),

				'school' => array(
					'categories'        => array( 'Business' ),
					'xml_file'     		=> $url . 'school/school.xml',
					'theme_settings' 	=> $url . 'school/school.dat',
					'widgets_file'  	=> $url . 'school/school.wie',
					'front_is'			=> 'page', // 'page' or 'posts'
					'is_shop'			=> 'no', // 'yes' or 'no'
					'required_plugins'  => array(
						'free' => array(
							array(
								'slug'  	=> 'elementor',
								'init'  	=> 'elementor/elementor.php',
								'name'  	=> 'Elementor',
							),
							array(
								'slug'  	=> 'contact-form-7',
								'init'  	=> 'contact-form-7/wp-contact-form-7.php',
								'name'  	=> 'Contact Form 7',
							),
							array(
								'slug'  	=> 'post-grid-elementor-addon',
								'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
								'name'  	=> 'Elementor Post Grid Addon',
							),
							array(
								'slug'  	=> 'vertical-news-scroller',
								'init'  	=> 'vertical-news-scroller/newsScroller.php',
								'name'  	=> 'Vertical News Scroller',
							),
						),
					),
				),
				

			);

			/*
			* Possible plugins
			*
			*
				array(
					'slug'  	=> 'woocommerce',
					'init'  	=> 'woocommerce/woocommerce.php',
					'name'  	=> 'WooCommerce',
				),

				array(
					'slug'  	=> 'megamenu',
					'init'  	=> 'megamenu/megamenu.php',
					'name'  	=> 'Max Mega Menu',
				),

				array(
					'slug'  	=> 'yith-woocommerce-quick-view',
					'init'  	=> 'yith-woocommerce-quick-view/init.php',
					'name'  	=> 'WooCommerce Quick View',
				),

				array(
					'slug'  	=> 'ti-woocommerce-wishlist',
					'init'  	=> 'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php',
					'name'  	=> 'WooCommerce Wishlist',
				),

				array(
					'slug'  	=> 'woocommerce-pdf-invoices-packing-slips',
					'init'  	=> 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php',
					'name'  	=> 'PDF Invoices & Packing Slips',
				),

				array(
					'slug'  	=> 'bbpress',
					'init'  	=> 'bbpress/bbpress.php',
					'name'  	=> 'bbPress',
				),

				array(
					'slug'  	=> 'contact-form-7',
					'init'  	=> 'contact-form-7/wp-contact-form-7.php',
					'name'  	=> 'Contact Form 7',
				),

				array(
					'slug'  	=> 'elementor',
					'init'  	=> 'elementor/elementor.php',
					'name'  	=> 'Elementor',
				),

				array(
					'slug'  	=> 'post-grid-elementor-addon',
					'init'  	=> 'post-grid-elementor-addon/post-grid-elementor-addon.php',
					'name'  	=> 'Elementor Post Grid Addon',
				),

				array(
					'slug'  	=> 'opal-hotel-room-booking',
					'init'  	=> 'opal-hotel-room-booking/opal-hotel-room-booking.php',
					'name'  	=> 'Opal Hotel Room Booking',
				),

				array(
					'slug'  	=> 'restaurant-addon-for-elementor',
					'init'  	=> 'restaurant-addon-for-elementor/restaurant-addon-for-elementor.php',
					'name'  	=> 'Restaurant Addon for Elementor',
				),

				array(
					'slug'  	=> 'vertical-news-scroller',
					'init'  	=> 'vertical-news-scroller/newsScroller.php',
					'name'  	=> 'Vertical News Scroller',
				),


			 */

			// Return
			return apply_filters( 'dmdi_demos_data', $data );

		}

		/**
		 * Get the category list of all categories used in the predefined demo imports array.
		 *
		 */
		public static function get_demo_all_categories( $demo_imports ) {
			$categories = array();

			foreach ( $demo_imports as $item ) {
				if ( ! empty( $item['categories'] ) && is_array( $item['categories'] ) ) {
					foreach ( $item['categories'] as $category ) {
						$categories[ sanitize_key( $category ) ] = $category;
					}
				}
			}

			if ( empty( $categories ) ) {
				return false;
			}

			return $categories;
		}

		/**
		 * Return the concatenated string of demo import item categories.
		 * These should be separated by comma and sanitized properly.
		 *
		 */
		public static function get_demo_item_categories( $item ) {
			$sanitized_categories = array();

			if ( isset( $item['categories'] ) ) {
				foreach ( $item['categories'] as $category ) {
					$sanitized_categories[] = sanitize_key( $category );
				}
			}

			if ( ! empty( $sanitized_categories ) ) {
				return implode( ',', $sanitized_categories );
			}

			return false;
		}

		/**
		 * Demos popup
		 *
		 */
		public static function popup() {
			global $pagenow;

			// Display on the demos pages
			if ( 'themes.php' == $pagenow && isset( $_GET['page'] ) && 'dmdi-panel-install-demos' == $_GET['page'] ) { ?>
				
				<div id="dmdi-demo-popup-wrap">
					<div class="dmdi-demo-popup-container">
						<div class="dmdi-demo-popup-content-wrap">
							<div class="dmdi-demo-popup-content-inner">
								<a href="#" class="dmdi-demo-popup-close">×</a>
								<div id="dmdi-demo-popup-content"></div>
							</div>
						</div>
					</div>
					<div class="dmdi-demo-popup-overlay"></div>
				</div>

			<?php
			}
		}

		/**
		 * Demos popup ajax.
		 *
		 */
		public static function ajax_demo_data() {

			if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['demo_data_nonce'], 'get-demo-data' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// Database reset url
			if ( is_plugin_active( 'wp-reset/wp-reset.php' ) ) {
				$plugin_link 	= admin_url( 'tools.php?page=wp-reset' );
			} else {
				$plugin_link 	= admin_url( 'plugin-install.php?s=WP+Reset&tab=search' );
			}

			// Get all demos
			$demos = self::get_demos_data();

			// Get selected demo
			$demo = isset( $_GET['demo_name'] ) ? sanitize_text_field( wp_unslash( $_GET['demo_name'] ) ) : '';

			// Get required plugins
			$plugins = $demos[$demo][ 'required_plugins' ];

			// Get free plugins
			$free = $plugins[ 'free' ];

			?>

			<div id="dmdi-demo-plugins">

				<h2 class="title"><?php echo sprintf( esc_html__( 'Import the %1$s demo', 'di-themes-demo-site-importer' ), esc_attr( $demo ) ); ?></h2>

				<div class="dmdi-popup-text">

					<p><?php echo
						sprintf(
							esc_html__( 'Importing demo data allow you to quickly edit everything instead of creating content from scratch. It is recommended uploading sample data on a fresh WordPress install to prevent conflicts with your current content. You can use WP Reset plugin to reset your site if needed: %1$sWP Reset%2$s.', 'di-themes-demo-site-importer' ),
							'<a href="'. esc_url( $plugin_link ) .'" target="_blank">',
							'</a>'
						); ?></p>

					<div class="dmdi-required-plugins-wrap">
						<h3><?php esc_html_e( 'Required Plugins', 'di-themes-demo-site-importer' ); ?></h3>
						<p><?php esc_html_e( 'For your site to look exactly like this demo, the plugins below need to be activated.', 'di-themes-demo-site-importer' ); ?></p>
						<div class="dmdi-required-plugins oe-plugin-installer">
							<?php
							self::required_plugins( $free, 'free' );
							?>
						</div>
					</div>

				</div>

				<a class="dmdi-button dmdi-plugins-next" href="#"><?php esc_html_e( 'Go to the next step', 'di-themes-demo-site-importer' ); ?></a>

			</div>

			<form method="post" id="dmdi-demo-import-form">

				<input id="dmdi_import_demo" type="hidden" name="dmdi_import_demo" value="<?php echo esc_attr( $demo ); ?>" />

				<div class="dmdi-demo-import-form-types">

					<h2 class="title"><?php esc_html_e( 'Select what you want to import:', 'di-themes-demo-site-importer' ); ?></h2>
					
					<ul class="dmdi-popup-text">
						<li>
							<label for="dmdi_import_xml">
								<input id="dmdi_import_xml" type="checkbox" name="dmdi_import_xml" checked="checked" />
								<strong><?php esc_html_e( 'Import XML Data', 'di-themes-demo-site-importer' ); ?></strong> (<?php esc_html_e( 'pages, posts, images, menus, etc...', 'di-themes-demo-site-importer' ); ?>)
							</label>
						</li>

						<li>
							<label for="dmdi_theme_settings">
								<input id="dmdi_theme_settings" type="checkbox" name="dmdi_theme_settings" checked="checked" />
								<strong><?php esc_html_e( 'Import Customizer Settings', 'di-themes-demo-site-importer' ); ?></strong>
							</label>
						</li>

						<li>
							<label for="dmdi_import_widgets">
								<input id="dmdi_import_widgets" type="checkbox" name="dmdi_import_widgets" checked="checked" />
								<strong><?php esc_html_e( 'Import Widgets', 'di-themes-demo-site-importer' ); ?></strong>
							</label>
						</li>

					</ul>

				</div>
				
				<?php wp_nonce_field( 'dmdi_import_demo_data_nonce', 'dmdi_import_demo_data_nonce' ); ?>
				<input type="submit" name="submit" class="dmdi-button dmdi-import" value="<?php esc_html_e( 'Install this demo', 'di-themes-demo-site-importer' ); ?>"  />

			</form>

			<div class="dmdi-loader">
				<h2 class="title"><?php esc_html_e( 'The import process could take some time, please be patient', 'di-themes-demo-site-importer' ); ?></h2>
				<div class="dmdi-import-status dmdi-popup-text"></div>
			</div>

			<div class="dmdi-last">
				
				<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
				
				<h3><?php esc_html_e( 'Demo Imported!', 'di-themes-demo-site-importer' ); ?></h3>
				
				<a href="<?php echo esc_url( get_home_url() ); ?>" target="_blank"><?php esc_html_e( 'See the result', 'di-themes-demo-site-importer' ); ?></a>
			</div>

			<?php
			die();
		}

		/**
		 * Required plugins.
		 *
		 */
		public function required_plugins( $plugins, $return ) {

			foreach( $plugins as $key => $plugin ) {

				$api = array(
					'slug' 	=> isset( $plugin['slug'] ) ? $plugin['slug'] : '',
					'init' 	=> isset( $plugin['init'] ) ? $plugin['init'] : '',
					'name' 	=> isset( $plugin['name'] ) ? $plugin['name'] : '',
				);

				if ( ! is_wp_error( $api ) ) { // confirm error free

					// Installed but Inactive.
					if( file_exists( WP_PLUGIN_DIR . '/' . $plugin['init'] ) && is_plugin_inactive( $plugin['init'] ) ) {

						$button_classes = 'button activate-now button-primary';
						$button_text 	= esc_html__( 'Activate', 'di-themes-demo-site-importer' );

					// Not Installed.
					} elseif( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin['init'] ) ) {

						$button_classes = 'button install-now';
						$button_text 	= esc_html__( 'Install Now', 'di-themes-demo-site-importer' );

					// Active.
					} else {
						$button_classes = 'button disabled';
						$button_text 	= esc_html__( 'Activated', 'di-themes-demo-site-importer' );
					} ?>

					<div class="dmdi-plugin dmdi-clr dmdi-plugin-<?php echo esc_attr( $api['slug'] ); ?>" data-slug="<?php echo esc_attr( $api['slug'] ); ?>" data-init="<?php echo esc_attr( $api['init'] ); ?>">

						<h2><?php echo esc_html( $api['name'] ); ?></h2>

						<button class="<?php echo esc_attr( $button_classes ); ?>" data-init="<?php echo esc_attr( $api['init'] ); ?>" data-slug="<?php echo esc_attr( $api['slug'] ); ?>" data-name="<?php echo esc_attr( $api['name'] ); ?>"><?php echo esc_html( $button_text ); ?></button>

					</div>

				<?php
				}
			}

		}

		/**
		 * Required plugins activate
		 *
		 */
		public function ajax_required_plugins_activate() {

			if( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'No plugin specified', 'di-themes-demo-site-importer' ),
					)
				);
			}

			$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';
			$activate 	 = activate_plugin( $plugin_init, '', false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $activate->get_error_message(),
					)
				);
			}

			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Plugin Successfully Activated', 'di-themes-demo-site-importer' ),
				)
			);

		}

		/**
		 * Returns an array containing all the importable content
		 *
		 */
		public function ajax_get_import_data() {

			if( ! current_user_can( 'manage_options' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			check_ajax_referer( 'dmdi_import_data_nonce', 'security' );

			echo json_encode( 
				
				array(

					array(
						'input_name' 	=> 'dmdi_import_xml',
						'action' 		=> 'dmdi_ajax_import_xml',
						'method' 		=> 'ajax_import_xml',
						'loader' 		=> esc_html__( 'Importing XML Data', 'di-themes-demo-site-importer' )
					),

					array(
						'input_name' 	=> 'dmdi_theme_settings',
						'action' 		=> 'dmdi_ajax_import_theme_settings',
						'method' 		=> 'ajax_import_theme_settings',
						'loader' 		=> esc_html__( 'Importing Customizer Settings', 'di-themes-demo-site-importer' )
					),

					array(
						'input_name' 	=> 'dmdi_import_widgets',
						'action' 		=> 'dmdi_ajax_import_widgets',
						'method' 		=> 'ajax_import_widgets',
						'loader' 		=> esc_html__( 'Importing Widgets', 'di-themes-demo-site-importer' )
					),
				)
			);

			die();
		}

		/**
		 * Import XML file
		 *
		 */
		public function ajax_import_xml() {

			if ( ! current_user_can('manage_options') || ! wp_verify_nonce( $_POST['dmdi_import_demo_data_nonce'], 'dmdi_import_demo_data_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// Get the selected demo
			$demo_type 			= isset( $_POST['dmdi_import_demo'] ) ? sanitize_text_field( wp_unslash( $_POST['dmdi_import_demo'] ) ) : '';

			// Get demos data
			$demo 				= DTDSI_Demos::get_demos_data()[ $demo_type ];

			// Content file
			$xml_file 			= isset( $demo['xml_file'] ) ? $demo['xml_file'] : '';

			// Import Posts, Pages, Images, Menus.
			$result = $this->process_xml( $xml_file );

			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Import XML data
		 *
		 */
		public function process_xml( $file ) {
			
			$response = DTDSI_Demos_Helpers::get_remote( $file );

			// No sample data found
			if ( $response === false ) {
				return new WP_Error( 'xml_import_error', __( 'Can not retrieve sample data xml file. The server may be down at the moment please try again later. If you still have issues contact the theme developer for assistance.', 'di-themes-demo-site-importer' ) );
			}

			// Write sample data content to temp xml file
			$temp_xml = DTDSI_PATH .'inc/di-multipurpose/importers/temp.xml';
			file_put_contents( $temp_xml, $response );

			// Set temp xml to attachment url for use
			$attachment_url = $temp_xml;

			// If file exists lets import it
			if ( file_exists( $attachment_url ) ) {
				$this->import_xml( $attachment_url );
			} else {
				// Import file can't be imported - we should die here since this is core for most people.
				return new WP_Error( 'xml_import_error', __( 'The xml import file could not be accessed. Please try again or contact the theme developer.', 'di-themes-demo-site-importer' ) );
			}

		}
		
		/**
		 * Import XML file
		 *
		 */
		private function import_xml( $file ) {

			// Make sure importers constant is defined
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}

			// Define error var
			$importer_error = false;

			if ( ! class_exists( 'WP_Import' ) ) {
				$class_wp_import = DTDSI_PATH . 'inc/di-multipurpose/importers/class-wordpress-importer.php';

				if ( file_exists( $class_wp_import ) ) {
					require_once $class_wp_import;
				} else {
					$importer_error = __( 'Can not retrieve wordpress-importer.php', 'di-themes-demo-site-importer' );
				}
			}

			// Display error
			if ( $importer_error ) {
				return new WP_Error( 'xml_import_error', $importer_error );
			} else {

				// No error, lets import things...
				if ( ! is_file( $file ) ) {
					$importer_error = __( 'Sample data file appears corrupt or can not be accessed.', 'di-themes-demo-site-importer' );
					return new WP_Error( 'xml_import_error', $importer_error );
				} else {
					$importer = new WP_Import();
					$importer->fetch_attachments = true;
					add_filter( 'upload_mimes', array( $this, 'allow_svg_mime_types' ) );
					$importer->import( $file );

					// Clear sample data content from temp xml file
					$temp_xml = DTDSI_PATH .'inc/di-multipurpose/importers/temp.xml';
					file_put_contents( $temp_xml, '' );
				}
			}
		}

		/**
		 * [allow_svg_mime_types description]
		 * @return [type] [description]
		 */
		public function allow_svg_mime_types( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';
 			return $mimes;
		}

		/**
		 * Import customizer settings
		 *
		 */
		public function ajax_import_theme_settings() {

			if( ! current_user_can('manage_options') || ! wp_verify_nonce( $_POST['dmdi_import_demo_data_nonce'], 'dmdi_import_demo_data_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// Include settings importer
			include DTDSI_PATH . 'inc/di-multipurpose/importers/class-settings-importer.php';

			// Get the selected demo
			$demo_type 			= isset( $_POST['dmdi_import_demo'] ) ? sanitize_text_field( wp_unslash( $_POST['dmdi_import_demo'] ) ) : '';

			// Get demos data
			$demo 				= DTDSI_Demos::get_demos_data()[ $demo_type ];

			// Settings file
			$theme_settings 	= isset( $demo['theme_settings'] ) ? $demo['theme_settings'] : '';

			// Import settings.
			$settings_importer = new DTDSI_Settings_Importer();
			$result = $settings_importer->process_import_file( $theme_settings );
			
			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * Import widgets
		 *
		 */
		public function ajax_import_widgets() {

			if( ! current_user_can('manage_options') || ! wp_verify_nonce( $_POST['dmdi_import_demo_data_nonce'], 'dmdi_import_demo_data_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// Include widget importer
			include DTDSI_PATH . 'inc/di-multipurpose/importers/class-widget-importer.php';

			// Get the selected demo
			$demo_type 			= isset( $_POST['dmdi_import_demo'] ) ? sanitize_text_field( wp_unslash( $_POST['dmdi_import_demo'] ) ) : '';

			// Get demos data
			$demo 				= DTDSI_Demos::get_demos_data()[ $demo_type ];

			// Widgets file
			$widgets_file 		= isset( $demo['widgets_file'] ) ? $demo['widgets_file'] : '';

			// Import settings.
			$widgets_importer = new DTDSI_Widget_Importer();
			$result = $widgets_importer->process_import_file( $widgets_file );
			
			if ( is_wp_error( $result ) ) {
				echo json_encode( $result->errors );
			} else {
				echo 'successful import';
			}

			die();
		}

		/**
		 * After import
		 *
		 */
		public function ajax_after_import() {
			
			if ( ! current_user_can('manage_options') || ! wp_verify_nonce( $_POST['dmdi_import_demo_data_nonce'], 'dmdi_import_demo_data_nonce' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// If XML file is imported
			if ( $_POST['dmdi_import_is_xml'] === 'true' ) {

				// Get the selected demo
				$demo_type = isset( $_POST['dmdi_import_demo'] ) ? sanitize_text_field( wp_unslash( $_POST['dmdi_import_demo'] ) ) : '';

				// Get demos data
				$demo = DTDSI_Demos::get_demos_data()[ $demo_type ];

				// front_is 'page' handle
				if( isset( $demo['front_is'] ) && $demo['front_is'] == 'page' ) {

					$home_page = get_page_by_title( 'Home' );
					$blog_page = get_page_by_title( 'Blog' );

					update_option( 'show_on_front', 'page' );

					if ( is_object( $home_page ) ) {
						update_option( 'page_on_front', $home_page->ID );
					}

					if ( is_object( $blog_page ) ) {
						update_option( 'page_for_posts', $blog_page->ID );
					}					

				}

				// is_shop 'yes' handle
				if( class_exists( 'WooCommerce' ) && isset( $demo['is_shop'] ) && $demo['is_shop'] == 'yes' ) {

					$woopages = array(
						'woocommerce_shop_page_id' 				=> 'shop',
						'woocommerce_cart_page_id' 				=> 'cart',
						'woocommerce_checkout_page_id' 			=> 'checkout',
						'woocommerce_myaccount_page_id' 		=> 'my-account',
					);

					foreach( $woopages as $woo_page_name => $woo_page_slug ) {

						$woopage = get_page_by_path( $woo_page_slug );
						if( isset( $woopage ) && $woopage->ID ) {
							update_option( $woo_page_name, $woopage->ID );
						}
					}

					// We no longer need to install pages
					delete_option( '_wc_needs_pages' );
					delete_transient( '_wc_activation_redirect' );

					// Make sure TI WooCommerce Wishlist Plugin is in action
					if( defined( 'TINVWL_URL' ) ) {

						// find and set wishlist page
						$wishlist_page = get_page_by_path( 'wishlist' );
						if( isset( $wishlist_page ) && $wishlist_page->ID ) {
							// set the page
							update_option( 'tinvwl-page', array( 'wishlist' => $wishlist_page->ID ) );
						}

					}

				}
			
				// Set imported menus to registered theme locations
				$locations 	= get_theme_mod( 'nav_menu_locations' );
				$menus 		= wp_get_nav_menus();

				if( $menus ) {
					foreach( $menus as $menu ) {
						if( $menu->name == 'main' ) {
							$locations['primary'] = $menu->term_id;
						} elseif( $menu->name == 'topbar' ) {
							$locations['topbar'] = $menu->term_id;
						}
					}
				}

				set_theme_mod( 'nav_menu_locations', $locations );

				// Enable Elementor FA 4 Support
				update_option( 'elementor_load_fa4_shim', 'yes' );
				
			}

			die();
		}

		/**
		 * [plugin_action_links description]
		 * @param  [type] $actions [description]
		 * @return [type]          [description]
		 */
		public function plugin_action_links( $actions ) {
			$custom_link = array(
				'configure' => sprintf( '<a href="%s">%s</a>', admin_url() . 'themes.php?page=dmdi-panel-install-demos', __( 'Open Demos', 'di-themes-demo-site-importer' ) ),
				);
			return array_merge( $custom_link, $actions );
		}
	}
}
new DTDSI_Demos();
