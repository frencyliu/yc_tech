<?php

namespace YC\Admin\CPT\Bet;

if (!class_exists('yc_AdminPageFramework_PostType')) return;

class _Menu_Bet extends \yc_AdminPageFramework_PostType
{

    /**
     * This method is called at the end of the constructor.
     *
     * ALternatevely, you may use the start_{instantiated class name} method, which also is called at the end of the constructor.
     */
    public function start()
    {
    }
    /**
     * Use this method to set up the post type.
     *
     * ALternatevely, you may use the set_up_{instantiated class name} method, which also is called at the end of the constructor.
     */
    public function setUp()
    {

        $this->setArguments(
            array( // argument - for the array structure, refer to http://codex.wordpress.org/Function_Reference/register_post_type#Arguments
                'labels' => array(
                    'name'               => __('下注列表', 'yc_tech'),
                    'singular_name'      => __('下注', 'yc_tech'),
                    'add_new'            => __('新增下注', 'yc_tech'),
                    'add_new_item'       => __('新增下注', 'yc_tech'),
                    'edit_item'          => __('編輯下注', 'yc_tech'),
                    'new_item'           => __('新下注', 'yc_tech'),
                    'all_items'          => __('全部下注', 'yc_tech'),
                    'view_item'          => __('查看下注', 'yc_tech'),
                    'search_items'       => __('搜尋下注', 'yc_tech'),
                    'not_found'          => __('目前還沒有下注', 'yc_tech'),
                    'not_found_in_trash' => __('回收桶裡沒找到', 'yc_tech'),
                    'menu_name'          => __('下注列表', 'yc_tech'),
                ),
                'menu_icon'         => 'dashicons-money-alt',
                'show_in_rest'      => false,


                'show_admin_column' => true, // [3.5+ core] this is for custom taxonomies to automatically add the column in the listing table.
                // (framework specific) [3.5.10+] default: true
                'show_submenu_add_new'  => true,
                // (framework specific) [3.7.4+]
                'submenu_order_manage' => 5,   // default 5
                'submenu_order_addnew' => 9,   // default 10

                'public'              => false,
                'show_ui'             => true,
                'map_meta_cap'        => true,
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'hierarchical'        => false,
                'show_in_nav_menus'   => false,
                'rewrite'             => false,
                'query_var'           => false,
                'supports'            => array('title', 'comments', 'custom-fields'),
                'has_archive'         => false,
            )
        );

    }

    /**
     * Modifies the output of the post content.
     *
     * This method is called in the single page of this class post type.
     *
     * Alternatively, you may use the 'content_{instantiated class name}' method,
     */
    public function content($sContent)
    {

        // 1. To retrieve the meta box data - get_post_meta( $post->ID ) will return an array of all the meta field values.
        // or if you know the field id of the value you want, you can do $value = get_post_meta( $post->ID, $field_id, true );
        $_iPostID   = $GLOBALS['post']->ID;
        $_aPostData = array();

        foreach ((array) get_post_custom_keys($_iPostID) as $_sKey) {    // This way, array will be unserialized; easier to view.
            $_aPostData[$_sKey] = get_post_meta($_iPostID, $_sKey, true);
        }

        // Or you may do this but the nested elements will be a serialized array.
        // $_aPostData = get_post_custom( $_iPostID ) ;

        // 2. To retrieve the saved options in the setting pages created by the framework - use the get_option() function.
        // The key name is the class name by default. The key can be changed by passing an arbitrary string
        // to the first parameter of the constructor of the AdminPageFramework class.
        $_aSavedOptions = get_option('_Menu_Bet');

        return "<h3>" . __('Saved Meta Field Values of the Post', 'yc_tech-loader') . "</h3>"
            . $this->oDebug->get($_aPostData)
            . "<h3>" . __('Saved Setting Options of The Loader Plugin', 'yc_tech-loader') . "</h3>"
            . $this->oDebug->get($_aSavedOptions);
    }

    public function columns_gg_bet($aHeaderColumns)
    {

        return array_merge(
            $aHeaderColumns,
            array(
                'cb'                => '<input type="checkbox" />', // Checkbox for bulk actions.
                'title'             => __('下注單號', 'yc_tech'), // Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
                'author'            => __('會員', 'yc_tech'), // Post author.
                // 'categories'     => __( 'Categories', 'yc_tech' ), // Categories the post belongs to.
                // 'tags' => __( 'Tags', 'yc_tech' ), // Tags for the post.
                'date'              => __('下注時間', 'yc_tech'),     // The date and publish status of the post.
                'amount'              => __('點數', 'yc_tech'),
                'status'              => __('狀態', 'yc_tech'), //進行中、已結束、取消下注
                'balance'              => __('結算', 'yc_tech'),
                'gg_game'              => __('賽事', 'yc_tech'),
            )
        );
    }

    /**
     *
     * @callback        filter      sortable_columns_{post type slug}
     */
    public function sortable_columns_gg_bet($aSortableHeaderColumns)
    {
        return $aSortableHeaderColumns + array(
            'author' => 'author',
            'amount' => 'amount',
            'status' => 'status',
            'balance' => 'balance',
            'gg_game' => 'gg_game',
        );
    }

    /**
     *
     * @callback        filter      cell_{post type}_{column key}
     */
    public function cell_gg_bet_samplecolumn($sCell, $iPostID)
    {

        return sprintf(__('Post ID: %1$s', 'yc_tech-loader'), $iPostID) . "<br />"
            . __('Text', 'yc_tech-loader') . ': ' . get_post_meta($iPostID, 'metabox_text_field', true);
    }

    /**
     * Custom callback methods
     */

    /**
     * Modifies the way how the sample column is sorted. This makes it sorted by post ID.
     *
     * @see http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
     * @callback        filter      request
     */
    public function replyToSortCustomColumn($aVars)
    {

        if (isset($aVars['orderby']) && 'samplecolumn' == $aVars['orderby']) {
            $aVars = array_merge(
                $aVars,
                array(
                    'meta_key'  => 'metabox_text_field',
                    'orderby'   => 'meta_value',
                )
            );
        }
        return $aVars;
    }
}

new _Menu_Bet('gg_bet');
