<?php

/**
 * Plugin Name
 *
 * @package           YC_TECH
 * @author            Jerry Liu
 * @copyright         2021 YC-TECH
 *
 * @wordpress-plugin
 * Plugin Name:       YC_TECH
 * Plugin URI:
 * Description:       EZ update all the site
 * Version:           1.0.0
 * Requires at least: 5.5.0
 * Requires PHP:      7.3
 * Author:            Jerry Liu
 * Author URI:
 * Text Domain:       YC_TECH
 */


/**
 *
 *
 *
 */


defined('ABSPATH') or die('hey, you can\'t see this.');



if (!class_exists('YC_TECH')) {
    class YC_TECH
    {
        static $level_0 = ['administrator'];
        static $level_1 = ['designer']; //網頁設計師
        static $level_2 = ['supervisor']; //可以新增用戶
        static $level_3 = ['shop_manager', 'editor', 'author', 'translator']; //不可以新增
        static $current_user_level = 1;
        //隱藏的用戶
        static $hide_user = ['JerryLiu', 'KarenShen', 'Emily'];

        public function __construct()
        {

            //add_action('admin_head', [ $this, 'test' ]);
            add_action('init', [$this, 'yc_get_current_user_level']);
            add_action('init', [$this, 'yc_set_default']);

            //預設wp statistics 最多保存365天數據
            add_filter('wp_statistics_option_schedule_dbmaint', function () {
                return 'on';
            });
            add_filter('wp_statistics_option_schedule_dbmaint_days', function () {
                return '365';
            });

            //i18n
            add_action('init', [$this, 'yc_i18n']);

            //DEBUG
            add_action('admin_head', [$this, 'DEBUG']);

        }

        static public function DEBUG($variable, $show_anyway = false)
        {
            $html = '';
            ob_start();
?>
            <style>
                .yc_debug {
                    position: fixed;
                    height: 100%;
                    width: 300px;
                    overflow-y: auto;
                    right: 0px;
                    top: 0px;
                    background: #fff;
                    box-shadow: 0px 0px 15px #999;
                    z-index: 99999;
                }
            </style>
<?php
            $html .= ob_get_clean();
            echo $html;
            if ($show_anyway) :
                YC_TECH::DEBUG_output($variable);
            elseif (!empty($variable) && WP_DEBUG) :
                YC_TECH::DEBUG_output($variable);
            endif;
        }

        static public function DEBUG_output($variable){
            echo '<div class="yc_debug"><pre>';
            var_dump($variable);
            echo '</pre></div>';
        }




        static public function yc_is_login_logic()
        {
            if (!is_user_logged_in()) {

                //如果未登入
                //就顯示登入頁面
                //get_header();
                echo '<div class="woocommerce">';
                wc_get_template('myaccount/form-login.php');
                echo '</div>';
                //登入後跳轉
                add_filter('woocommerce_login_redirect', function () {
                    return get_permalink();
                }, 200);
                //get_footer();
                //exit();
            } else {
                //如果已登入，就判斷使用者腳色
                $current_user = wp_get_current_user();
                $current_user_role = $current_user->roles[0];
                $available_roles = ['administrator', 'desinger', 'shop_manager', 'shop_manager_super', 'sh_vendor_a', 'sh_vendor_b'];
                if (!in_array($current_user_role, $available_roles)) :
                    //如果不在許可名單內，轉到申請成為經銷商頁面
                    wp_redirect(site_url() . '/be-a-vendor');
                    exit;
                else :
                    //如果在許可名單內，繼續，並加上body class
                    add_filter('body_class', function ($classes) {
                        array_push($classes, 'vendor_only');
                        return $classes;
                    }, 200);
                endif;
            }
        }

        public function yc_wps_setting($options)
        {
            //var_dump($options);
        }


        public function yc_set_default()
        {
            //修改預設資料
            update_option('thumbnail_size_w', 0);
            update_option('thumbnail_size_h', 0);
            update_option('medium_size_w', 0);
            update_option('medium_size_h', 0);
            update_option('large_size_w', 5000);
            update_option('large_size_h', 20000);
            update_option('thumbnail_crop', '');

            //WC
            if(IS_WC){
            update_option('woocommerce_allow_tracking', 'no');
            update_option('woocommerce_show_marketplace_suggestions', 'no');
        }

            //修改WP Statistics Read capability權限
            add_filter("wp_statistics_option_read_capability", function () {
                return 'read';
            }, 99, 1);
        }


        public function yc_get_current_user_level()
        {
            /*
             * 限制載入CSS跟JS的角色 Admin除外
             */
            if (!is_user_logged_in()) return;
            $user = wp_get_current_user();
            if ($user->roles[0] == 'administrator') {
                self::$current_user_level = 0;
                return;
            }
            $user_levels[0] = self::$level_0;
            $user_levels[1] = self::$level_1;
            $user_levels[2] = self::$level_2;
            $user_levels[3] = self::$level_3;
            foreach ($user_levels as $key => $user_level) {
                if (array_intersect($user_level, $user->roles)) {
                    self::$current_user_level = $key;
                    //return $key;
                }
            }
        }


        public function yc_hide_user($user_search)
        {
            global $current_user;
            $username = $current_user->user_login;
            $hide_users = self::$hide_user;

            if (!in_array($username, $hide_users)) {
                global $wpdb;
                $text = 'WHERE 1=1 ';
                foreach ($hide_users as $key => $hide_user) {
                    $text .= "AND {$wpdb->users}.user_login != '$hide_user' ";
                }
                $user_search->query_where = str_replace('WHERE 1=1', $text, $user_search->query_where);
            }
        }

        public function get_plugin_abs_path()
        {

            // gets the absolute path to this plugin directory
            return untrailingslashit(plugin_dir_path(__FILE__));
        }
        public function yc_i18n()
        {
            //debug
            load_plugin_textdomain('YC_TECH', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }
        public function activate()
        {
            flush_rewrite_rules();
        }
        public function deactivate()
        {
            flush_rewrite_rules();
        }
    }
}

if (class_exists('YC_TECH')) {
    $YC_TECH = new YC_TECH();
}

require_once(__DIR__ . '/constants.php');
require_once( __DIR__ . '/library/APF/admin-page-framework.php' );
require_once( __DIR__ . '/library/APF_Frontend_Form/admin-page-framework-frontend-form-beta.php' );
require_once(__DIR__ . '/class-Override.php');
require_once(__DIR__ . '/include/admin/include.php');
require_once(__DIR__ . '/include/sync/sync.php');
require_once(__DIR__ . '/include/shortcode/include.php');
require_once(__DIR__ . '/include/oneshop/oneshop.php');
require_once(__DIR__ . '/include/extensions/extensions.php');


//!!! FRONT END FORM!!!

/**
 * Activate the plugin.
 */
register_activation_hook(__FILE__, array($YC_TECH, 'activate'));

/**
 * Deactivation hook.
 */
register_deactivation_hook(__FILE__, array($YC_TECH, 'deactivate'));
