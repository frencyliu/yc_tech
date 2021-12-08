<?php

/**
 * menu page
 */

namespace YC\Admin;
use YC_TECH;

defined('ABSPATH') || exit;

class _Menu extends YC_TECH
{

    public function __construct()
    {
        add_filter('custom_menu_order', '__return_true', 10);
        add_action('admin_menu', [$this, 'yc_amp_setting'], 97);

    }

    //代辦：9li.uk-open選單會打開
    //自訂後台頁面
    public function yc_amp_setting()
    {
        switch (self::$current_user_level) {
            case 0:
                $this->yc_remove_menu_page_level_0();
                break;
            case 1:
                $this->yc_remove_menu_page_level_1();
                break;
            case 2:
                $this->yc_remove_menu_page_level_2();
                break;
            default:
                $this->yc_remove_menu_page_level_2();
                break;
        }

        //流量中心
        if (class_exists('WP_Statistics', false)) {
            add_menu_page(
                __('Traffic', 'YC_TECH'),
                __('Traffic', 'YC_TECH'),
                'read',
                'admin.php?page=wps_overview_page',
                '',
                'dashicons-chart-line', //icon
                null
            );
        }


        //訂單中心
        if (class_exists('WooCommerce', false)) {
            add_menu_page(
                __('Oders', 'YC_TECH'),
                __('Oders', 'YC_TECH'),
                'edit_shop_orders',
                'edit.php?post_type=shop_order',
                '',
                'dashicons-cart', //icon
                null
            );

            if (class_exists('WC_Order_Export_Admin', false)) {
                add_submenu_page(
                    'edit.php?post_type=shop_order',
                    __('Export Oders', 'YC_TECH'),
                    __('Export Oders', 'YC_TECH'),
                    'edit_shop_orders',
                    'admin.php?page=wc-order-export#segment=common',
                    '',
                    2
                );
            }

            if (class_exists('Zorem_Woocommerce_Advanced_Shipment_Tracking', false)) {

                add_menu_page(
                    __('Shipping', 'YC_TECH'),
                    __('Shipping', 'YC_TECH'),
                    'edit_shop_orders',
                    'admin.php?page=woocommerce-advanced-shipment-tracking',
                    '',
                    'dashicons-car', //icon
                    null
                );
            }
        }

        /*if (class_exists('User_import_export_Review_Request', false)) {
            //用戶中心
            add_submenu_page(
                'users.php',
                '匯出會員',
                '匯出會員',
                'edit_shop_orders',
                'admin.php?page=wt_import_export_for_woo_basic_export',
                '',
                2
            );
        }*/

        //行銷中心
        if (class_exists('WooCommerce', false)) {
            add_menu_page(
                __('Marketing', 'YC_TECH'),
                __('Marketing', 'YC_TECH'),
                'edit_shop_orders',
                'edit.php?post_type=shop_coupon',
                '',
                'dashicons-megaphone', //icon
                null
            );
            add_submenu_page(
                'edit.php?post_type=shop_coupon',
                __('Coupons', 'YC_TECH'),
                __('Coupons', 'YC_TECH'),
                'edit_shop_orders',
                'edit.php?post_type=shop_coupon',
                '',
                2
            );

            if (class_exists('WooCommerce_Coupon_Generator', false)) {
                add_submenu_page(
                    'edit.php?post_type=shop_coupon',
                    __('Generate Coupons', 'YC_TECH'),
                    __('Generate Coupons', 'YC_TECH'),
                    'edit_shop_orders',
                    'admin.php?page=woocommerce_coupon_generator',
                    '',
                    3
                );
            }
            //if (class_exists('The_SEO_Framework\Core', false)) {
            add_submenu_page(
                'edit.php?post_type=shop_coupon',
                __('SEO Settings', 'YC_TECH'),
                __('SEO Settings', 'YC_TECH'),
                'read',
                'admin.php?page=theseoframework-settings',
                '',
                4
            );
            //}
        } else {
            add_menu_page(
                __('Marketing', 'YC_TECH'),
                __('Marketing', 'YC_TECH'),
                'read',
                'admin.php?page=theseoframework-settings',
                '',
                'dashicons-megaphone', //icon
                null
            );
        }

        //網站設定

        /*add_submenu_page(
            'yc_setting',
            __('Homepage', 'YC_TECH'),
            __('Homepage', 'YC_TECH'),
            'edit_posts',
            'post.php?post=' . get_option('page_on_front') . '&action=edit',
            '',
            2
        );*/
        add_submenu_page(
            'yc_setting',
            __('Menus', 'YC_TECH'),
            __('Menus', 'YC_TECH'),
            'edit_posts',
            'nav-menus.php',
            '',
            3
        );

        if (class_exists('EasyWPSMTP', false)) {
            add_submenu_page(
                'yc_setting',
                __('Email Settings', 'YC_TECH'),
                __('Email Settings', 'YC_TECH'),
                'edit_shop_orders',
                'options-general.php?page=swpsmtp_settings#smtp',
                '',
                4
            );
        }

        //網站外觀選項




        //網路商店設定
        if (class_exists('WooCommerce', false)) {
            add_menu_page(
                __('Store Setting', 'YC_TECH'),
                __('Store Setting', 'YC_TECH'),
                'edit_shop_orders',
                'admin.php?page=wc-settings',
                '',
                'dashicons-store', //icon
                null
            );
            add_submenu_page(
                'admin.php?page=wc-settings',
                __('Shipping Cost', 'YC_TECH'),
                __('Shipping Cost', 'YC_TECH'),
                'edit_shop_orders',
                'admin.php?page=wc-settings&tab=shipping',
                '',
                2
            );
            add_submenu_page(
                'admin.php?page=wc-settings',
                __('Payment Method', 'YC_TECH'),
                __('Payment Method', 'YC_TECH'),
                'edit_shop_orders',
                'admin.php?page=wc-settings&tab=checkout',
                '',
                3
            );
            add_submenu_page(
                'admin.php?page=wc-settings',
                __('Privacy', 'YC_TECH'),
                __('Privacy', 'YC_TECH'),
                'edit_shop_orders',
                'admin.php?page=wc-settings&tab=account',
                '',
                4
            );
            add_submenu_page(
                'admin.php?page=wc-settings',
                __('Email Notification', 'YC_TECH'),
                __('Email Notification', 'YC_TECH'),
                'edit_shop_orders',
                'admin.php?page=wc-settings&tab=email',
                '',
                5
            );

            if (class_exists('THWCFD', false)) {
                add_submenu_page(
                    'admin.php?page=wc-settings',
                    __('Checkout Form', 'YC_TECH'),
                    __('Checkout Form', 'YC_TECH'),
                    'edit_shop_orders',
                    'admin.php?page=checkout_form_designer&tab=fields',
                    '',
                    6
                );
            }

            if (class_exists('WC_Facebookcommerce', false)) {
                add_submenu_page(
                    'admin.php?page=wc-settings',
                    __('Connect Facebook', 'YC_TECH'),
                    __('Connect Facebook', 'YC_TECH'),
                    'edit_shop_orders',
                    'admin.php?page=wc-facebook',
                    '',
                    7
                );
            }

            //if (class_exists('', false)) {
            add_submenu_page(
                'wpcf7',
                __('contact record', 'YC_TECH'),
                __('contact record', 'YC_TECH'),
                'read',
                'admin.php?page=cfdb7-list.php',
                '',
                7
            );
            //}

            /*add_submenu_page(
  'admin.php?page=wc-settings',
  '綠界電子發票設定',
  '綠界電子發票設定',
  'edit_shop_orders',
  'admin.php?page=wc-settings&tab=ecpayinvoice',
  '',
  7
  );*/
        }

        //聯絡表單
        /*add_menu_page(
  '聯絡表單',
  '聯絡表單',
  'read',
  '',
  '',
  'dashicons-clipboard', //icon
  null
  );*/

  //商品全局TAB
  add_submenu_page(
    'edit.php?post_type=product',
    __('商品全局TAB', 'YC_TECH'),
    __('商品全局TAB', 'YC_TECH'),
    'edit_posts',
    'admin.php?page=yikes-woo-settings',
    '',
    10
);



    add_menu_page(
                'BOOTSTRAP 變數設定',
                'BOOTSTRAP 變數設定',
                'read',
                'customize.php?return=%2Fwp-admin%2Fpost.php%3Fpost%3D202%26action%3Dedit',
                '',
                'dashicons-admin-appearance',
                6
            );




        if (WP_DEBUG) {
            //教學中心
            add_menu_page(
                __('Tutorial', 'YC_TECH'),
                __('Tutorial', 'YC_TECH'),
                'read',
                'yc_teach',
                [$this, 'yc_teach_page'],
                'dashicons-info', //icon
                null
            );
        }
    }



    public function yc_remove_menu_page_level_0()
    {
        remove_menu_page('yikes-woo-settings');
        remove_menu_page('revslider');
        remove_menu_page('theseoframework-settings');
        if(!WP_DEBUG){
            remove_submenu_page('edit.php?post_type=page', '#lc_click_action_new_page');
        }
        if (!TAG_ENABLE) {
            remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
            remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product');
            remove_submenu_page('edit.php?post_type=product', 'product_attributes');
        }


    }



    public function yc_remove_menu_page_level_1()
    {
        $this->yc_remove_menu_page_level_0();
        //remove_submenu_page( string $menu_slug, string $submenu_slug )
        //移除主選單
        remove_menu_page('index.php');

        if (!COMMENTS_ENABLE) {
            remove_menu_page('edit-comments.php');
        }
        remove_menu_page('plugins.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('themes.php');
        remove_menu_page('et_bloom_options');
        remove_menu_page('et_divi_options');
        remove_menu_page('theseoframework-settings');
        remove_menu_page('wps_overview_page');
        remove_menu_page('facebook-messenger-customer-chat');
        remove_menu_page('edit.php?post_type=divi_mega_pro');
        remove_menu_page('wpclever');
        remove_menu_page('edit.php?post_type=dipl-testimonial');
        remove_menu_page('edit.php?post_type=dipl-team-member');
        remove_menu_page('media-cloud');
        remove_menu_page('media-cloud-tools');
        remove_menu_page('WP-Optimize');
        remove_menu_page('cfdb7-extensions');
        remove_menu_page('berocket_account');




        //分析 - 移除下載跟稅金
        remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/taxes');
        remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/downloads');

        //remove_menu_page('upload.php');
        //remove_submenu_page('upload.php', 'upload.php');
        remove_submenu_page('upload.php', 'media-new.php');
        remove_submenu_page('upload.php', 'ewww-image-optimizer-bulk');

        /*cfdb7 contact form 7*/
        remove_submenu_page('wpcf7', 'wpcf7-integration');
        remove_menu_page('cfdb7-list.php');

        /* Support Candy */
        remove_submenu_page('wpsc-tickets', 'wpsc-custom-fields');
        remove_submenu_page('wpsc-tickets', 'wpsc-ticket-list');
        remove_submenu_page('wpsc-tickets', 'wpsc-settings');
        remove_submenu_page('wpsc-tickets', 'wpsc-license');
        remove_submenu_page('wpsc-tickets', 'wpsc-add-ons');

        /*Custom Product Tabs for WooCommerce*/
        remove_submenu_page('yikes-woo-settings', 'yikes-woo-premium');
        remove_submenu_page('yikes-woo-settings', 'yikes-woo-support');


        //WP statistic
        /*remove_submenu_page('wps_overview_page', 'wps_overview_page');
        remove_submenu_page('wps_overview_page', 'wps_hits_page');
        remove_submenu_page('wps_overview_page', 'wps_visitors_page');
        remove_submenu_page('wps_overview_page', 'wps_referrers_page');
        remove_submenu_page('wps_overview_page', 'wps_words_page');
        remove_submenu_page('wps_overview_page', 'wps_searches_page');
        remove_submenu_page('wps_overview_page', 'wps_pages_page');
        remove_submenu_page('wps_overview_page', 'wps_categories_page');
        remove_submenu_page('wps_overview_page', 'wps_tags_page');
        remove_submenu_page('wps_overview_page', 'wps_browser_page');
        remove_submenu_page('wps_overview_page', 'wps_platform_page');
        remove_submenu_page('wps_overview_page', 'wps_top-visitors_page');
        remove_submenu_page('wps_overview_page', 'wps_optimization_page');
        remove_submenu_page('wps_overview_page', 'wps_authors_page');
        remove_submenu_page('wps_overview_page', 'wps_settings_page');
        remove_submenu_page('wps_overview_page', 'wps_plugins_page');
        remove_submenu_page('wps_overview_page', 'wps_donate_page');
        */
    }
    public function yc_remove_menu_page_level_2()
    {
        $this->yc_remove_menu_page_level_1();
        remove_menu_page('loco');
        remove_menu_page('ags-layouts');
    }



    public function yc_teach_page()
    {
        echo '<h2>還在吸取日月精華...</h2>';
    }



}
new _Menu();

require_once __DIR__ . '/1_Menu_ReName.php';
require_once __DIR__ . '/2_Menu_ReOrder.php';
require_once __DIR__ . '/3_AddMenu_Advance.php';