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
        add_action('wp_enqueue_scripts', [$this, 'yc_enqueue_front_css'], 100);
        add_action('admin_footer', [$this, 'yc_enqueue_admin_js'], 100);
        add_action('admin_footer', [$this, 'yc_footer_js'], 101);
        add_action('wp_enqueue_scripts', [$this, 'yc_enqueue_front_js'], 100);
    }

    public function yc_enqueue_admin_css()
    {
        wp_enqueue_style('YC_TECH admin_for_editor css', plugins_url('/../../../assets/css/yc_admin_level_' . self::$current_user_level . '.css', __FILE__));
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

    public function yc_enqueue_front_css()
    {
        wp_enqueue_style('YC_TECH front css', plugins_url('/../../../assets/css/yc_front_level_' . self::$current_user_level . '.css', __FILE__), array(), '1.0.1');

        if (FA_ENABLE) {
            wp_enqueue_style('fontawesome_css', plugins_url('/../../../assets/fontawesome/css/all.min.css', __FILE__));
        }
        if (FLIPSTER_ENABLE) {
            wp_enqueue_style('flipster_css', plugins_url('/../../../assets/flipster/jquery.flipster.min.css', __FILE__));
        }
        if (SLICK_ENABLE) {
            wp_enqueue_style('slick-theme_css', plugins_url('/../../../assets/slick/slick-theme.css', __FILE__));
            wp_enqueue_style('slick_css', plugins_url('/../../../assets/slick/slick.css', __FILE__));
        }
    }

    public function yc_enqueue_front_js()
    {
        wp_enqueue_script('Lottie js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js');
        wp_enqueue_script('YC_TECH front js', plugins_url('/../../../assets/js/yc_front.js', __FILE__));
        if (FA_ENABLE) {
            wp_enqueue_script('fontawesome_js',  plugins_url('/../../../assets/fontawesome/js/all.min.js', __FILE__));
        }
        if (FLIPSTER_ENABLE) {
            wp_enqueue_script('flipster_js', plugins_url('/../../../assets/flipster/jquery.flipster.min.js', __FILE__));
        }
        if (SLICK_ENABLE) {
            wp_enqueue_script('slick_js', plugins_url('/../../../assets/slick/slick.min.js', __FILE__));
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