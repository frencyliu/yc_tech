<?php

/**
 *
 */

namespace YC\FrontEnd;

use YC_TECH;

defined('ABSPATH') || exit;


class _Default extends YC_TECH
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'yc_enqueue_front_css'], 100);
        add_action('wp_enqueue_scripts', [$this, 'yc_enqueue_front_js'], 100);

    }

    public function yc_enqueue_front_css()
    {
        wp_enqueue_style('YC_TECH front css', YC_ROOT_URL . '/assets/css/yc_front_level_' . self::$current_user_level . '.css', array(), '1.0.1');

        if (FA_ENABLE) {
            wp_enqueue_style('fontawesome_css', YC_ROOT_URL . '/assets/fontawesome/css/all.min.css');
        }
        if (FLIPSTER_ENABLE) {
            wp_enqueue_style('flipster_css', YC_ROOT_URL . '/assets/flipster/jquery.flipster.min.css');
        }
        if (SLICK_ENABLE) {
            wp_enqueue_style('slick-theme_css', YC_ROOT_URL . '/assets/slick/slick-theme.css');
            wp_enqueue_style('slick_css', YC_ROOT_URL . '/assets/slick/slick.css');
        }

        if (ANIMATE_CSS_ENABLE) {
            wp_enqueue_style('animate_css', YC_ROOT_URL . '/assets/animate-css/animate.min.css');
        }
    }

    public function yc_enqueue_front_js()
    {
        //Lottie
        wp_enqueue_script('Lottie js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js');


        wp_enqueue_script('YC_TECH front js', YC_ROOT_URL . '/assets/js/yc_front.js');
        if (FA_ENABLE) {
            wp_enqueue_script('fontawesome_js',  YC_ROOT_URL . '/assets/fontawesome/js/all.min.js');
        }
        if (FLIPSTER_ENABLE) {
            wp_enqueue_script('flipster_js', YC_ROOT_URL . '/assets/flipster/jquery.flipster.min.js');
        }
        if (SLICK_ENABLE) {
            wp_enqueue_script('slick_js', YC_ROOT_URL . '/assets/slick/slick.min.js');
        }
        if (ANIMATE_JS_ENABLE) {
            wp_enqueue_script('animate_js', YC_ROOT_URL . '/assets/anime-master/anime.min.js');
        }
        if (THREE_JS_ENABLE) {
            wp_enqueue_script('three_js', YC_ROOT_URL . '/assets/threejs/three.js');
        }


    }


}
new _Default();
