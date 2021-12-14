<?php

/**
 * Video post type
 */

namespace YC\Override;

use YC_TECH;

if (!IS_WC) return;

defined('ABSPATH') || exit;

class YCWC_Override extends YC_TECH
{

    public function __construct()
    {
        add_action('init', [$this, 'init']);
        //Override Woocommerce template
        add_filter('woocommerce_locate_template', [$this, 'yc_override_woocommerce_template'], 99, 3);

        add_action('after_setup_theme', [$this, 'yc_remove_product_link'], 98);

        //移除my account選單
        add_filter('woocommerce_account_menu_items', [$this, 'yc_custom_my_account'], 99);

        //調整結帳頁順序
        add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 40);
    }

    public function init()
    {
        //調整WC板位
        remove_filter('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
    }
    public function yc_custom_my_account($items)
    {
        unset($items['dashboard']);
        if (!ENABLE_DOWNLOAD_PRODUCT) {
            unset($items['downloads']);
        }
        return $items;
    }

    function yc_override_woocommerce_template($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_template = $template;

        if (!$template_path) $template_path = $woocommerce->template_url;

        $plugin_path  = $this->get_plugin_abs_path() . '/templates/woocommerce/';
        // Look within passed path within the theme - this is priority
        $template = locate_template(

            array(
                $template_path . $template_name,
                $template_name
            )
        );

        // Modification: Get the template from this plugin, if it exists
        if (!$template && file_exists($plugin_path . $template_name))
            $template = $plugin_path . $template_name;

        // Use default template
        if (!$template)
            $template = $_template;

        // Return what we found
        return $template;
    }

    public function yc_remove_product_link()
    {

        //移除產品連結，改成加入購物車
        if (!YCWC_LINK_TO_PRODUCT) {
            //移除產品連結
            remove_action(
                'woocommerce_before_shop_loop_item',
                'woocommerce_template_loop_product_link_open',
                10
            );
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

            //改成加入購物車
            add_action(
                'woocommerce_before_shop_loop_item',
                function () {
                    global $product;
                    echo '<a href="?add-to-cart=105" data-quantity="1" class="show_cart add_to_cart_button ajax_add_to_cart" data-product_id="' . $product->get_ID() . '" >';
                },
                10
            );
        }
        //移除coupon
        remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        //改位置
        add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form', 100);
    }
}
new YCWC_Override();
