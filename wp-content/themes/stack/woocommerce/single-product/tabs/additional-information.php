<?php
/**
* @author 		TommusRhodus
* @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional information', 'stack' ) ) );

?>

<?php if ( $heading ): ?>
	<h3><?php echo esc_html($heading); ?></h3>
<?php endif; ?>

<?php do_action( 'woocommerce_product_additional_information', $product ); ?>