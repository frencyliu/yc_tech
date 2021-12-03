<?php

/**
 * 1_LC_builder_modify
 */

namespace YC\Admin\post;

use YC_TECH;

defined('ABSPATH') || exit;

class _LC extends YC_TECH
{

    public function __construct()
    {
        add_action('admin_head',  [$this, 'yc_remove_add_page_btn'], 100);
        add_action('admin_head',  [$this, 'LC_support'], 100);
        add_action('admin_footer', [$this, 'yc_create_LC_btn'], 100);
        add_action('save_post_page', [$this, 'yc_set_LC_enable_to_posttype'], 100, 3);



    }

    public function LC_support(){
        if(get_current_screen()->id == 'page'){
            add_action('admin_footer', 'lc_template_admin_notice_using_lc', 200 );
        }
    }

    public function yc_remove_add_page_btn()
    {
        if (get_current_screen()->id == 'edit-page') :
?>
            <style>
                #wpbody>#wpbody-content>.wrap>a.page-title-action:not(.yc-show) {
                    display: none !important;
                }
            </style>
        <?php
        endif;
    }

    public function yc_set_LC_enable_to_posttype($post_ID)
    {
        global $post;
        if (is_null($post)) {
            update_post_meta($post_ID, '_lc_livecanvas_enabled', '1', '');
        } else {
            if ($post->post_status != "publish") { //新發布才執行
                update_post_meta($post_ID, '_lc_livecanvas_enabled', '1', '');
            }
        }
    }

    public function yc_create_LC_btn()
    {
        /**
         * Live Canvas
         */
        ?>
        <script>
            jQuery(document).ready(($) => {
                <?php
                //YC_TECH::DEBUG(get_current_screen()->id);
                $screen = get_current_screen()->id;
                switch ($screen) {
                    case 'edit-page':
                        echo $this->yc_create_LC_btn_script();
                        break;
                    default:
                        # code...
                        break;
                }



                ?>
            });
        </script>
    <?php
    }

    public function yc_create_LC_btn_script()
    {
        $html = '';
        ob_start();

        /*

        */

        //no guten

    ?>
    let create = '<a href="' + SITE_URL + '/wp-admin/?lc_action_new_page=1" class="page-title-action yc-show">新增Live Canvas頁面</a>';
        jQuery("body.post-type-page:not(.post-php) .wrap .page-title-action").after(create);

    <?php
        $html .= ob_get_clean();
        return $html;
    }
}

new _LC();
