<?php

/**
 * customise admin
 */

namespace YC\Admin;
use YC_TECH;

defined('ABSPATH') || exit;

class Custom_Admin extends YC_TECH
{

    public function __construct()
    {




        //---- 註冊設定 ----//
        //add_action('admin_init', [$this, 'yc_sync_data']);

        //---- user ----//
        add_filter('pre_option_default_role', [$this, 'yc_change_default_role'], 100);
        //---- Admin ----//
        remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');




        //add_filter('menu_order', array($this, 'yc_submenu_order'), 10099);

        //---- admin_bar_menu ----//
        add_action('wp_before_admin_bar_render', [$this, 'yc_admin_bar_render'], 100);

        //---- Login page ----//
        add_action('login_head', [$this, 'yc_change_login_logo'], 100);

        //---- disable gutenberg ----//
        add_filter('use_block_editor_for_post', '__return_false');

        //---- Profile page ----//
        add_filter('user_contactmethods', [$this, 'yc_profile_fields'], 100);

        //---- Meta Box ----//
        add_action('wp_dashboard_setup', [$this, 'yc_remove_dashboard_widgets']);

        //---- tool bar ----//
        add_action('admin_bar_menu', [$this, 'yc_toolbar'], 99);



        //add Social login at woocommerce register form
        //woocommerce_login_form_end | woocommerce_login_form_start
        add_action('woocommerce_login_form_end', [$this, 'yc_add_register_form_field'], 100);

        //change favicon
        add_action('wp_head', [$this, 'yc_add_wp_head']);
        add_action('admin_head', [$this, 'yc_add_wp_head']);


        //remove hook
        add_action('admin_init', [$this, 'yc_remove_filters'], 100);

        //add admin footer
        add_action('admin_footer', [$this, 'yc_add_admin_footer']);

        add_action('init', [$this, 'remove_admin_bar']);

        add_filter('bogo_localizable_post_types', [$this, 'yc_bogo_support_for_custom_post_types'], 10, 1);


        //redirect when user access wp-admin/
        add_action('admin_init', [$this, 'yc_set_admin_redirect']);

        //Disable comment
        add_action('init', [$this, 'yc_disable_comments_admin_bar']);
        add_action('admin_init', [$this, 'yc_disable_comments_post_types_support']);
        add_filter('comments_open', [$this, 'yc_disable_comments_status'], 20, 2);
        add_filter('pings_open', [$this, 'yc_disable_comments_status'], 20, 2);
        add_filter('comments_array', [$this, 'yc_disable_comments_hide_existing_comments'], 10, 2);


        //圖片  移除所有圖片尺寸
        add_action('init', [$this, 'yc_remove_all_image_sizes']);
        add_filter('big_image_size_threshold', function () {
            return 20000;
        });
        add_filter('jpeg_quality', function () {
            return 100;
        });


        //自訂後台標題
        add_filter('admin_title', [$this, 'yc_admin_title'], 99, 2);

        //CHATBUTTON 前端顯示
        add_action('wp_footer', [$this, 'yc_add_chatbutton_frontend']);
    }




    /**
     * Support custom post type with bogo.
     * @param array $ locallyizable Supported post types.
     */
    public function yc_bogo_support_for_custom_post_types($localizable)
    {
        if (class_exists('Bogo_POMO', false)) {
            $args = array(
                'public' => true,
                '_builtin' => false,
            );
            $custom_post_types = get_post_types($args);
            return array_merge($localizable, $custom_post_types);
        }
    }




    public function yc_add_chatbutton_frontend()
    {
        $fb_chat_plugin_activate = is_plugin_active("facebook-messenger-customer-chat/facebook-messenger-customer-chat.php");
        $class = ($fb_chat_plugin_activate) ? 'yc_chatbutton' : 'yc_chatbutton_no_fb';

        $chatbutton_order = ['yc_chatbutton_phone', 'yc_chatbutton_email', 'yc_chatbutton_whatsapp', 'yc_chatbutton_ig', 'yc_chatbutton_tg', 'yc_chatbutton_line'];

        $html_btn = '';
        foreach ($chatbutton_order as $button) {
            if (!empty(get_option($button))) {
                switch ($button) {
                    case 'yc_chatbutton_email':
                        $prefix = 'mailto:';
                        break;
                    case 'yc_chatbutton_phone':
                        $prefix = 'tel://';
                        break;
                    default:
                        $prefix = '';
                        break;
                }
                $html_btn .= '<a href="' . $prefix . get_option($button) . '" class="' . $button . '" target="_blank"></a>';
            }
        }
        if (empty($html_btn)) return;

        $html = '';
        $html .= '<div class="' . $class . '">';

        $html .= '<div class="chatbutton_content">';

        /*$html .= '<i class="fas fa-arrow-from-right"></i>';
        $html .= '<i class="fas fa-arrow-from-left"></i>';*/
        $html .= '<i class="fad fa-reply-all"></i>';
        $html .= '<i class="fad fa-share-all"></i>';
        $html .= '<div class="chatbutton_content_inner">';
        $html .= '<div class="chatbutton_content_inner_scroll">';
        $html .= $html_btn;
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        echo $html;
    }

    public function yc_sync_data()
    {

        //LOGO
        $admin2020_options = get_option('admin2020_settings');
        $yc_site_logo = get_option('yc_site_logo');
        $yc_login_bg = get_option('yc_login_bg');

        //Social Login
        $the_champ_login = get_option('the_champ_login');

        $the_champ_login['enable'] = (@get_option('yc_sociallogin_enable')[1] == 1) ? true : false;

        $the_champ_login['fb_key'] = get_option('yc_facebook_app');
        $the_champ_login['fb_secret'] = get_option('yc_facebook_secret');
        $the_champ_login['google_key'] = get_option('yc_google_app');
        $the_champ_login['google_secret'] = get_option('yc_google_secret');
        $the_champ_login['line_channel_id'] = get_option('yc_line_app');
        $the_champ_login['line_channel_secret'] = get_option('yc_line_secret');


        if (!empty($the_champ_login['fb_key']) && !empty($the_champ_login['fb_secret'])) {
            $providers[] = 'facebook';
        }
        if (!empty($the_champ_login['google_key']) && !empty($the_champ_login['google_secret'])) {
            $providers[] = 'google';
        }
        if (!empty($the_champ_login['line_channel_id']) && !empty($the_champ_login['line_channel_secret'])) {
            $providers[] = 'line';
        } else {
            $providers = [];
        }
        $the_champ_login['providers'] = $providers;

        //update_option('the_champ_login', $the_champ_login);

        //site logo
        $admin2020_options['modules']['admin2020_admin_bar']['light-logo'] = wp_get_attachment_image_url($yc_site_logo, 'large');

        $admin2020_options['modules']['admin2020_admin_login']['login-background'] = wp_get_attachment_image_url($yc_login_bg, 'full');

        if (isset($_POST['submit'])) {

            update_option('admin2020_settings', $admin2020_options);
            //social login
            update_option('the_champ_login', $the_champ_login);
        } else {
            if ($admin2020_options['modules']['admin2020_admin_bar']['light-logo'] == wp_get_attachment_image_url($yc_site_logo, 'large') && $admin2020_options['modules']['admin2020_admin_login']['login-background'] == wp_get_attachment_image_url($yc_login_bg, 'full')) {
                // do nothing
            } else {
                update_option('admin2020_settings', $admin2020_options);
            }
        }
    }



    public function remove_admin_bar()
    {
        // level 0跟1可以看到menu bar
        //if (self::$current_user_level > 1) {
        //show_admin_bar(false);
        //}
    }


    public function yc_add_admin_footer()
    {
        global $pagenow;
        if($pagenow == 'admin.php' && $_GET['page'] == 'wc-order-export'){
            ?>
            <script>
                jQuery('#my-date-filter').ready(function(){
                    let today = new Date();
                let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                jQuery('#from_date').val(date);
                jQuery('#to_date').val(date);
                });
            </script>
            <?php
        }
    }

    public function yc_remove_filters()
    {
        global $wp_filter;

        /*
   * 解掉export order套件 select2.js的bug，詳情可以看
   * 位置：plugins\woo-order-export-lite\classes\class-wc-order-export-admin.php
   * add_filter( 'script_loader_src', array( $this, 'script_loader_src' ), 100, 2 );
   */
        //unset($wp_filter['script_loader_src']->callbacks[100]);

        /*
   * 移除掉我以外的所有通知(priority 2)
   * priority 10全部屏蔽  100是admin2020
   */
        if (self::$current_user_level > 0) {
            unset($wp_filter['admin_notices']->callbacks[10]);
            unset($wp_filter['admin_title']->callbacks[10]); //WC title
            unset($wp_filter['admin_notices']->callbacks[20]); //wp-statistic
        }

        /*debug*/
        /*echo '<pre>';
  var_dump($wp_filter['admin_title']);
  echo '</pre>';*/
        /*debug*/
    }

    public function yc_add_wp_head()
    {
?>
        <?php if (!empty(get_option('yc_ga_track'))) : ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo get_option('yc_ga_track'); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '<?php echo get_option('yc_ga_track'); ?>');
            </script>
        <?php endif; ?>
        <?php if (!empty(get_option('yc_fb_track'))) : ?>
            <!-- Facebook Pixel Code -->
            <script>
                ! function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '<?php echo get_option('yc_ga_track'); ?>');
                fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo get_option('yc_ga_track'); ?>&ev=PageView&noscript=1" /></noscript>
            <!-- End Facebook Pixel Code -->
        <?php endif; ?>
        <link rel="shortcut icon" href="<?php echo wp_get_attachment_image_url(get_option('yc_favicon'), 100); ?>">
        <script>
            let SITE_URL = "<?php echo site_url(); ?>";
        </script>


        <?php
    }


    public function yc_add_register_form_field()
    {
        //新增欄位
        /*woocommerce_form_field(
  'country_to_visit',
  array(
  'type'        => 'text',
  'required'    => true, // just adds an "*"
  'label'       => 'Country you want to visit the most'
  ),
  ( isset($_POST['country_to_visit']) ? $_POST['country_to_visit'] : '' )
  );*/
        echo do_shortcode('[TheChamp-Login title="用社群帳號登入"]');
    }


    function yc_set_admin_redirect()
    {
        if (class_exists('WP_Statistics', false)) {
            if ( strpos($_SERVER['PHP_SELF'],'wp-admin/index.php') !== false && $_SERVER['QUERY_STRING'] == '') {
                $redirect_to = admin_url() . 'admin.php?page=wps_overview_page';
                wp_redirect($redirect_to);
                exit;
            }
        }
    }

    function yc_toolbar($admin_bar)
    {
        /**
         * https://developer.wordpress.org/reference/classes/wp_admin_bar/add_node/
         */



        $args = array(
            'id'    => 'sitetool',
            'title' => '站長工具',
            'meta'  => array(
                'class' => 'uk-background-muted uk-border-rounded '
            ),
        );
        $admin_bar->add_node($args);

        $args = array(
            'parent' => 'sitetool',
            'id'     => 'google-analytics',
            'title'  => 'Google Analytics',
            'href'   => 'https://analytics.google.com/',
            'meta'   => array(
                'target' => '_blank'
            ),
        );
        $admin_bar->add_node($args);

        $args = array(
            'parent' => 'sitetool',
            'id'     => 'google-console',
            'title'  => 'Google Console',
            'href'   => 'https://accounts.google.com/ServiceLogin?service=sitemaps&hl=zh-TW&continue=https://search.google.com/search-console?hl%3Dzh-tw%26utm_source%3Dabout-page',
            'meta'   => array(
                'target' => '_blank'
            ),
        );
        $admin_bar->add_node($args);

        if (class_exists('WooCommerce', false)) {
            $args = array(
                'parent' => 'sitetool',
                'id'     => 'ecpay',
                'title'  => '綠界科技',
                'href'   => 'https://www.ecpay.com.tw/',
                'meta'   => array(
                    'target' => '_blank'
                ),
            );
            $admin_bar->add_node($args);
        }


        /*$wp_admin_bar->add_node([
            'id'      => 'line',
            'title'   => 'Google Analytics',
            'parent'  => '',
            'href'    => esc_url( admin_url( 'admin.php?page=googlesitekit-splash' ) ),
            'group'   => false,
            'meta'    => [
            'class' => 'yc_toolbar_btn'
            ],
            ]);*/
    }



    public function yc_remove_dashboard_widgets()
    {

        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
        // Remove comments metabox from dashboard
        if (!COMMENTS_ENABLE) {
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        }
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // Incoming Links
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Press
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // Recent Drafts
        remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress blog
        remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Other WordPress News
        remove_meta_box('themefusion-news', 'dashboard', 'normal');
        remove_meta_box('wordfence_activity_report_widget', 'dashboard', 'normal');

        // use 'dashboard-network' as the second parameter to remove widgets from a network dashboard.
    }

    public function yc_profile_fields($contact_methods)
    {
        // Unset fields you don’t need
        unset($contact_methods['author_email']);
        unset($contact_methods['author_google']);
        unset($contact_methods['author_twitter']);
        unset($contact_methods['author_linkedin']);
        unset($contact_methods['author_dribble']);
        unset($contact_methods['author_whatsapp']);
        unset($contact_methods['author_custom']);

        return $contact_methods;
    }

    public function yc_change_login_logo()
    {
        $logo_style = '<style type="text/css">';
        $logo_style .= '#backtoblog {display:none !important;}';
        $logo_style .= '#nav {margin-bottom:51px !important;}';
        $logo_style .= '</style>';
        echo $logo_style;
    }

    //修改用戶的註冊預設身分
    public function yc_change_default_role($default_role)
    {
        return 'customer';
        //return $default_role;
    }





    public function yc_admin_bar_render()
    {
        global $wp_admin_bar;

        if (!COMMENTS_ENABLE) {
            $wp_admin_bar->remove_menu('comments');
        }

        $wp_admin_bar->remove_menu('updates');
        $wp_admin_bar->remove_menu('feedback');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');
        $wp_admin_bar->remove_menu('documentation');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('wp-logo');
        //$wp_admin_bar->remove_menu('new-content');
        $wp_admin_bar->remove_menu('fb-edit');



    }

    function yc_remove_all_image_sizes()
    {
        foreach (get_intermediate_image_sizes() as $size) {
            remove_image_size($size);
        }
    }

    function yc_admin_title($admin_title, $title)
    {
        return $title;
    }

    public function yc_disable_comments_post_types_support()
    {
        if (!COMMENTS_ENABLE) {
            // Disable support for comments and trackbacks in post types
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }

            // Redirect any user trying to access comments page
            global $pagenow;
            if ($pagenow === 'edit-comments.php') {
                wp_redirect(admin_url());
                exit;
            }
        }
    }



    // Close comments on the front-end
    public function yc_disable_comments_status()
    {
        return COMMENTS_ENABLE;
    }

    // Hide existing comments
    public function yc_disable_comments_hide_existing_comments($comments)
    {
        if (!COMMENTS_ENABLE) {
            $comments = array();
            return $comments;
        }
    }

    // Remove comments links from admin bar
    public function yc_disable_comments_admin_bar()
    {
        if (!COMMENTS_ENABLE) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }
}

new Custom_Admin();