<?php

/**
 * One Shop
 */

namespace YC\OneShop;

use YC_TECH;

defined('ABSPATH') || exit;

if (ONESHOP) {

    class OneShop_Mode extends YC_TECH
    {
        public function __construct()
        {



            add_action('wp_enqueue_scripts', [$this, 'yc_enqueue_front_css'], 101);

            add_filter('body_class', [$this, 'yc_add_bodyclass']);

            //Remove single page
            add_filter('woocommerce_register_post_type_product', [$this, 'yc_hide_product_page'], 12, 1);



        }

        public function yc_enqueue_front_css()
        {
            wp_enqueue_style('YC_TECH ONESHOP front css', plugins_url('/../../assets/css/yc_front_oneshop.css', __FILE__));
        }

        public function yc_add_bodyclass($classes)
        {
            $oneshop_class = ['yc_oneshop'];
            return array_merge($classes, $oneshop_class);
        }

        //Remove single page
        public function yc_hide_product_page($args)
        {
            $args["publicly_queryable"] = false;
            $args["public"] = false;
            return $args;
        }




    }

}
