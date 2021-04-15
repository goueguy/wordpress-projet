<?php

final class Di_Blocks_Helper {

	private static $instance;

	public static $block_list;

	public static $current_block_list = array();

	public static $di_blocks_flag = false;

	public static $stylesheet;

	public static $page_blocks;

	public static $gfonts = array();

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		require( Di_Blocks_PATH . 'classes/class-config.php' );
		require( Di_Blocks_PATH . 'classes/class-block-helper.php' );

		self::$block_list = Di_Blocks_Config::get_block_attributes();

		add_action( 'wp', array( $this, 'generate_stylesheet' ), 10 );
		add_action( 'wp_head', array( $this, 'frontend_gfonts' ), 120 );
		add_action( 'wp_head', array( $this, 'print_stylesheet' ), 80 );
	}

	/**
	 * Generates stylesheet and appends in head tag.
	 */
	public function generate_stylesheet() {

		$this_post = array();

		if ( is_single() || is_page() || is_404() ) {

			global $post;
			$this_post = $post;

			if ( ! is_object( $this_post ) ) {
				return;
			}

			$this->get_generated_stylesheet( $this_post );

		} elseif ( is_archive() || is_home() || is_search() ) {

			global $wp_query;

			foreach ( $wp_query as $post ) {
				$this->get_generated_stylesheet( $post );
			}
		}
	}

	/**
	 * Generates stylesheet in loop.
	 */
	public function get_generated_stylesheet( $this_post ) {

		if ( ! is_object( $this_post ) ) {
			return;
		}

		if ( ! isset( $this_post->ID ) ) {
			return;
		}

		if ( has_blocks( $this_post->ID ) ) {

			if ( isset( $this_post->post_content ) ) {

				$blocks            = $this->parse( $this_post->post_content );
				self::$page_blocks = $blocks;

				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return;
				}

				self::$stylesheet .= $this->get_stylesheet( $blocks );
			}
		}
	}

	/**
	 * Generates stylesheet for reusable blocks.
	 */
	public function get_stylesheet( $blocks ) {

		$desktop = '';
		$tablet  = '';
		$mobile  = '';

		$tab_styling_css = '';
		$mob_styling_css = '';


		foreach ( $blocks as $i => $block ) {

			if ( is_array( $block ) ) {

				if ( '' === $block['blockName'] ) {
					continue;
				}
				if ( 'core/block' === $block['blockName'] ) {
					$id = ( isset( $block['attrs']['ref'] ) ) ? $block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						self::$stylesheet .= $this->get_stylesheet( $reusable_blocks );

					}
				} else {
					// Get CSS for the Block.
					$css = $this->get_block_css( $block );

					if ( isset( $css['desktop'] ) ) {
						$desktop .= $css['desktop'];
					}
					if ( isset( $css['tablet'] ) ) {
						$tablet  .= $css['tablet'];
					}
					if ( isset( $css['mobile'] ) ) {
						$mobile  .= $css['mobile'];
					}
				}
			}
		}

		if ( ! empty( $tablet ) ) {
			$tab_styling_css .= '@media only screen and (max-width: ' . Di_Blocks_TABLET_BREAKPOINT .'px) {';
			$tab_styling_css .= $tablet;
			$tab_styling_css .= '}';
		}

		if ( ! empty( $mobile ) ) {
			$mob_styling_css .= '@media only screen and (max-width: ' . Di_Blocks_MOBILE_BREAKPOINT .'px) {';
			$mob_styling_css .= $mobile;
			$mob_styling_css .= '}';
		}

		return $desktop  . $tab_styling_css . $mob_styling_css;
	}

	/**
	 * Generates CSS recursively.
	 */
	public function get_block_css( $block ) {

		$block = ( array ) $block;

		$name = $block['blockName'];
		$css  = array();

		if( ! isset( $name ) ) {
			return;
		}

		if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
			$blockattr = $block['attrs'];
			if ( isset( $blockattr['blockId'] ) ) {
				$blockId = $blockattr['blockId'];
			}
		}

		self::$current_block_list[] = $name;

		if ( strpos( $name, 'di-blocks/' ) !== false ) {
			self::$di_blocks_flag = true;
		}

		switch ( $name ) {

			case 'di-blocks/headline':
				$css += Di_Blocks_Block_Helper::get_heading_css( $blockattr, $blockId );
				Di_Blocks_Block_Helper::get_heading_css_gfont( $blockattr );
				break;
			case 'di-blocks/google-map':
				$css += Di_Blocks_Block_Helper::get_google_map_css( $blockattr, $blockId );
				break;
			case 'di-blocks/icon':
				$css += Di_Blocks_Block_Helper::get_icon_css( $blockattr, $blockId );
				break;
			case 'di-blocks/paragraph':
				$css += Di_Blocks_Block_Helper::get_paragraph_css( $blockattr, $blockId );
				Di_Blocks_Block_Helper::get_paragraph_css_gfont( $blockattr );
				break;
			case 'di-blocks/divider':
				$css += Di_Blocks_Block_Helper::get_divider_css( $blockattr, $blockId );
				break;
			case 'di-blocks/button':
				$css += Di_Blocks_Block_Helper::get_button_css( $blockattr, $blockId );
				Di_Blocks_Block_Helper::get_button_css_gfont( $blockattr );
				break;
			default:
				break;
		}

		if ( isset( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $j => $inner_block ) {
				if ( 'core/block' == $inner_block['blockName'] ) {
					$id = ( isset( $inner_block['attrs']['ref'] ) ) ? $inner_block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						self::$stylesheet .= $this->get_stylesheet( $reusable_blocks );
					}
				} else {
					// Get CSS for the Block.
					$inner_block_css = $this->get_block_css( $inner_block );

					$css_desktop = ( isset( $css['desktop'] ) ? $css['desktop'] : '' );
					$css_tablet = ( isset( $css['tablet'] ) ? $css['tablet'] : '' );
					$css_mobile = ( isset( $css['mobile'] ) ? $css['mobile'] : '' );

					if( isset( $inner_block_css['desktop'] ) ){
						$css['desktop'] = $css_desktop . $inner_block_css['desktop'];
					}
					if( isset( $inner_block_css['tablet'] ) ){
						$css['tablet'] = $css_tablet . $inner_block_css['tablet'];
					}
					if( isset( $inner_block_css['mobile'] ) ){
						$css['mobile'] = $css_mobile . $inner_block_css['mobile'];
					}
				}
			}
		}

		self::$current_block_list = array_unique( self::$current_block_list );

		return $css;
	}

	/**
	 * Load the front end Google Fonts.
	 */
	public function frontend_gfonts() {
		if ( empty( self::$gfonts ) ) {
			return;
		}
		$show_google_fonts = apply_filters( 'di_blocks_show_google_fonts', true );
		if ( ! $show_google_fonts ) {
			return;
		}
		$link    = '';
		$subsets = array();
		foreach ( self::$gfonts as $key => $gfont_values ) {
			if ( ! empty( $link ) ) {
				$link .= '%7C'; // Append a new font to the string.
			}
			$link .= $gfont_values['fontfamily'];
			if ( ! empty( $gfont_values['fontvariants'] ) ) {
				$link .= ':';
				$link .= implode( ',', $gfont_values['fontvariants'] );
			}
			if ( ! empty( $gfont_values['fontsubsets'] ) ) {
				foreach ( $gfont_values['fontsubsets'] as $subset ) {
					if ( ! in_array( $subset, $subsets, true ) ) {
						array_push( $subsets, $subset );
					}
				}
			}
		}
		if ( ! empty( $subsets ) ) {
			$link .= '&amp;subset=' . implode( ',', $subsets );
		}

		wp_enqueue_style( 'di-blocks-gfonts', '//fonts.googleapis.com/css?family=' . esc_attr( str_replace( '|', '%7C', $link ) ), array(), Di_Blocks_VER, 'all' );
	}

	/**
	 * Print the Stylesheet in header.
	 */
	public function print_stylesheet() {

		global $content_width;

		if ( is_null( self::$stylesheet ) || '' === self::$stylesheet ) {
			return;
		}

		self::$stylesheet = str_replace( '#CONTENT_WIDTH#', $content_width . 'px', self::$stylesheet );

		ob_start();
		?>
		<style type="text/css" media="all" id="di-blocks-style-frontend"><?php echo self::$stylesheet; ?></style>
		<?php
		ob_end_flush();
	}


	/**
	 * Parse CSS into correct CSS syntax.
	 */
	public static function generate_css( $selectors, $id ) {

		$styling_css = '';
		$styling_css = '';

		if ( empty( $selectors ) ) {
			return;
		}

		foreach ( $selectors as $key => $value ) {

			$css = '';

			foreach ( $value as $j => $val ) {

				if ( ! empty( $val ) || 0 === $val ) {
					$css .= $j . ': ' . $val . ';';
				}
			}

			if ( ! empty( $css ) ) {
				$styling_css .= $id;
				$styling_css .= $key . '{';
				$styling_css .= $css . '}';
			}
		}

		return $styling_css;
	}

	/**
	 * Get CSS value
	 */
	public static function get_css_value( $value = '', $unit = '' ) {
		
		if ( $value == '' ) {
			return $value;
		}

		$css_val = '';

		if ( isset( $value ) ) {
			$css_val = esc_attr( $value ) . $unit;
		}

		return $css_val;
	}

	/**
	 * Get Rotate CSS value
	 */
	public static function get_rotate_css_value( $value = '' ) {

		if ( $value == '' ) {
			return;
		}

		$css_val = '';

		if ( isset( $value ) ) {
			$css_val = esc_attr( $value );
			$css_val = 'rotate( ' . $css_val . 'deg )';
		}

		return $css_val;
	}

	/**
	 * Get Drop Cap CSS value
	 */
	public static function get_dropcap_css_value( $dropcap = false, $value = '', $unit = '' ) {
		
		if( $dropcap == false ) {
			return;
		}
		
		if ( $value == '' ) {
			return $value;
		}

		$css_val = '';

		if ( isset( $value ) ) {
			$css_val = esc_attr( $value ) . $unit;
		}

		return $css_val;
	}

	/**
	 * Adds Google fonts all blocks.
	 */
	public static function blocks_google_font( $load_google_font, $font_family, $font_weight, $font_subset ) {

		if ( true === $load_google_font ) {
			if ( ! array_key_exists( $font_family, self::$gfonts ) ) {
				$add_font = array(
					'fontfamily'   => $font_family,
					'fontvariants' => ( isset( $font_weight ) && ! empty( $font_weight ) ? array( $font_weight ) : array() ),
					'fontsubsets'  => ( isset( $font_subset ) && ! empty( $font_subset ) ? array( $font_subset ) : array() ),
				);
				self::$gfonts[ $font_family ] = $add_font;
			} else {
				if ( isset( $font_weight ) && ! empty( $font_weight ) ) {
					if ( ! in_array( $font_weight, self::$gfonts[ $font_family ]['fontvariants'], true ) ) {
						array_push( self::$gfonts[ $font_family ]['fontvariants'], $font_weight );
					}
				}
				if ( isset( $font_subset ) && ! empty( $font_subset ) ) {
					if ( ! in_array( $font_subset, self::$gfonts[ $font_family ]['fontsubsets'], true ) ) {
						array_push( self::$gfonts[ $font_family ]['fontsubsets'], $font_subset );
					}
				}
			}
		}
	}

	/**
	 * Parse Block.
	 */
	public function parse( $content ) {

		global $wp_version;

		return ( version_compare( $wp_version, '5', '>=' ) ) ? parse_blocks( $content ) : gutenberg_parse_blocks( $content );
	}


}
Di_Blocks_Helper::get_instance();
