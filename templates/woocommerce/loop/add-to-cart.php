<?php

/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;
global $woocommerce;



do_action( 'YCWC_bfore_excerpt');

if (YCWC_SHOW_EXCERPT_WHEN_LOOP) {
    the_excerpt();
}

do_action( 'YCWC_bfore_add_to_cart_btn');

echo '<div class="ycwc_loop_btn">';

if (YCWC_SHOW_ADD_TO_CART_WHEN_LOOP) {
    if ($product->is_type( 'simple' )) {
        echo do_shortcode( '[ajax_add_to_cart id="' . $product->get_ID() . '" text="' . esc_html($product->add_to_cart_text()) . '" class="btn btn-lg btn-primary w-100 mb-2"]' );
        if (YCWC_SHOW_DIRECT_BUY_WHEN_LOOP && $product->is_type( 'simple' )) {
            echo sprintf(
                '<a href="%s" class="%s" %s>%s</a>',
                esc_url(site_url() . '/checkout/?empty_cart=yes&add-to-cart=' . $product->get_ID()),
                'w-100 product_type_simple btn btn-lg btn-primary ajax_add_to_cart',
                '',
                esc_html('直接購買')
            );
        }

    }elseif($product->is_type( 'variable' )){
        $structure = get_option( 'woocommerce_permalinks' );
        echo sprintf(
            '<a href="%s" class="%s" %s>%s</a>',
            site_url() . '/' . $structure['product_base'] . '/' . $product->get_slug(),
            'product_type_variable btn btn-lg btn-primary w-100',
            '',
            esc_html('產品資訊')
        );
    }

}


echo '</div>';

do_action( 'YCWC_after_add_to_cart_btn', $product->get_ID());

