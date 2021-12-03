<?php
namespace YC\Admin;
use yc_AdminPageFramework;
require_once(YC_ROOT_DIR . 'library/APF/admin-page-framework.php');

if (!class_exists('yc_AdminPageFramework') || !WP_DEBUG) return;

class _Menu_Advance extends yc_AdminPageFramework
{
    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     */
    public function setUp()
    {

        // Create the root menu - specifies to which parent menu to add.
        $this->setRootMenuPage(__('進階','yc_tech'), 'dashicons-html');

        // Add the sub menus and the pages.
        $this->addSubMenuItems(
            array(
                'title'     => __('進階','yc_tech'),  // page and menu title
                'page_slug' => 'yc_advance'     // page slug
            )
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
     *
     * Notice that the name of the method is 'do_' + the page slug.
     * So the slug should not contain characters which cannot be used in function names such as dots and hyphens.
     */
    public function do_yc_advance()
    {

?>
        <h3>Action Hook</h3>
        <p>This is inserted by the 'do_' + page slug method.</p>
<?php

    }
}

new _Menu_Advance();
