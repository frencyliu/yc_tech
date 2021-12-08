<?php

/**
 * custom meta box
 * https://github.com/mustafauysal/post-meta-box-order/blob/master/post-meta-box-order.php
 * https://developer.wordpress.org/reference/functions/do_meta_boxes/
 *
 * https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
 *
 *  https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
 *
 * https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
 *
 * manage_category_custom_column
 * manage_edit-category_columns
 */


namespace YC\Admin\metabox;

use YC_TECH;

defined('ABSPATH') || exit;


class _Default extends YC_TECH
{

    //預設隱藏以及縮合起來的METABOX
    static $hidden_metabox = ['slugdiv', 'postcustom', 'trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv', 'wp-statistics-post-widget', 'pageparentdiv', 'postexcerpt', 'tsf-inpost-box', 'lca_meta_boxes'];

    static $close_metabox = [];

    static $hide_product_column = ['is_in_stock', 'product_tag', 'featured', 'tsf-quick-edit'];



    public function __construct()
    {
        if (!WP_DEBUG || \YC\Admin\Custom_Admin::$current_user_level != 0) {
            add_action('add_meta_boxes', [$this, 'yc_remove_metabox'], 100);

            add_filter('get_user_option_meta-box-order_post', [$this, 'yc_reorder_post_metabox'], 99);

            add_filter('get_user_option_meta-box-order_page', [$this, 'yc_reorder_post_metabox'], 99);
            add_filter('get_user_option_meta-box-order_product', [$this, 'yc_reorder_post_metabox'], 99);


            //讓新註冊的用戶預設關閉的MetaBox
            add_action('user_register', [$this, 'yc_save_on_registration'], 10, 1);
            //刷新所有用戶，預設關閉的MetaBox
            add_action('admin_init', [$this, 'yc_flush_metabox_setting'], 10, 1);

            // 修改column
            $taxonomys = ['category', 'post_tag', 'product_cat', 'product_tag'];
            foreach ($taxonomys as $taxonomy) {
                add_filter('manage_edit-' . $taxonomy . '_columns', [$this, 'yc_remove_column_taxonomy'], 99, 1);
            }
            $post_types = ['post', 'page', 'portfolio', 'project'];
            foreach ($post_types as $post_type) {
                add_filter('manage_edit-' . $post_type . '_columns', [$this, 'yc_remove_column_posts'], 99, 1);
            }


            add_filter('manage_edit-shop_order_columns', [$this, 'yc_remove_column_shop_order'], 99, 1);

            add_filter('manage_edit-product_columns', [$this, 'yc_remove_column_product'], 99, 1);



            add_filter('manage_users_columns', [$this, 'yc_remove_column_user'], 99, 1);

        }
    }


    function yc_remove_metabox()
    {

        //remove in all post_type
$all_post_types = get_post_types();
foreach ($all_post_types as $post_type) {
    remove_meta_box(
        'lca_meta_boxes',
        $post_type,
        'side'
    );

    remove_meta_box(
        'simple_css_metabox',
        $post_type,
        'normal'
    );

    remove_meta_box(
        'the_champ_meta',
        $post_type,
        'advanced'
    );

    remove_meta_box(
        'postcustom',
        $post_type,
        'normal'
    );
    remove_meta_box(
        'commentsdiv', //留言
        $post_type,
        'normal'
    );

    remove_meta_box(
        'formatdiv',
        $post_type,
        'side'
    );
    remove_meta_box(
        'slider_revolution_metabox',
        $post_type,
        'side'
    );
    remove_meta_box(
        'pageparentdiv',
        $post_type,
        'side'
    );

    /*remove_meta_box(
        'slugdiv',//代稱
        $post_type,
        'normal'
    );*/

    if(!TAG_ENABLE){
        remove_meta_box(
            'tagsdiv-' . $post_type . '_tag',
            $post_type,
            'side'
        );
    }
}
//YC_TECH::DEBUG($all_post_types);


        //SHOP_ORDER
        remove_meta_box(
            'postcustom',
            'shop_order',
            'normal'
        );
        remove_meta_box(
            'woocommerce-order-downloads',
            'shop_order',
            'normal'
        );
    }

    function yc_reorder_post_metabox($order)
    {
        $post_type = get_post_type();

        //join=將 array轉為字串
        switch ($post_type) {
            case 'post':
                return array(
                    'normal'   => join(",", array(
                        'postimagediv',
                        'tsf-inpost-box',
                        'slider_revolution_metabox',

                    )),
                    'side'     => join(",", array(
                        'submitdiv',
                        'categorydiv',
                        'tagsdiv-post_tag',
                        'pageparentdiv',
                    )),
                    'advanced' => join(",", array(
                        'postexcerpt',
                        'authordiv',
                    )),
                );
                break;
            case 'page':
                return array(
                    'normal'   => join(",", array(
                        'postimagediv',
                        'tsf-inpost-box',
                        'slider_revolution_metabox',

                    )),
                    'side'     => join(",", array(
                        'submitdiv',
                        'categorydiv',
                        'tagsdiv-post_tag',
                        'pageparentdiv',
                    )),
                    'advanced' => join(",", array(
                        'postexcerpt',
                        'authordiv',
                    )),
                );
                break;
            case 'product':
                return array(
                    'normal'   => join(",", array(
                        'woocommerce-product-data',
                        'postimagediv',
                        'woocommerce-product-images',
                        'tsf-inpost-box',
                        'slider_revolution_metabox',

                    )),
                    'side'     => join(",", array(
                        'submitdiv',
                        'categorydiv',
                        'tagsdiv-post_tag',
                        'pageparentdiv',
                    )),
                    'advanced' => join(",", array(
                        'postexcerpt',
                        'authordiv',
                    )),
                );
                break;

                case 'video':
                    return array(
                        'normal'   => join(",", array(
                            'postimagediv',
                            'tsf-inpost-box',
                            'slider_revolution_metabox',
                        )),
                        'side'     => join(",", array(
                            'submitdiv',
                        )),
                    );
                    break;



            default:
                # code...
                break;
        }
    }




    function yc_save_on_registration($user_id)
    {


        //update_user_meta($user_id, 'closedpostboxes_post', $hide_metabox);
        update_user_meta($user_id, 'metaboxhidden_post', self::$hidden_metabox);
        update_user_meta($user_id, 'closedpostboxes_post', self::$close_metabox);
        update_user_meta($user_id, 'metaboxhidden_page', self::$hidden_metabox);
        update_user_meta($user_id, 'closedpostboxes_page', self::$close_metabox);
        update_user_meta($user_id, 'metaboxhidden_product', self::$hidden_metabox);
        update_user_meta($user_id, 'closedpostboxes_product', self::$close_metabox);
        update_user_meta($user_id, 'manageedit-shop_ordercolumnshidden', array());
        update_user_meta($user_id, 'manageedit-productcolumnshidden', self::$hide_product_column);
    }
    function yc_flush_metabox_setting()
    {
        if (FLUSH_METABOX == true) {
            $users = get_users();
            foreach ($users as $user) {
                $user_id = $user->data->ID;
                update_user_meta($user_id, 'metaboxhidden_post', self::$hidden_metabox);
                update_user_meta($user_id, 'closedpostboxes_post', self::$close_metabox);
                update_user_meta($user_id, 'metaboxhidden_page', self::$hidden_metabox);
                update_user_meta($user_id, 'closedpostboxes_page', self::$close_metabox);
                update_user_meta($user_id, 'metaboxhidden_product', self::$hidden_metabox);
                update_user_meta($user_id, 'closedpostboxes_product', self::$close_metabox);
                update_user_meta($user_id, 'manageedit-shop_ordercolumnshidden', array());
                update_user_meta($user_id, 'manageedit-productcolumnshidden', self::$hide_product_column);
            }
        }
    }

    function yc_remove_column_taxonomy($columns)
    {
        //var_dump($columns);
        unset($columns['tsf-seo-bar-wrap']);
        unset($columns['wp-statistics-tax-hits']);
        return $columns;
    }
    function yc_remove_column_posts($columns)
    {
        //var_dump($columns);
        unset($columns['wp-statistics-post-hits']);
        return $columns;
    }

    function yc_remove_column_shop_order($columns)
    {
        //var_dump($columns);
        unset($columns['woe_export_status']);
        $columns = array(
            'cb' => '<input type="checkbox">',
            'order_number' => '訂單',
            'order_date' => '訂單日期',
            'order_status' => '狀態',
            'billing_address' => '帳單',
            'shipping_address' => '運送至',
            'woocommerce-advanced-shipment-tracking' => '配送狀態',
            'order_total' => '總計',
            'wc_actions' => '動作',
        );
        return $columns;
    }

    function yc_remove_column_product($columns)
    {

        $columns = array(
            'cb' => '<input type="checkbox">',
            'thumb' => '圖片',
            'name' => '名稱',
            'featured' => '精選',
            'price' => '價格',
            'is_in_stock' => '庫存',
            'sku' => '貨號',
            'product_cat' => '分類',
            'product_tag' => '標籤',
            'tsf-seo-bar-wrap' => 'SEO',
            'date' => '發佈日期',
            'tsf-quick-edit' => '',
        );
        return $columns;
    }

    function yc_remove_column_user($columns)
    {

        unset($columns['heateor_ss_delete_profile_data']);

        return $columns;
    }
}

new _Default();

require_once __DIR__ . '/1_LC_builder_modify.php';