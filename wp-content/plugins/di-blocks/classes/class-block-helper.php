<?php

class Di_Blocks_Block_Helper {

	public static function get_heading_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/headline']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$m_selectors = array();
		$t_selectors = array();

		$selectors = array(
			' .di-blocks-heading-main'        => array(
				'font-family' => $attr['FontFamily'],
				'font-weight' => $attr['FontWeight'],
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSize'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeight'], $attr['LineHeightType'] ),
				'letter-spacing' => Di_Blocks_Helper::get_css_value( $attr['LetterSpacing'], 'px' ),
				'text-transform' => $attr['TextTransform'],
				'color' => Di_Blocks_Helper::get_css_value( $attr['HeadColor'], '' ),
				'background-color' => Di_Blocks_Helper::get_css_value( $attr['HeadBgColor'], '' ),
				'border-radius' => Di_Blocks_Helper::get_css_value( $attr['BorderRadius'], 'px' ),
				'border-style' => Di_Blocks_Helper::get_css_value( $attr['BorderStyle'], '' ),
				'border-width' => Di_Blocks_Helper::get_css_value( $attr['BorderWidth'], 'px' ),
				'border-color' => Di_Blocks_Helper::get_css_value( $attr['BorderColor'], '' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
			),
			' .di-blocks-heading-main a'        => array(
				'color' => Di_Blocks_Helper::get_css_value( $attr['HeadLinksColor'], '' ),
			),
			' .di-blocks-heading-main a:hover'        => array(
				'color' => Di_Blocks_Helper::get_css_value( $attr['HeadLinksHvrColor'], '' ),
			),
		);

		$m_selectors = array(
			' .di-blocks-heading-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeMobile'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightMobile'], $attr['LineHeightType'] ),
			)
		);

		$t_selectors = array(
			' .di-blocks-heading-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeTablet'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightTablet'], $attr['LineHeightType'] ),

			)
		);

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-heading-' . $id );

		$tablet = Di_Blocks_Helper::generate_css( $t_selectors, '#di-blocks-heading-' . $id );

		$mobile = Di_Blocks_Helper::generate_css( $m_selectors, '#di-blocks-heading-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}


	public static function get_heading_css_gfont( $attr ) {

		$load_google_font = isset( $attr['LoadGoogleFonts'] ) ? $attr['LoadGoogleFonts'] : '';
		$font_family      = isset( $attr['FontFamily'] ) ? $attr['FontFamily'] : '';
		$font_weight      = isset( $attr['FontWeight'] ) ? $attr['FontWeight'] : '';
		$font_subset      = isset( $attr['FontSubset'] ) ? $attr['FontSubset'] : '';

		Di_Blocks_Helper::blocks_google_font( $load_google_font, $font_family, $font_weight, $font_subset );
	}

	public static function get_google_map_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/google-map']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$selectors = array(
			' .di-blocks-google-map-main'        => array(
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
			),
		);

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-google-map-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
		);

		return $generated_css;
	}

	public static function get_icon_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/icon']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$selectors = array(
			' .di-blocks-icon-main'	=> array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['IconSize'], 'px' ),
				'color' => Di_Blocks_Helper::get_css_value( $attr['IconColor'], '' ),
				'background-color' => Di_Blocks_Helper::get_css_value( $attr['IconBgColor'], '' ),
				'border-radius' => Di_Blocks_Helper::get_css_value( $attr['BorderRadius'], 'px' ),
				'border-style' => Di_Blocks_Helper::get_css_value( $attr['BorderStyle'], '' ),
				'border-width' => Di_Blocks_Helper::get_css_value( $attr['BorderWidth'], 'px' ),
				'border-color' => Di_Blocks_Helper::get_css_value( $attr['BorderColor'], '' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
				'-ms-transform' => Di_Blocks_Helper::get_rotate_css_value( $attr['IconRotate'] ),
				'transform' => Di_Blocks_Helper::get_rotate_css_value( $attr['IconRotate'] ),
			),
		);

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-icon-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
		);

		return $generated_css;
	}

	public static function get_paragraph_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/paragraph']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$m_selectors = array();
		$t_selectors = array();

		$selectors = array(
			' .di-blocks-paragraph-main'        => array(
				'font-family' => $attr['FontFamily'],
				'font-weight' => $attr['FontWeight'],
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSize'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeight'], $attr['LineHeightType'] ),
				'letter-spacing' => Di_Blocks_Helper::get_css_value( $attr['LetterSpacing'], 'px' ),
				'text-transform' => $attr['TextTransform'],
				'color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'background-color' => Di_Blocks_Helper::get_css_value( $attr['BgColor'], '' ),
				'border-radius' => Di_Blocks_Helper::get_css_value( $attr['BorderRadius'], 'px' ),
				'border-style' => Di_Blocks_Helper::get_css_value( $attr['BorderStyle'], '' ),
				'border-width' => Di_Blocks_Helper::get_css_value( $attr['BorderWidth'], 'px' ),
				'border-color' => Di_Blocks_Helper::get_css_value( $attr['BorderColor'], '' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
			),
			' .di-blocks-paragraph-main a'        => array(
				'color' => Di_Blocks_Helper::get_css_value( $attr['LinksColor'], '' ),
			),
			' .di-blocks-paragraph-main a:hover'        => array(
				'color' => Di_Blocks_Helper::get_css_value( $attr['LinksHvrColor'], '' ),
			),
			' .di-blocks-paragraph-main::first-letter'        => array(
				'font-size' => Di_Blocks_Helper::get_dropcap_css_value( $attr['DropCap'], $attr['DropCapSize'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_dropcap_css_value( $attr['DropCap'], $attr['DropCapGap'], 'px' ),
				'color' => Di_Blocks_Helper::get_dropcap_css_value( $attr['DropCap'], $attr['DropCapColor'], '' ),
				'float' => Di_Blocks_Helper::get_dropcap_css_value( $attr['DropCap'], 'left', '' ),
				'margin' => Di_Blocks_Helper::get_dropcap_css_value( $attr['DropCap'], '0.05em 0.1em 0 0', '' ),
			),
		);

		$m_selectors = array(
			' .di-blocks-paragraph-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeMobile'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightMobile'], $attr['LineHeightType'] ),
			)
		);

		$t_selectors = array(
			' .di-blocks-paragraph-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeTablet'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightTablet'], $attr['LineHeightType'] ),

			)
		);

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-paragraph-' . $id );

		$tablet = Di_Blocks_Helper::generate_css( $t_selectors, '#di-blocks-paragraph-' . $id );

		$mobile = Di_Blocks_Helper::generate_css( $m_selectors, '#di-blocks-paragraph-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}


	public static function get_paragraph_css_gfont( $attr ) {

		$load_google_font = isset( $attr['LoadGoogleFonts'] ) ? $attr['LoadGoogleFonts'] : '';
		$font_family      = isset( $attr['FontFamily'] ) ? $attr['FontFamily'] : '';
		$font_weight      = isset( $attr['FontWeight'] ) ? $attr['FontWeight'] : '';
		$font_subset      = isset( $attr['FontSubset'] ) ? $attr['FontSubset'] : '';

		Di_Blocks_Helper::blocks_google_font( $load_google_font, $font_family, $font_weight, $font_subset );
	}

	public static function get_divider_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/divider']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$selectors = array(
			' .di-blocks-divider.di-blocks-outer'        => array(
				'justify-content' => Di_Blocks_Helper::get_css_value( $attr['Alignment'], '' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['DividerHeight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['DividerHeight'], 'px' ),
			),
			' span.di-blocks-divider-main'        => array(
				'width' => Di_Blocks_Helper::get_css_value( $attr['BorderWidth'], '%' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
			),
			' .di-blocks-divider-icon'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['IconSize'], 'px' ),
				'color' => Di_Blocks_Helper::get_css_value( $attr['IconColor'], '' ),
				'margin' => '0 ' . Di_Blocks_Helper::get_css_value( $attr['IconGap'], 'px' ),
			),
		);

		if( $attr['IconStatus'] == 'center' ) {
			$selectors[ ' .di-blocks-divider-main::after' ] = array(
				'border-top-style' => $attr['Style'],
				'border-top-width' => Di_Blocks_Helper::get_css_value( $attr['BorderHeight'], 'px' ),
				'border-top-color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'display' => 'block',
				'content' => '""',
				'border-bottom' => '0',
				'flex-grow' => '1',
			);
			$selectors[ ' .di-blocks-divider-main::before' ] = array(
				'border-top-style' => $attr['Style'],
				'border-top-width' => Di_Blocks_Helper::get_css_value( $attr['BorderHeight'], 'px' ),
				'border-top-color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'display' => 'block',
				'content' => '""',
				'border-bottom' => '0',
				'flex-grow' => '1',
			);
		} elseif(  $attr['IconStatus'] == 'left' ) {
			$selectors[ ' .di-blocks-divider-main::after' ] = array(
				'border-top-style' => $attr['Style'],
				'border-top-width' => Di_Blocks_Helper::get_css_value( $attr['BorderHeight'], 'px' ),
				'border-top-color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'display' => 'block',
				'content' => '""',
				'border-bottom' => '0',
				'flex-grow' => '1',
			);
			$selectors[ ' div.di-blocks-divider-icon' ] = array(
				'margin-left' => '0',
			);
		} elseif(  $attr['IconStatus'] == 'right' ) {
			$selectors[ ' .di-blocks-divider-main::before' ] = array(
				'border-top-style' => $attr['Style'],
				'border-top-width' => Di_Blocks_Helper::get_css_value( $attr['BorderHeight'], 'px' ),
				'border-top-color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'display' => 'block',
				'content' => '""',
				'border-bottom' => '0',
				'flex-grow' => '1',
			);
			$selectors[ ' div.di-blocks-divider-icon' ] = array(
				'margin-right' => '0',
			);
		} else {
			$selectors[ ' .di-blocks-divider-main' ] = array(
				'border-top-style' => $attr['Style'],
				'border-top-width' => Di_Blocks_Helper::get_css_value( $attr['BorderHeight'], 'px' ),
				'border-top-color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
			);
		}

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-divider-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
		);

		return $generated_css;
	}

	public static function get_button_css( $attr, $id ) {

		$defaults = Di_Blocks_Helper::$block_list['di-blocks/button']['attributes'];

		$attr = array_merge( $defaults, (array) $attr );

		$m_selectors = array();
		$t_selectors = array();

		$selectors = array(
			' .di-blocks-button-main'        => array(
				'margin-top' => Di_Blocks_Helper::get_css_value( $attr['MarginTop'], 'px' ),
				'margin-right' => Di_Blocks_Helper::get_css_value( $attr['MarginRight'], 'px' ),
				'margin-bottom' => Di_Blocks_Helper::get_css_value( $attr['MarginBottom'], 'px' ),
				'margin-left' => Di_Blocks_Helper::get_css_value( $attr['MarginLeft'], 'px' ),
			),
			' .di-blocks-button-main a'        => array(
				'font-family' => $attr['FontFamily'],
				'font-weight' => $attr['FontWeight'],
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSize'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeight'], $attr['LineHeightType'] ),
				'letter-spacing' => Di_Blocks_Helper::get_css_value( $attr['LetterSpacing'], 'px' ),
				'text-transform' => $attr['TextTransform'],
				'color' => Di_Blocks_Helper::get_css_value( $attr['Color'], '' ),
				'background-color' => Di_Blocks_Helper::get_css_value( $attr['BgColor'], '' ),
				'border-radius' => Di_Blocks_Helper::get_css_value( $attr['BorderRadius'], 'px' ),
				'border-style' => Di_Blocks_Helper::get_css_value( $attr['BorderStyle'], '' ),
				'border-width' => Di_Blocks_Helper::get_css_value( $attr['BorderWidth'], 'px' ),
				'border-color' => Di_Blocks_Helper::get_css_value( $attr['BorderColor'], '' ),
				'padding-top' => Di_Blocks_Helper::get_css_value( $attr['PaddingTop'], 'px' ),
				'padding-right' => Di_Blocks_Helper::get_css_value( $attr['PaddingRight'], 'px' ),
				'padding-bottom' => Di_Blocks_Helper::get_css_value( $attr['PaddingBottom'], 'px' ),
				'padding-left' => Di_Blocks_Helper::get_css_value( $attr['PaddingLeft'], 'px' ),
			),
			' .di-blocks-button-main a:hover'        => array(
				'color' => Di_Blocks_Helper::get_css_value( $attr['HvrColor'], '' ),
				'background-color' => Di_Blocks_Helper::get_css_value( $attr['HvrBgColor'], '' ),
			),
		);

		$m_selectors = array(
			' .di-blocks-button-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeMobile'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightMobile'], $attr['LineHeightType'] ),
			)
		);

		$t_selectors = array(
			' .di-blocks-button-main'        => array(
				'font-size' => Di_Blocks_Helper::get_css_value( $attr['FontSizeTablet'], $attr['FontSizeType'] ),
				'line-height' => Di_Blocks_Helper::get_css_value( $attr['LineHeightTablet'], $attr['LineHeightType'] ),

			)
		);

		$desktop = Di_Blocks_Helper::generate_css( $selectors, '#di-blocks-button-' . $id );

		$tablet = Di_Blocks_Helper::generate_css( $t_selectors, '#di-blocks-button-' . $id );

		$mobile = Di_Blocks_Helper::generate_css( $m_selectors, '#di-blocks-button-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}


	public static function get_button_css_gfont( $attr ) {

		$load_google_font = isset( $attr['LoadGoogleFonts'] ) ? $attr['LoadGoogleFonts'] : '';
		$font_family      = isset( $attr['FontFamily'] ) ? $attr['FontFamily'] : '';
		$font_weight      = isset( $attr['FontWeight'] ) ? $attr['FontWeight'] : '';
		$font_subset      = isset( $attr['FontSubset'] ) ? $attr['FontSubset'] : '';

		Di_Blocks_Helper::blocks_google_font( $load_google_font, $font_family, $font_weight, $font_subset );
	}

}
