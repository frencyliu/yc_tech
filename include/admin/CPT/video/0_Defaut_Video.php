<?php

/**
 * Video post type
 */
namespace YC\Admin\CPT\Video;
use YC_TECH;

defined('ABSPATH') || exit;

if(!VIDEO_CPT) return;
class _Default extends YC_TECH
{

    public function __construct()
    {
        add_action('init', [$this, 'create_posttype'], 100);
        add_filter('pre_get_posts', [$this, 'custom_change_portfolio_posts_per_page'], 2000, 1);
    }

    function create_posttype()
    {

        register_post_type(
            'video',
            // CPT Options
            array(
                'labels' => array(
                    'name' => '影片中心',
                    'singular_name' => __('video')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'video'),
                'show_in_rest' => true,
                'menu_icon'   => 'dashicons-format-video',
                'supports'    => array('title', 'thumbnail'),

            )
        );
    }

    public function custom_change_portfolio_posts_per_page($query)
    {
        if ($query->is_post_type_archive('video') && !is_admin() && $query->is_main_query()) {
            $query->set('posts_per_page', '12');
        }
        return $query;
    }


}
new _Default();

require_once __DIR__ . '/APF.php';
