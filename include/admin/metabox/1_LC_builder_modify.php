<?php

/**
 * 1_LC_builder_modify
 */

namespace YC\Admin\metabox;

use YC_TECH;

defined('ABSPATH') || exit;

class _LC extends YC_TECH
{

    public function __construct()
    {
        add_action( 'init', [$this, 'yc_remove_page_support'], 100);
    }

    public function yc_remove_page_support(){
        remove_post_type_support('page', 'editor');
    }

}

new _LC();
