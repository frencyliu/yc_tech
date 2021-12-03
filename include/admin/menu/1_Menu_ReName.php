<?php

/**
 * menu order
 */

namespace YC\Admin;
use YC_TECH;

defined('ABSPATH') || exit;

class _Menu_Rename extends YC_TECH
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'yc_admin_menu_rename'], 98);
    }

    public function yc_admin_menu_rename()
    {
        global $menu; // Global to get menu array

        //global $submenu;
        //YC_TECH::DEBUG($submenu);


        foreach ($menu as $key => $menu_array) {

            switch ($menu_array[2]) {
                case 'edit.php':
                    $menu[$key][0] = __('Posts', 'YC_TECH');
                    break;
                case 'edit.php?post_type=page':
                    $menu[$key][0] = __('Pages', 'YC_TECH');
                    break;
                case 'edit.php?post_type=project':
                    $menu[$key][0] = __('Projects', 'YC_TECH');
                    break;
                case 'edit.php?post_type=dipl-testimonial':
                    $menu[$key][0] = __('Testimonial', 'YC_TECH');
                    break;
                case 'edit.php?post_type=dipl-team-member':
                    $menu[$key][0] = __('Team', 'YC_TECH');
                    break;
                case 'edit.php?post_type=product':
                    $menu[$key][0] = __('Products', 'YC_TECH');
                    break;
                case 'users.php':
                    $menu[$key][0] = __('Users', 'YC_TECH');
                    break;
                case 'wc-admin&path=/analytics/overview':
                    $menu[$key][0] = __('Analytics', 'YC_TECH');
                    break;
                case 'wps_overview_page':
                    $menu[$key][0] = __('Traffic', 'YC_TECH');
                    break;
                case 'loco':
                    $menu[$key][0] = __('Translate', 'YC_TECH');
                    break;
                case 'upload.php':
                    $menu[$key][0] = __('Uploads', 'YC_TECH');
                    break;


                default:
                    # code...
                    break;
            }
        }

        global $submenu;
        /*echo '<pre>';
        print_r($submenu['wpsc-tickets']);
        echo '</pre>';*/

        foreach ($submenu["users.php"] as $key => $submenu_array) {
            switch ($submenu_array[2]) {
                case 'import-export-menu-old':
                    $submenu["users.php"][$key][0] = __('Export User', 'YC_TECH');
                    break;

                default:
                    # code...
                    break;
            }
        }
        //var_dump($submenu["et_divi_options"]);




        /*echo '<pre>';
        var_dump($submenu["wpcf7"]);
        echo '</pre>';*/
    }

}
new _Menu_Rename();