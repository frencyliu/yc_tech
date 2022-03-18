<?php

/**
 * customise admin
 */

namespace YC\Admin;

use YC_TECH;

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce', false)) return;
class YCWC extends YC_TECH
{

    public function __construct()
    {


        add_action('admin_head', [$this, 'yc_cancel_some_order'], 99);
        add_filter('woocommerce_enqueue_styles', [$this, 'yc_override_wc_css'], 99);

        //when changing theme_mod, then re-compile child-theme/assets/css/wc scss

        /*代處理
        add_action("admin_init", function (){
            if (!current_user_can("administrator")) return; //ADMINS ONLY

            if (isset($_GET['ps_compile_scss'])) {		$this->yc_generate_css();		die();	}
            if (isset($_GET['ps_reset_theme'])) {		remove_theme_mods(); 	echo ("Theme Options Reset.<br>");	$this->yc_generate_css();		die(); }
            if (isset($_GET['ps_show_mods'])){		print_r(get_theme_mods());		wp_die();	}
        });
        */

        //代處理  全站優惠
        //add_filter('woocommerce_cart_totals_coupon_label', [$this,  'filter_woocommerce_cart_totals_coupon_label'], 99, 2);
        //add_action('admin_footer', [$this,  'make_coupon_uneditable'], 99);
        //add_filter('woocommerce_cart_subtotal', [$this,  'royal_woocommerce_filter_checkout_for_coupons'], 99, 3);


        //change woocommerce default recipient
        add_filter('woocommerce_email_recipient_new_order', [$this, 'my_email_heading_customisation_function_ent'], 100, 2);
        add_filter('woocommerce_email_recipient_cancelled_order', [$this, 'quadlayers_add_email_recipient_to'], 100, 3);
        //---- woocommerce ----//
        //add_filter('woocommerce_customer_meta_fields', [$this, 'yc_remove_shipping_fields'], 999);
        //remove woocommerce setting tab
        add_filter('woocommerce_settings_tabs_array', [$this, 'yc_remove_woocommerce_setting_tabs'], 100, 1);

        add_action('init', [$this, 'yc_get_post_data']);


        add_filter( 'product_type_selector', [$this, 'yc_remove_product_types' ], 99 );
    }

    function yc_remove_product_types( $types ){
        //unset( $types['simple'] );
        unset( $types['grouped'] );
        unset( $types['external'] );
        unset( $types['variable'] );

        return $types;
    }

    function yc_generate_css(){
    }

    function yc_override_wc_css($css_array)
    {

        if( file_exists(get_stylesheet_directory() . '/assets/css/wc/woocommerce-layout.css') ){
            $css_array['woocommerce-layout']['src'] = get_stylesheet_directory_uri() . '/assets/css/wc/woocommerce-layout.css';
        }

        if( file_exists(get_stylesheet_directory() . '/assets/css/wc/woocommerce.css') ){
            $css_array['woocommerce-general']['src'] = get_stylesheet_directory_uri() . '/assets/css/wc/woocommerce.css';
            $css_array['woocommerce-general']['deps'] = 'woocommerce-layout';
        }

        if( file_exists(get_stylesheet_directory() . '/assets/css/wc/woocommerce-smallscreen.css') ){
            $css_array['woocommerce-smallscreen']['src'] = get_stylesheet_directory_uri() . '/assets/css/wc/woocommerce-smallscreen.css';
            $css_array['woocommerce-smallscreen']['deps'] = ['woocommerce-layout', 'woocommerce-general'];
        }
        //YC_TECH::DEBUG($css_array, true);
        return $css_array;
    }

    function yc_cancel_some_order()
    {

        //超商取貨，沒選擇地址就變取消
        $args = array(
            'post_type' => 'shop_order',
            'post_status'   => 'wc-on-hold',
            'date_query' => array(
                array(
                    'after' => '7 days ago'
                )
            ),
            'meta_query' => array(
                'relation' => 'AND',
                'payment_method_clause' => array(
                    'key' => '_payment_method',
                    'value' => 'ecpay_shipping_pay',
                ),
                'purchaserStore_clause' => array(
                    'key' => '_shipping_purchaserStore',
                    'compare' => 'NOT EXISTS',
                ),
                'purchaserAddress_clause' => array(
                    'key' => '_shipping_purchaserAddress',
                    'compare' => 'NOT EXISTS',
                ),
                'CVSStoreID_clause' => array(
                    'key' => '_shipping_CVSStoreID',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );

        // The Query
        $the_orders = get_posts($args);
        foreach ($the_orders as $the_order) {

            //
            $arg = array(
                'ID'            => $the_order->ID,
                'post_status'     => 'wc-cancelled',
            );
            //echo $the_order->ID . ', ';
            wp_update_post($arg);
        }
        //var_dump($the_orders);
    }

    // Tested and works for WooCommerce versions 2.6.x, 3.0.x and 3.1.x
    function make_coupon_uneditable()
    {
        global $post;
        if (get_current_screen()->id == 'shop_coupon' && $post->ID == 241582) {
?>
            <style>
                #titlewrap .generate-coupon-code {
                    display: none !important;
                }

                #titlewrap #title {
                    background-color: #bbb;
                    cursor: not-allowed;
                }
            </style>
            <script>
                jQuery('#titlewrap .generate-coupon-code').remove();
                jQuery('#titlewrap #title').attr('disabled', 'disabled');
            </script>
        <?php
        }
        if (get_current_screen()->id == 'toplevel_page_wpsc-tickets') {
        ?>
            <script src='<?php echo get_stylesheet_directory_uri() ?>/admin.js?v=1.0.2' id='yc-admin-js'></script>
<?php
        }
    }


    function royal_woocommerce_filter_checkout_for_coupons($subtotal, $compound, $cart)
    {

        $coupon_name = '全站折扣設定';
        $d = new WC_Discounts($cart);
        $c = new WC_Coupon($coupon_name);

        //var_dump($d->is_coupon_valid( $c ));

        if ($d->is_coupon_valid($c) === true) {

            // Setup our virtual coupon

            switch ($c->discount_type) {
                case 'percent':
                    $discount_amount = $cart->get_subtotal() * $c->amount / 100;
                    break;
                case 'fixed_cart':
                    $discount_amount = $c->amount;
                    break;
                case 'fixed_product':
                    $discount_amount = 0;
                    break;
                default:
                    $discount_amount = 0;
                    break;
            }

            $coupon = array($coupon_name => $discount_amount);
            // Apply the store credit coupon to the cart & update totals
            $cart->applied_coupons = array($coupon_name);
            $cart->set_discount_total($discount_amount);
            $cart->set_total($cart->get_subtotal() - $discount_amount);
            $cart->coupon_discount_totals = $coupon;
        }
        return $subtotal;
    }


    function filter_woocommerce_cart_totals_coupon_label($label, $coupon)
    {
        // Compare
        if ($coupon->get_code() == '全站折扣設定') {
            $label = $coupon->get_description();
        }

        return $label;
    }




    public function yc_get_post_data()
    {
        if (isset($_GET['empty_cart'])) {
            global $woocommerce;
            $woocommerce->cart->empty_cart();
        }

    }

    public function my_email_heading_customisation_function_ent($recipient, $order)
    {
        global $woocommerce;
        //$recipient = "123@email.cz";

        return $recipient;
    }


    public function quadlayers_add_email_recipient_to($email_recipient, $email_object, $email)
    {
        //$email_recipient .= ', 456@mail.com';
        return $email_recipient;
    }

    //移除WC後台設定TAB
    function yc_remove_woocommerce_setting_tabs($tabs)
    {

        switch (self::$current_user_level) {
            case 0:
                # do nothing
                break;
            default:
                unset($tabs['integration']);
                unset($tabs['advanced']);
                break;
        }
        return $tabs;
    }

    //停用帳單部分欄位跟shipping欄位
    //有裝套件，所以停用
    /*function yc_remove_shipping_fields($show_fields)
    {
        unset($show_fields['billing']['fields']['billing_last_name']);
        unset($show_fields['billing']['fields']['billing_address_2']);
        unset($show_fields['billing']['fields']['billing_city']);
        //unset($show_fields['billing']['fields']['billing_country']);
        unset($show_fields['billing']['fields']['billing_state']);
        unset($show_fields['billing']['fields']['billing_email']);
        unset($show_fields['shipping']);
        //var_dump($show_fields);
        return $show_fields;
    }*/

    //Remove single page
    /*
add_filter( 'woocommerce_register_post_type_product','hide_product_page',12,1);
function hide_product_page($args){
    $args["publicly_queryable"]=false;
    $args["public"]=false;
    return $args;
}
*/
}
new YCWC();
