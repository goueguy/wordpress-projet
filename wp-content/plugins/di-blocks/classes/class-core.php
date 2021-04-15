<?php

class Di_Blocks_Core {

	/**
	 * Instance object.
	 *
	 * @var instance
	 */
	public static $instance;

	/**
	 * Get_instance method.
	 *
	 * @return instance instance of the class.
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * [__construct description]
	 */
	public function __construct() {
		add_filter( 'block_categories', array( $this, 'block_category'), 10, 2 );
		add_action( 'init', array( $this, 'assets' ) ); // standard assets: editor+front css, editor js, editor css
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) ); // only front end css, js
		require_once Di_Blocks_PATH . 'classes/class-helper.php';
	}

	public function block_category( $categories, $post ) {
		return array_merge(
			array(
				array(
					'slug'		=> 'di-blocks',
					'title'		=> __( 'Di Blocks', 'di-blocks' ),
					'icon'		=> null,
				),
			),
			$categories
		);
	}

	public function assets() {

		wp_register_style(
			'di-blocks-editor-front-style',
			Di_Blocks_URL . 'dist/blocks.style.build.css',
			array( 'wp-editor' ),
			Di_Blocks_VER,
			'all'
		);

		wp_register_style(
			'di-blocks-editor-front-animate-style',
			Di_Blocks_URL . 'assets/css/animate.min.css',
			array( 'wp-editor' ),
			'3.7.2',
			'all'
		);

		wp_register_style(
			'di-blocks-font-awesome',
			Di_Blocks_URL . 'assets/css/fontawesome.min.css',
			array( 'wp-editor' ),
			'5.13.0',
			'all'
		);

		wp_register_style(
			'di-blocks-font-awesome-shim',
			Di_Blocks_URL . 'assets/css/fontawesome-v4-shims.min.css',
			array( 'wp-editor' ),
			'5.13.0',
			'all'
		);

		wp_register_script(
			'di-blocks-editor-script',
			Di_Blocks_URL . 'dist/blocks.build.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			Di_Blocks_VER,
			true
		);

		wp_register_style(
			'di-blocks-editor-style',
			Di_Blocks_URL . 'dist/blocks.editor.build.css',
			array( 'wp-edit-blocks' ),
			Di_Blocks_VER,
			'all'
		);

		wp_register_style(
			'di-blocks-editor-only-custom-style',
			Di_Blocks_URL . 'dist/block.editor.only.custom.css',
			array( 'wp-edit-blocks' ),
			Di_Blocks_VER,
			'all'
		);

		wp_localize_script(
			'di-blocks-editor-script',
			'diGlobal',
			array(
				'pluginDirPath' => Di_Blocks_PATH,
				'pluginDirUrl'  => Di_Blocks_URL,
			)
		);

		register_block_type(
			'di-blocks/blocks', array(
				'style'         => array( 'di-blocks-editor-front-style', 'di-blocks-editor-front-animate-style', 'di-blocks-font-awesome', 'di-blocks-font-awesome-shim' ),
				'editor_script' => array( 'di-blocks-editor-script' ),
				'editor_style'  => array( 'di-blocks-editor-style', 'di-blocks-editor-only-custom-style' ),
			)
		);
	}

	public function wp_enqueue_scripts() {

		wp_enqueue_script(
			'di-blocks-editor-front-animate-script',
			Di_Blocks_URL . 'assets/js/animate.min.js',
			array( 'jquery' ),
			Di_Blocks_VER,
			true
		);

	}

}
Di_Blocks_Core::get_instance();
