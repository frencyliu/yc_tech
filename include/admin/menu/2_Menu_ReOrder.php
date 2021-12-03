<?php

/**
 * menu order
 */

namespace YC\Admin;
use YC_TECH;

defined('ABSPATH') || exit;

class _Menu_ReOrder extends YC_TECH
{

    public function __construct()
    {
        add_filter('menu_order', [$this, 'yc_menu_reorder'], 100, 1);
    }

    //調整主選單順序
    public function yc_menu_reorder($menu_ord)
    {
        if (!$menu_ord) {
            return true;
        }
        global $menu;
        //--debug--//
        /*echo '<pre>';
        var_dump($menu);
        echo '</pre>';*/
        //--debug--//

        return array(
            //'index.php',
            'admin.php?page=wps_overview_page',
            'wc-admin&path=/analytics/overview',
            'edit.php?post_type=shop_order',
            'admin.php?page=woocommerce-advanced-shipment-tracking',
            'edit.php?post_type=product',
            'edit.php',
            'edit.php?post_type=page',
            'edit.php?post_type=project',
            'admin.php?page=et_theme_builder',
            'yc_setting',
            'admin.php?page=wc-settings',
            'users.php',
            'edit.php?post_type=shop_coupon',
            'admin.php?page=theseoframework-settings',
            'upload.php',
            'loco',
            //'yc_extention',
            //'yc_teach',
        );
    }

}
new _Menu_ReOrder();