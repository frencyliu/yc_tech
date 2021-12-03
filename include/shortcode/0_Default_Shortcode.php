<?php

/**
 * Element Shortcode
 * https://pagely.com/blog/creating-custom-shortcodes/
 */

namespace YC\ShortCode;
use YC_TECH;


defined('ABSPATH') || exit;


class _General extends YC_TECH
{


    public function __construct()
    {
        add_action('init', [$this, 'yc_add_shortcode']);
    }

    public function yc_add_shortcode()
    {
        //login form
        add_shortcode('yc_login_form', 'yc_login_form_function');

        add_shortcode('dotifollow', [$this, 'dotifollow_function']);
        add_shortcode('get_monthly_sales', [$this, 'get_monthly_sales_f']);
        add_shortcode('get_last_monthly_sales', [$this, 'get_last_monthly_sales_f']);
        add_shortcode('get_dates_sales', [$this, 'get_dates_sales_f']);
    }

    /*
    add_action( 'login_form_middle', 'add_lost_password_link' );
function add_lost_password_link() {
    return '<a href="/wp-login.php?action=lostpassword">Forgot Your Password?</a>';
}*/


  function yc_login_form_function()
  {

    return 'pdguoagnoagdagamgoomvanoagniagnadgnoangomodo ga ';
      /*
      $args = array(
        'echo'           => true,
        'remember'       => true,
        'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'form_id'        => 'loginform',
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'label_username' => __( 'Username or Email Address', 'yc_tech' ),
        'label_password' => __( 'Password', 'yc_tech' ),
        'label_remember' => __( 'Remember Me', 'yc_tech' ),
        'label_log_in'   => __( 'Log In', 'yc_tech' ),
        'value_username' => '',
        'value_remember' => false
    );
    wp_login_form($args);
    */
    //add_lost_password_link();
  }

    public function get_monthly_sales_f()
    {
        if(!IS_WC) return '沒有開啟電商功能';
        /**
         * 當月銷量
         */
        global $woocommerce, $wpdb, $product;
        include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');

        // WooCommerce Admin Report
        $wc_report = new \WC_Admin_Report();

        // Set date parameters for the current month
        $start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight');
        $end_date = strtotime('+1month', $start_date) - 86400;
        $wc_report->start_date = $start_date;
        $wc_report->end_date = $end_date;

        // Avoid max join size error
        $wpdb->query('SET SQL_BIG_SELECTS=1');

        $last_month = (array) $wc_report->get_order_report_data(
            array(
                'data'         => array(
                    '_order_total'        => array(
                        'type'     => 'meta',
                        'function' => 'SUM',
                        'name'     => 'total_sales',
                    ),
                ),
                'group_by'     => $wc_report->group_by_query,
                'order_by'     => 'post_date ASC',
                'query_type'   => 'get_results',
                'filter_range' => 'month',
                'order_types'  => wc_get_order_types('sales-reports'),
                'order_status' => array('completed', 'processing', 'on-hold', 'refunded'),
            )
        );

        $html = '';
        $html .= get_woocommerce_currency_symbol() . intval($last_month[0]->total_sales);
        //$html .= '<p>開始時間：' . date("Y-m-d", $start_date) . '</p>';
        //$html .= '<p>結束時間：' . date("Y-m-d", $end_date) . '</p>';

        return $html;
    }


    public function get_last_monthly_sales_f()
    {
        if(!IS_WC) return '沒有開啟電商功能';
        /**
         * 上月銷量
         */

        global $woocommerce, $wpdb, $product;
        include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');

        // WooCommerce Admin Report
        $wc_report = new \WC_Admin_Report();

        // Set date parameters for the current month
        $this_month_start = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight');
        $start_date = strtotime('-1month', $this_month_start);
        $end_date = strtotime('+1month', $start_date) - 86400;
        $wc_report->start_date = $start_date;
        $wc_report->end_date = $end_date;

        // Avoid max join size error
        $wpdb->query('SET SQL_BIG_SELECTS=1');

        $last_month = (array) $wc_report->get_order_report_data(
            array(
                'data'         => array(
                    '_order_total'        => array(
                        'type'     => 'meta',
                        'function' => 'SUM',
                        'name'     => 'total_sales',
                    ),
                ),
                'group_by'     => $wc_report->group_by_query,
                'order_by'     => 'post_date ASC',
                'query_type'   => 'get_results',
                'filter_range' => 'month',
                'order_types'  => wc_get_order_types('sales-reports'),
                'order_status' => array('completed', 'processing', 'on-hold', 'refunded'),
            )
        );

        $html = '';
        $html .= get_woocommerce_currency_symbol() . intval($last_month[0]->total_sales);
        //$html .= '<p>開始時間：' . date("Y-m-d", $start_date) . '</p>';
        //$html .= '<p>結束時間：' . date("Y-m-d", $end_date) . '</p>';

        return $html;
    }

    public function get_dates_sales_f($atts = [])
    {

        if(!IS_WC) return '沒有開啟電商功能';
        /**
         * 指定時間銷量
         */

        // set up default parameters
        /**
         * 預設是昨天
         */
        extract(shortcode_atts([
            'StartDay' => date('Y-m-d', strtotime('-1day', current_time('timestamp'))),
            'EndDay' => date('Y-m-d', current_time('timestamp'))
        ], $atts));


        global $woocommerce, $wpdb, $product;
        include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');

        // WooCommerce Admin Report
        $wc_report = new \WC_Admin_Report();


        // Set date parameters for the current month
        $start_date = strtotime($StartDay . ' midnight');
        $end_date = strtotime($EndDay . ' midnight');
        $wc_report->start_date = $start_date;
        $wc_report->end_date = $end_date;

        // Avoid max join size error
        $wpdb->query('SET SQL_BIG_SELECTS=1');

        $last_month = (array) $wc_report->get_order_report_data(
            array(
                'data'         => array(
                    '_order_total'        => array(
                        'type'     => 'meta',
                        'function' => 'SUM',
                        'name'     => 'total_sales',
                    ),
                ),
                'group_by'     => $wc_report->group_by_query,
                'order_by'     => 'post_date ASC',
                'query_type'   => 'get_results',
                'filter_range' => 'month',
                'order_types'  => wc_get_order_types('sales-reports'),
                'order_status' => array('completed', 'processing', 'on-hold', 'refunded'),
            )
        );

        $html = '';
        $html .= get_woocommerce_currency_symbol() . intval($last_month[0]->total_sales);
        //$html .= '<p>開始時間：' . date("Y-m-d H:i:s", $start_date) . '</p>';
        //$html .= '<p>結束時間：' . date("Y-m-d H:i:s", $end_date) . '</p>';

        return $html;
    }
}

new _General();

