<?php

/**
 * Video post type
 */

namespace YC\Admin\Column;

use YC_TECH;

defined('ABSPATH') || exit;

//秀幾個月前的訂單金額
define('ORDER_HISTORY', 4);

class _Default extends YC_TECH
{

    public function __construct()
    {

        if (!TAG_ENABLE) {
            add_filter("manage_post_posts_columns", function ($columns) {
                unset($columns['tags']);
                unset($columns['cs_replacement-hide']);
                return $columns;
            }, 200);

        }

        //設定欄位標題
        add_filter('manage_users_columns', [$this, 'set_custom_edit_users_columns'], 200, 1);
        //設定欄位值
        add_filter('manage_users_custom_column', [$this, 'custom_users_column'], 200, 3);
        //add_action( 'admin_head', [$this, 'get_order_data_by_user_date'],200 );
    }

    public function get_order_data_by_user_date($user_id = 2, $months_ago = 0, $args = array())
    {
        if (!IS_WC) return;
        $month = current_time('m') - $months_ago;
        $year = current_time('Y');

        $user = get_userdata($user_id);
        $user_registed_year = date('Y', strtotime($user->data->user_registered));
        $user_registed_month = date('m', strtotime($user->data->user_registered));

        if ($year == $user_registed_year && $month == $user_registed_month) {
            $specific_date = strtotime(date("Y-m-d", strtotime("first day of +1 month", time())));
            //判斷用戶是否註冊
            $is_registered = (strtotime($user->data->user_registered) <= $specific_date) ? true : false;
        } else {
            $is_registered = ($year >= $user_registed_year && $month >= $user_registed_month) ? true : false;
        }
        if (empty($args)) {
            $args = array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => $user_id,
                'post_type'   => array('shop_order'),
                'post_status' => array('wc-completed', 'wc-processing'),
                'date_query' => array(
                    'year'  => $year,
                    'month' => $month
                )
            );
        }
        $customer_orders = get_posts($args);
        $total = 0;
        foreach ($customer_orders as $customer_order) {
            $order = wc_get_order($customer_order);
            $total += $order->get_total();
        }
        $order_data['total'] = $total;
        $order_data['order_num'] = count($customer_orders);
        $order_data['user_is_registered'] = $is_registered;
        $user_role = get_user_by('id', $user_id)->roles[0];
        switch ($user_role) {
            case 'sh_vendor_a': //Administrator sh_vendor_a
                $goal = 20000;
                $order_data['goal'] = ($order_data['total'] >= $goal) ? 'yes' : 'no'; //yes=達標 no=不達標
                break;
            case 'administrator': //administrator sh_vendor_a
                $goal = 20000;
                $order_data['goal'] = ($order_data['total'] >= $goal) ? 'yes' : 'no'; //yes=達標 no=不達標
                break;
            case 'sh_vendor_b':
                $goal = 6000;
                $order_data['goal'] = ($order_data['total'] >= $goal) ? 'yes' : 'no'; //yes=達標 no=不達標
                break;
            default:
                $goal = 0;
                $order_data['goal'] = 'no_goal';
                break;
        }

        return $order_data;
    }






    public function set_custom_edit_users_columns($columns)
    {
        if (!IS_WC) return $columns;
        //$columns['user_id'] = 'User ID';
        $order = (@$_REQUEST['ts_all'] == 'DESC') ? 'ASC' : 'DESC';
        $columns['total_order_amount'] = '<a title="用戶註冊後至今累積總消費金額" href="?ts_all=' . $order . '">全部</a>';

        for ($i = 0; $i < ORDER_HISTORY; $i++) {
            $order = (@$_REQUEST['ts' . $i] == 'DESC') ? 'ASC' : 'DESC';
            $month = current_time('m') - $i;
            $columns['ts' . $i] = '<a title="' . $month . '月累積採購金額" href="?ts' . $i . '=' . $order . '">' . $month . '月</a>';
        }
        $columns['goal_gg'] = '<span title="連續未達標幾個月等狀態(不計算當月)">狀態</span>'; //

        unset($columns['wfls_last_login']);
        unset($columns['posts']);
        unset($columns['wfls_2fa_status']);
        // ["cb"]=> string(25) "" ["username"]=> string(8) "Username" ["name"]=> string(4) "Name" ["email"]=> string(5) "Email" ["role"]=> string(4) "Role" ["posts"]=> string(5) "Posts" ["wfls_2fa_status"]=> string(10) "2FA Status" ["wfls_last_login"]=> string(10) "Last Login" ["user_id"]=> string(7) "User ID" } array(9) { ["cb"]=> string(25) "" ["username"]=> string(8) "Username" ["name"]=> string(4) "Name" ["email"]=> string(5) "Email" ["role"]=> string(4) "Role" ["posts"]=> string(5) "Posts" ["wfls_2fa_status"]=> string(10) "2FA Status" ["wfls_last_login"]=> string(10) "Last Login" ["user_id"]=> string(7) "User ID" }
        return $columns;
    }

    public function custom_users_column($default_value, $column_name, $user_id)
    {
        if (!IS_WC) return;
        $default_value = '0';
        //$user = get_userdata( $user_id );
        for ($i = 0; $i < 4; $i++) {
            if ($column_name == 'ts' . $i) {
                //$order_data = $this->get_order_data_by_user_date($user_id, $i);
                $order_data = $this->get_order_data_by_user_date($user_id, $i);
                update_user_meta($user_id, '_total_sales_in_' . $i . '_months_ago', $order_data['total']);
                if ($order_data['user_is_registered']) {
                    switch ($order_data['goal']) {
                        case 'no_goal':
                            $text = '';
                            break;
                        case 'yes':
                            $text = '<span class="yc_success">達標<span>';
                            break;
                        case 'no':
                            $text = '<span class="yc_warning">不達標<span>';
                            break;
                        default:
                            $text = '';
                            break;
                    };
                } else {
                    return '<span class="yc_general">當時尚未註冊</span>';
                }
                $html = 'NT$ ' . $order_data['total'] . '<br>訂單' . $order_data['order_num'] . '筆<br>' . $text;
                return $html;
            }
        }
        switch ($column_name) {
            case 'goal_gg':

                $total_in_six_months = 0;
                for ($i = 1; $i < 7; $i++) {
                    $order_data = $this->get_order_data_by_user_date($user_id, $i);
                    $goal_status_in_six_months[$i] = $order_data['goal'];
                    $total_in_six_months += $order_data['total'];
                }

                $count = 0;
                for ($i = 1; $i < 7; $i++) {
                    if ($goal_status_in_six_months[$i] == 'yes')
                        $count++;
                }
                if ($count == 6) { //連續N個月達標
                    $bonus = $total_in_six_months * 0.05;
                    return '<span class="yc_success">連續' . $count . '個月達標</span><br>總採購金額NT$ ' . $total_in_six_months . '<br>5%金額為NT$ ' . $bonus;
                }


                for ($i = 1; $i < 7; $i++) {
                    $order_data = $this->get_order_data_by_user_date($user_id, $i);
                    if ($order_data['user_is_registered']) {
                        if ($order_data['goal'] == 'no') {
                            if ($i >= 4) {
                                $j = $i - 1;
                                return '<span class="yc_warning">連續' . $j . '個月未達標</span>';
                                exit;
                            }
                        } elseif ($i == 1 && $order_data['goal'] == 'yes') {
                            return '';
                            exit;
                        } elseif ($order_data['goal'] == 'yes') {
                            $j = $i - 1;
                            return '<span class="yc_warning">連續' . $j . '個月未達標</span>';
                            exit;
                        }
                    } else {
                        return '<span class="yc_general">註冊未滿3個月</span>';
                    }
                }

                break;
            case 'total_order_amount':
                $args = array(
                    'numberposts' => -1,
                    'meta_key'    => '_customer_user',
                    'meta_value'  => $user_id,
                    'post_type'   => array('shop_order'),
                    'post_status' => array('wc-completed', 'wc-processing'),
                );
                $order_data = $this->get_order_data_by_user_date($user_id, 0, $args);
                update_user_meta($user_id, '_total_sales_in_life', $order_data['total']);

                $html = 'NT$ ' . $order_data['total'] . '<br>訂單' . $order_data['order_num'] . '筆';
                return $html;

            default:
                return $default_value;
                break;
        }
    }
}
new _Default();
