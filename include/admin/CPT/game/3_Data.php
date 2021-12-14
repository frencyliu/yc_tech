<?php

/**
 *
 * 賽事系統
 * 資料處理
 */

namespace YC\Admin\CPT\Game;

use YC_TECH;

defined('ABSPATH') || exit;

class _Data extends YC_TECH
{
    public function __construct()
    {
        add_action('init', [$this, 'pass_game_data_to_js']);

        add_action('wp_enqueue_scripts', [$this, 'pass_data_scripts']);

        add_action( 'wp_ajax_foobar', [$this, 'my_ajax_foobar_handler'] );
        add_action( 'wp_ajax_nopriv_foobar', [$this, 'my_ajax_foobar_handler'] );

    }

    function my_ajax_foobar_handler() {
        // Make your response and echo it.
        var_dump($_POST['foobar_id']);
        // Don't forget to stop execution afterward.
        wp_die();
    }



    function pass_data_scripts()
    {
        /**
         * gg ajax requests.
         */
        wp_enqueue_script('gg-ajax', plugin_dir_url(__FILE__) . '/js/gg-ajax.js', array('jquery'), null, true);

        $posts = get_posts([
            'post_type' => 'gg_game',
        ]);

        wp_localize_script(
            'gg-ajax',
            'game_data_from_php',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'data_var_1' => 'value 1',
                'data_var_2' => $posts,
            )
        );
    }

    function pass_game_data_to_js()
    {
        // $_POST['game_id'] = 120; //var_dump($key); debug
        if (!isset($_POST['game_id'])) return;
        // Initialize cookie name
        $get_game_data = get_post($_POST['game_id'], 'ARRAY_A');
        foreach ($get_game_data as $key => $value) {

            $cookie_name = 'game_obj[' . $key . ']';
            setcookie($cookie_name, $value);
        }
        if (!isset($_POST['team_name'])) return;
        setcookie('team_name', $_POST['team_name']);
    }
}
new _Data();
