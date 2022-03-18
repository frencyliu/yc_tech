<?php

/**
 * CSS lv 0
 * for deverloper
 */

namespace YC\Admin;
use YC_TECH;

defined('ABSPATH') || exit;

class _CSS_JS extends YC_TECH
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'yc_enqueue_admin_css'], 100);
        add_action('admin_footer', [$this, 'yc_enqueue_admin_js'], 100);
        add_action('admin_footer', [$this, 'yc_footer_js'], 101);

    }

    public function yc_enqueue_admin_css()
    {
        wp_enqueue_style('YC_TECH admin_for_editor css', plugins_url('/../../../assets/css/yc_admin_level_' . self::$current_user_level . '.css', __FILE__));

        //在後台用BOOTSTRAP CLASS
        //wp_enqueue_style('theme bootstrap css', get_stylesheet_directory_uri() . '/css-output/bundle.css');
    }

    public function yc_enqueue_admin_js()
    {

        switch (self::$current_user_level) {
            case 0:
                wp_enqueue_script('YC_TECH admin js', plugins_url('/../../../assets/js/yc_admin_level_0.js', __FILE__));
                break;
            case 1:
                wp_enqueue_script('YC_TECH admin js', plugins_url('/../../../assets/js/yc_admin_level_0.js', __FILE__));
                wp_enqueue_script('YC_TECH admin js', plugins_url('/../../../assets/js/yc_admin_level_1.js', __FILE__));
                break;
            default:
                wp_enqueue_script('YC_TECH admin js', plugins_url('/../../../assets/js/yc_admin_level_0.js', __FILE__));
                wp_enqueue_script('YC_TECH admin js', plugins_url('/../../../assets/js/yc_admin_level_1.js', __FILE__));
                break;
        }
    }





    function yc_footer_js()
    {
        if(CAT_RADIO):
?>
        <script>
            jQuery('#categorychecklist').ready(function() {
                jQuery('#categorychecklist input[name="post_category[]"]').attr('type', 'radio');
            });
        </script>
        <?php
        endif;
    }

}
new _CSS_JS();