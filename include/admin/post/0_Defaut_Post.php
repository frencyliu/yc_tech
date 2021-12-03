<?php

/**
 * menu page
 */

namespace YC\Admin\post;

use YC_TECH;

defined('ABSPATH') || exit;

class _Post extends YC_TECH
{

    public function __construct()
    {
        add_action('admin_head', [$this, 'yc_remove_submit_status']);
    }
    public function yc_remove_submit_status()
    {
        //yc_remove_pending_PostStatus
?>
        <style>
            #submitdiv option[value="pending"],
            #submitdiv .misc-pub-section.misc-pub-revisions{
                display: none !important;
            }
        </style>
<?php

    }
}

new _Post();

require_once __DIR__ . '/1_LC_builder_modify.php';
