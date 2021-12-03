<?php

/**
 *
 *
 * //註冊需改寫
 */

namespace YC\Sync;

use YC_TECH;

defined('ABSPATH') || exit;


class Page extends YC_TECH
{


    public function __construct()
    {
        add_action('admin_head', [$this, 'yc_create_default_page']);
        add_action('admin_head', [$this, 'yc_hide_post']);
        add_action('init', [$this, 'yc_add_shortcode']);
    }

    function yc_create_default_page()
    {
        //auto create register page
        if (class_exists('TheChampLoginWidget', false)) {
            $register_page_exist = post_exists('Register', '', '', 'page');
            if ($register_page_exist == 0) {
                $postarr = [
                    'post_content'  => '[wc_reg_form_bbloomer]',
                    'post_title'    => 'Register',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                ];
                //新增文章
                $r_post_id = wp_insert_post($postarr);
                //新增預設LAYOUT
                update_post_meta($r_post_id, '_et_pb_page_layout', 'et_no_sidebar');
            }
        }
    }
    function yc_add_shortcode()
    {
        add_shortcode('wc_reg_form_bbloomer', [$this, 'yc_separate_registration_form']);
    }


    function yc_separate_registration_form()
    {
        ob_start();
        /*
         * custum register field
         * https://www.cloudways.com/blog/add-woocommerce-registration-form-fields/
         */
        if (is_admin() || is_user_logged_in()) {
            wp_redirect(site_url());
            return;
        }


        // NOTE: THE FOLLOWING <FORM></FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php
        // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY

?>
        <div class="yc_login_form_wrap">
            <div class="yc_login_form">
                <?php
                do_action('woocommerce_before_customer_login_form');

                ?>

                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
                    <h2 class="yc_login_form_title">註冊</h2>

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?> <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                                            ?>
                        </p>

                    <?php endif; ?>



                    <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?> <span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                        </p>

                    <?php else : ?>

                        <p><?php esc_html_e('A password will be sent to your email address.', 'woocommerce'); ?></p>

                    <?php endif; ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?> <span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_billing_first_name"><?php esc_html_e('姓名', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_first_name" id="reg_billing_first_name" autocomplete="billing_first_name" value="<?php echo (!empty($_POST['billing_first_name'])) ? esc_attr(wp_unslash($_POST['billing_first_name'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_gender"><?php esc_html_e('性別', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="radio" id="male" name="male" value="male"> 男
                        <input type="radio" id="female" name="female" value="female"> 女
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_billing_phone"><?php esc_html_e('手機', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" autocomplete="billing_phone" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_birthday"><?php esc_html_e('生日', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="birthday" id="reg_birthday" autocomplete="birthday" value="<?php echo (!empty($_POST['birthday'])) ? esc_attr(wp_unslash($_POST['birthday'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_billing_address_1"><?php esc_html_e('地址', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" id="reg_billing_address_1" autocomplete="billing_address_1" value="<?php echo (!empty($_POST['billing_address_1'])) ? esc_attr(wp_unslash($_POST['billing_address_1'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_how_to_know_us"><?php esc_html_e('如何得知我們', 'YC_TECH'); ?> <span class="required">*</span></label>

                        <select class="woocommerce-Input woocommerce-Input--select input-select" name="how_to_know_us" id="reg_how_to_know_us" autocomplete="how_to_know_us" value="<?php echo (!empty($_POST['how_to_know_us'])) ? esc_attr(wp_unslash($_POST['how_to_know_us'])) : ''; ?>">
                            <option>-</option>
                            <option value="rakuten">樂天市場</option>
                            <option value="momo">momo購物</option>
                            <option value="pchome">PCHOME</option>
                            <option value="shopee">蝦皮</option>
                            <option value="yahoo_buy">Yahoo購物中心</option>
                            <option value="yahoo_mall">Yahoo超級商城</option>
                            <option value="looking">Looking錄得清</option>
                            <option value="etmall">東森/森森購物</option>
                            <option value="friday">FRIDAY</option>
                            <option value="udn">UDN買東西</option>
                            <option value="yahoo_auction">Yahoo拍賣</option>
                            <option value="ruten">露天拍賣</option>
                            <option value="moto_vendor">機車行/汽車量販店</option>
                            <option value="other">其他</option>
                        </select>
                    </p>

                    <?php do_action('woocommerce_register_form'); ?>

                    <p class="woocommerce-FormRow form-row">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button btn btn-primary" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                    </p>



                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
            </div>
        </div>

<?php

        return ob_get_clean();
    }


    //防止客人修改註冊頁
    function yc_hide_post()
    {
        $register_page_exist = post_exists('Register', '', '', 'page');
        if ($register_page_exist == 0 || self::$current_user_level == 0) return;

        $css = '';
        $css .= '<style>';
        $css .= 'tr#post-' . $register_page_exist . '{';
        $css .=     'display: none !important;';
        $css .= '}';
        $css .= '</style>';

        echo $css;
    }
}
