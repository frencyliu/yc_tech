<?php

/**
 * Video post type
 */

namespace YC\Admin;

use YC_TECH;

defined('ABSPATH') || exit;

class _User extends YC_TECH
{

    public function __construct()
    {



        add_action('admin_init', [$this, 'init'], 100);
        add_action('current_screen', [$this, 'current_screen'], 100);
        add_filter('woocommerce_customer_meta_fields', [$this, 'YC_WC_customer_field']);


        add_action('personal_options_update', [$this, 'save_customer_meta_fields']);
        add_action('edit_user_profile_update', [$this, 'save_customer_meta_fields']);



        //add_action( 'admin_head', [$this, 'test'] );
    }
    public function init()
    {
        //remove_all_actions('show_user_profile', 10);
        //remove_all_actions('edit_user_profile', 10);


    }

    public function current_screen()
    {
        //The SEO framework
        remove_action('show_user_profile', ['The_SEO_Framework\Bridges\UserSettings', '_prepare_setting_fields'], 0, 1);
        remove_action('edit_user_profile', ['The_SEO_Framework\Bridges\UserSettings', '_prepare_setting_fields'], 0, 1);

        //Super socialize
        remove_action('edit_user_profile', 'the_champ_show_avatar_option');
        remove_action('show_user_profile', 'the_champ_show_avatar_option');
        //remove_all_actions('show_user_profile', 0);
        //remove_all_actions('edit_user_profile', 0);
    }
    public function YC_WC_customer_field($fields)
    {

        $fields['billing']['title'] = '個人資料';
        $fields['billing']['fields']['billing_address_1']['label'] = '地址';
        unset($fields['shipping']);
        unset($fields['billing']['fields']['billing_last_name']);
        unset($fields['billing']['fields']['billing_address_2']);
        unset($fields['billing']['fields']['billing_city']);
        unset($fields['billing']['fields']['billing_postcode']);
        unset($fields['billing']['fields']['billing_country']);
        unset($fields['billing']['fields']['billing_state']);
        //unset($fields['billing']['fields']['billing_email']);

        //YC_TECH::DEBUG($fields);

        return $fields;
    }

    public function save_customer_meta_fields($user_id)
    {
        if (!apply_filters('woocommerce_current_user_can_edit_customer_meta_fields', current_user_can('manage_woocommerce'), $user_id)) {
            return;
        }
    }
}
new _User();

require_once __DIR__ . '/1_Wallet.php';
