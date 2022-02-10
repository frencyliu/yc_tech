<?php

/**
 * customise admin
 */

namespace YC\Admin;

defined('ABSPATH') || exit;

class _Notice
{


    public function __construct()
    {
        /* admin notice
         * notice-error – error message displayed with a red border
         * notice-warning – warning message displayed with a yellow border
         * notice-success – success message displayed with a green border
         * notice-info – info message displayed with a blue border
        */
        add_action('admin_notices', [$this, 'yc_admin_notice'], 2);
    }

    function yc_admin_notice()
    {
?>
<!--
        <div class="notice jaio-notice notice-warning is-dismissible">
            <p>This is an example of a notice that appears on the settings page.</p>
        </div>
        -->
<?php
    }
}

new _Notice();
