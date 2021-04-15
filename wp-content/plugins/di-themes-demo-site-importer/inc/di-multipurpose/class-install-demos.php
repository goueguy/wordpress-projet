<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class DTDSI_Install_Demos {

	/**
	 * Start things up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ), 999 );
	}

	/**
	 * Add sub menu page for the custom CSS input
	 *
	 */
	public function add_page() {

		add_submenu_page(
			'themes.php',
			esc_html__( 'Install Demo', 'di-themes-demo-site-importer' ),
			esc_html__( 'Install Demo', 'di-themes-demo-site-importer' ),
			'manage_options',
			'dmdi-panel-install-demos',
			array( $this, 'create_admin_page' )
		);
	}
	

	/**
	 * Settings page output
	 *
	 */
	public function create_admin_page() {

		?>

		<div class="dmdi-demo-wrap wrap">

			<h2><?php esc_html_e( 'Di Multipurpose - Install Demo', 'di-themes-demo-site-importer' ); ?></h2>

			<div class="theme-browser rendered">

				<?php
				// Vars
				$demos = DTDSI_Demos::get_demos_data();
				$categories = DTDSI_Demos::get_demo_all_categories( $demos ); ?>

				<?php if ( ! empty( $categories ) ) : ?>
					<div class="dmdi-header-bar">
						<nav class="dmdi-navigation">
							<ul>
								<li class="active"><a href="#all" class="dmdi-navigation-link"><?php esc_html_e( 'All', 'di-themes-demo-site-importer' ); ?></a></li>
								<?php foreach ( $categories as $key => $name ) : ?>
									<li><a href="#<?php echo esc_attr( $key ); ?>" class="dmdi-navigation-link"><?php echo esc_html( $name ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
						<div clas="dmdi-search">
							<input type="text" class="dmdi-search-input" name="dmdi-search" value="" placeholder="<?php esc_html_e( 'Search demos...', 'di-themes-demo-site-importer' ); ?>">
						</div>
					</div>
				<?php endif; ?>

				<div class="themes wp-clearfix">

					<?php
					// Loop through all demos
					foreach ( $demos as $demo => $key ) {

						// Vars
						$item_categories = DTDSI_Demos::get_demo_item_categories( $key ); ?>

						<div class="theme-wrap" data-categories="<?php echo esc_attr( $item_categories ); ?>" data-name="<?php echo esc_attr( strtolower( $demo ) ); ?>">

							<div class="theme dmdi-open-popup" data-demo-id="<?php echo esc_attr( $demo ); ?>">

								<div class="theme-screenshot">
									<img src="<?php echo esc_url( DTDSI_URL ); ?>assets/images/<?php echo esc_attr( $demo ); ?>.jpg" />

									<div class="demo-import-loader preview-all preview-all-<?php echo esc_attr( $demo ); ?>"></div>

									<div class="demo-import-loader preview-icon preview-<?php echo esc_attr( $demo ); ?>"><i class="custom-loader"></i></div>
								</div>

								<div class="theme-id-container">
		
									<h2 class="theme-name" id="<?php echo esc_attr( $demo ); ?>"><span><?php echo ucwords( $demo ); ?></span></h2>

									<div class="theme-actions">
										<a class="button button-primary" href="http://demo.dithemes.com/di-multipurpose/<?php echo esc_attr( $demo ); ?>/" target="_blank"><?php _e( 'Live Preview', 'di-themes-demo-site-importer' ); ?></a>
									</div>

								</div>

							</div>

						</div>

					<?php } ?>

				</div>

			</div>

		</div>

	<?php }
}
new DTDSI_Install_Demos();
