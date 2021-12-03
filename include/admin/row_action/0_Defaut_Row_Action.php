<?php

/**
 * Row_Action
 */

namespace YC\Admin;

use YC_TECH;

if (ROW_ACTION_ENABLE) return;

defined('ABSPATH') || exit;



class _Row_Action extends YC_TECH
{

    public function __construct()
    {
        add_filter('post_row_actions', [$this, 'yc_posttype_row_actions'], 100);
        add_filter('page_row_actions', [$this, 'yc_posttype_row_actions'], 100);
        add_filter('user_row_actions', [$this, 'yc_user_row_actions'], 100);
    }
    public function yc_posttype_row_actions($actions)
    {
        unset($actions['edit']);
        unset($actions['inline hide-if-no-js']);

        //Live Canvas
        /*global $post;
        if (empty($actions['edit_page_with_lc'])) {
            $actions['edit_page_with_lc'] = '<a class="edit_page_with_lc" href="' . site_url() . '/?page_id=' . $post->ID . '&amp;lc_action_launch_editing=1">' . __('Edit with LiveCanvas', 'lc') . '</a>';
        }*/
        //var_dump($actions);

        return $actions;
    }

    public function yc_user_row_actions($actions)
    {
        if (\YC\Admin\Custom_Admin::$current_user_level >= 1) {
            unset($actions['resetpassword']);
            unset($actions['capabilities']);
        }
        return $actions;
    }
}
new _Row_Action();
