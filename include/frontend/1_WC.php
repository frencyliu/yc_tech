<?php

/**
 *
 */

namespace YC\FrontEnd;

use YC_TECH;

defined('ABSPATH') || exit;


class _WC extends YC_TECH
{

    public function __construct()
    {
        //add_action('wp_footer', [$this, 'yc_login_logic'], 100);

        add_filter( 'body_class', function( $classes ) {
            if (is_user_logged_in()) return $classes;
            return array_merge( $classes, array( 'unlogin' ) );
        },100 );
    }

    static public function yc_login_logic()
    {



            if (!is_user_logged_in()) {

                //如果未登入
                //就顯示登入頁面
                //get_header();
                $html = '';
                ob_start();
                ?>
<div class="yc-login-popup">
<div class="woocommerce">
    <?php wc_get_template('myaccount/form-login.php'); ?>
</div>
</div>
                <?php

                $html .= ob_get_clean();


                echo $html;
                //get_footer();
                //exit();
            }


    }

}
new _WC();
