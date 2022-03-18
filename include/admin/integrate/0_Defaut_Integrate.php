<?php

/**
 * customise admin
 */

namespace YC\Admin\Integrate;

use YC_TECH;

defined('ABSPATH') || exit;

//if (!class_exists('Bogo_POMO', false)) return;

class _Default extends YC_TECH
{

    public function __construct()
    {
        add_action('init', [$this, 'change_WPC_fly_cart_color'], 99);
    }


    public function change_WPC_fly_cart_color(){
        $woofc_color      = get_option( '_woofc_color' );
        if($woofc_color == '#cc6055'){
            $woofc_color = 'var(--mdb-primary, #cc6055)';
            update_option( '_woofc_color', $woofc_color  );
        }
    }

}
new _Default();