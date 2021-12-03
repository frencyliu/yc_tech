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
            //add_filter('woocommerce_register_post_type_product', [$this, 'yc_hide_product_page'], 12, 1);

            //override woocommerce template
            //https://www.skyverge.com/blog/override-woocommerce-template-file-within-a-plugin/
            //add_filter( 'woocommerce_locate_template', [$this, 'yc_oneshop_override_woocommerce_template' ], 20, 3 );


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

        function get_plugin_abs_path()
        {

            // gets the absolute path to this plugin directory

            return untrailingslashit(plugin_dir_path(__FILE__));
        }

        function yc_oneshop_override_woocommerce_template($template, $template_name, $template_path)
        {
            global $woocommerce;

            $_template = $template;

            if (!$template_path) $template_path = $woocommerce->template_url;

            $plugin_path  = $this->get_plugin_abs_path() . '/woocommerce/';
            //var_dump($plugin_path);
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


    }

}
