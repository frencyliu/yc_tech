<?php

namespace YC\Admin\CPT\Game;

if (!class_exists('yc_AdminPageFramework_PostType')) return;

class _Menu_Game extends \yc_AdminPageFramework_PostType
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
                    'name'               => __('賽事列表', 'yc_tech'),
                    'singular_name'      => __('賽事', 'yc_tech'),
                    'add_new'            => __('新增賽事', 'yc_tech'),
                    'add_new_item'       => __('新增賽事', 'yc_tech'),
                    'edit_item'          => __('編輯賽事', 'yc_tech'),
                    'new_item'           => __('新賽事', 'yc_tech'),
                    'all_items'          => __('全部賽事', 'yc_tech'),
                    'view_item'          => __('查看賽事', 'yc_tech'),
                    'search_items'       => __('搜尋賽事', 'yc_tech'),
                    'not_found'          => __('目前還沒有賽事', 'yc_tech'),
                    'not_found_in_trash' => __('回收桶裡沒找到', 'yc_tech'),
                    'menu_name'          => __('賽事列表', 'yc_tech'),
                ),
                'supports'          => array('title', 'thumbnail', 'excerpt'), // e.g. array( 'title', 'editor', 'comments', 'thumbnail', 'excerpt' ),
                'public'            => true,
                'menu_icon'         => 'dashicons-xing',
                'show_in_rest'      => false,
                'has_archive'       => true,


                'menu_position'     => 120,
                'taxonomies'        => array(''),
                'show_admin_column' => true, // [3.5+ core] this is for custom taxonomies to automatically add the column in the listing table.


                // (framework specific) [3.5.10+] default: true
                'show_submenu_add_new'  => true,
                // (framework specific) [3.7.4+]
                'submenu_order_manage' => 5,   // default 5
                'submenu_order_addnew' => 9,   // default 10
            )
        );

        $this->addTaxonomy(
            'gg_game_type',  // taxonomy slug
            array(                  // argument - for the argument array keys, refer to : http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments
                'labels'                => array(
                    'name'          => __('賽事種類', 'yc_tech'),
                    'add_new_item'  => __('新增賽事種類', 'yc_tech'),
                    'new_item_name' => __('新的賽事種類', 'yc_tech')
                ),
                'description'           => '因為不同的賽事種類可能會有不同的勝負方式與賠率計算方式，未來再新增',
                //'meta_box_cb'           => true,
                'show_in_rest'          => false,
                'show_ui'               => true,
                'show_tagcloud'         => false,
                'hierarchical'          => true,
                'show_admin_column'     => true,
                'show_in_nav_menus'     => true,
                'show_table_filter'     => true,    // framework specific key
                'show_in_sidebar_menus' => true,    // framework specific key
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
        $_aSavedOptions = get_option('_Menu_Game');

        $form = '<form><input type="text" name="bet"><button class="btn btn-primary" type="submit">下注</button></form>';

        $user_point = '可下注虛擬幣：' . do_shortcode( '[gamipress_user_points]' );


        return "<h3>" . __('Saved Meta Field Values of the Post', 'admin-page-framework-loader') . "</h3>"
            . $this->oDebug->get($_aPostData)
            . "<h3>" . __('Saved Setting Options of The Loader Plugin', 'admin-page-framework-loader') . "</h3>"
            . $this->oDebug->get($_aSavedOptions). $user_point . $form;
    }

    public function columns_gg_game($aHeaderColumns)
    {

        return array_merge(
            $aHeaderColumns,
            array(
                'cb'                => '<input type="checkbox" />', // Checkbox for bulk actions.
                'title'             => __('Title', 'admin-page-framework'), // Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
                'author'            => __('Author', 'admin-page-framework'), // Post author.
                // 'categories'     => __( 'Categories', 'admin-page-framework' ), // Categories the post belongs to.
                // 'tags' => __( 'Tags', 'admin-page-framework' ), // Tags for the post.
                'comments'          => '<div class="comment-grey-bubble"></div>', // Number of pending comments.
                'date'              => __('Date', 'admin-page-framework'),     // The date and publish status of the post.
                'samplecolumn'      => __('Sample Column'),
            )
        );
    }

    /**
     *
     * @callback        filter      sortable_columns_{post type slug}
     */
    public function sortable_columns_gg_game($aSortableHeaderColumns)
    {
        return $aSortableHeaderColumns + array(
            'samplecolumn' => 'samplecolumn',
        );
    }

    /**
     *
     * @callback        filter      cell_{post type}_{column key}
     */
    public function cell_gg_game_samplecolumn($sCell, $iPostID)
    {

        return sprintf(__('Post ID: %1$s', 'admin-page-framework-loader'), $iPostID) . "<br />"
            . __('Text', 'admin-page-framework-loader') . ': ' . get_post_meta($iPostID, 'metabox_text_field', true);
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

new _Menu_Game('gg_game');
