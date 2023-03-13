<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
/**
 * Hook for product template builder.
 */
do_action( 'kadence_woocommerce_template_before_loop_product', $post );
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: kadence_woocommerce_template_after_loop hook.
	 */
	do_action( 'kadence_woocommerce_template_after_loop' );

	/**
	 * Hook: kadence_woocommerce_template_product_loop_override.
	 */
	do_action( 'kadence_woocommerce_template_product_loop_override', $post );

	/**
	 * Hook: kadence_woocommerce_template_after_loop hook.
	 */
	do_action( 'kadence_woocommerce_template_after_loop' );
	?>
</li>

