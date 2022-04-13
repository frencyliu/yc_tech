<?php

/**
 * Custumizer
 */

add_action('init', 'chatbutton_init', 99);
add_action('customize_register', 'yc_basic_setting_action', 99);
add_action('customize_preview_init', 'yc_customizer', 99);


function chatbutton_init()
{
    //檢查套件是否有開，模組化
    $messenger_activate = get_theme_mod('enable_fb_plugin');
    if ($messenger_activate) {
        activate_plugin("facebook-messenger-customer-chat/facebook-messenger-customer-chat.php");
    } else {
        if(class_exists('Facebook_Messenger_Customer_Chat', false)){
        deactivate_plugins("facebook-messenger-customer-chat/facebook-messenger-customer-chat.php");
    }
    }
}


function yc_basic_setting_action($wp_customize)
{

    /**
     * 登入頁背景
     * Display Image using customizer image control
     * <img src="<?php echo get_theme_mod('diwp_logo'); ?>" />
     */
    $wp_customize->add_setting('yc_login_bg_img', array(
        'default' => 'https://images.unsplash.com/photo-1585409677983-0f6c41ca9c3b?ixlib=rb-1.2.1&q=80&fm=jpg', // Add Default Image URL
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'yc_login_bg_img_control', array(
        'label' => '登入頁背景圖片',
        'priority' => 1000,
        'section' => 'title_tagline',
        'settings' => 'yc_login_bg_img',
        'button_labels' => array( // All These labels are optional
            'select' => 'Select Logo',
            'remove' => 'Remove Logo',
            'change' => 'Change Logo',
        )
    )));

    /**
     * 置頂文字
     */

    $wp_customize->add_setting('yc_fixedtop_text', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_fixedtop_text', array(
        'label'        => '輸入置頂文字',
        'description'  => '也可以輸入html，例如&lt;a&gt; tag',
        'section'      => 'title_tagline',
        'settings'     => 'yc_fixedtop_text',
        'type'         => 'text',
        'priority'     => 1010,
        'input_attrs' => array(
            'placeholder' => '例如：全館滿999免運費',
        )
    ));

    $wp_customize->add_setting('enable_countdown', array(
        'default'     => 'no',
    ));
    $wp_customize->add_control('enable_countdown', array(
        'label'        => '啟用倒數計時',
        'section'      => 'title_tagline',
        'settings'     => 'enable_countdown',
        'type'         => 'radio',
        'priority'     => 1020,
        'choices' => array(
            'no' => '關閉倒數計時',
            'yes' => '啟用倒數計時 (到期後消失)',
            'infinity' => '啟用倒數計時 (到期後重新計算)',
          ),
    ));

    $wp_customize->add_setting('yc_fixedtop_countdown', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_fixedtop_countdown', array(
        'label'        => '輸入到期日',
        'section'      => 'title_tagline',
        'settings'     => 'yc_fixedtop_countdown',
        'type'         => 'date',
        'priority'     => 1030,
        'input_attrs' => array(
            'placeholder' => '例如：全館滿999免運費',
        ),
        'active_callback' => 'nd_dosth_hide_readmore_on_condition',
    ));

    $wp_customize->add_setting('yc_fixedtop_countdown_infinity', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_fixedtop_countdown_infinity', array(
        'label'        => '輸入無限倒數的秒數',
        'description'  => '1天 = 60秒 * 60分 * 24小時 = 86400',
        'section'      => 'title_tagline',
        'settings'     => 'yc_fixedtop_countdown_infinity',
        'type'         => 'number',
        'priority'     => 1030,
        'input_attrs' => array(
            'placeholder' => '例如：86400',
        ),
        'active_callback' => 'nd_dosth_hide_readmore_on_condition_infinity',
    ));

    $wp_customize->add_setting('yc_beforecountdown_text', array(
        'default'     => '限時搶購',
    ));
    $wp_customize->add_control('yc_beforecountdown_text', array(
        'label'        => '輸入倒數計時前方文字',
        'section'      => 'title_tagline',
        'settings'     => 'yc_beforecountdown_text',
        'type'         => 'text',
        'priority'     => 1040,
        'active_callback' => 'nd_dosth_hide_readmore_on_condition_text',
    ));

    $wp_customize->add_setting('yc_aftercountdown_text', array(
        'default'     => '要買要快',
    ));
    $wp_customize->add_control('yc_aftercountdown_text', array(
        'label'        => '輸入倒數計時後方文字',
        'section'      => 'title_tagline',
        'settings'     => 'yc_aftercountdown_text',
        'type'         => 'text',
        'priority'     => 1050,
        'active_callback' => 'nd_dosth_hide_readmore_on_condition_text',
    ));

    /**
     * 聊天按鈕
     */

    $wp_customize->add_section('yc_chatbutton', array(
        'title'      => '聊天按鈕',
        'priority'   => 100,
        'description' => '會出現在螢幕右下角',
    ));

    //檢查FB套件有沒有開
    $fb_chat_plugin_activate = is_plugin_active("facebook-messenger-customer-chat/facebook-messenger-customer-chat.php");
    $html = '<br><a href="https://www.facebook.com/login.php?next=https%3A%2F%2Fwww.facebook.com%2Fcustomer_chat%2Fdialog%2F%3Fdomain%3Dhttps://www.lookingtaiwan.com" target="_blank" class="button-primary">編輯 Facebook Messenger</a>';
    //if (class_exists('Facebook_Messenger_Customer_Chat', false)) {
        $wp_customize->add_setting('enable_fb_plugin', array(
            'default'     => '',
        ));
        $wp_customize->add_control('enable_fb_plugin', array(
            'label'        => '啟用 FB 即時聊天',
            'description'  => '用戶可在網頁直接傳送訊息' . $html,
            'section'      => 'yc_chatbutton',
            'settings'     => 'enable_fb_plugin',
            'type'         => 'checkbox',
        ));
    //}

    $wp_customize->add_setting('yc_chatbutton_line', array(
        'default'      => '',
    ));
    $wp_customize->add_control('yc_chatbutton_line', array(
        'label'        => '輸入 LINE 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.pkstep.com/archives/5261" target="_blank">如何產生 LINE 連結</a>',
        'section'      => 'yc_chatbutton',
        'settings'     => 'yc_chatbutton_line',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://page.line.me/https://page.line.me/xxxxxxxx',
        ),
    ));

    $wp_customize->add_setting('yc_chatbutton_tg', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_chatbutton_tg', array(
        'label'        => '輸入 Telegram 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.inside.com.tw/article/18743-Telegram-username" target="_blank">如何產生 Telegram 連結</a>',
        'section'      => 'yc_chatbutton',
        'settings'     => 'yc_chatbutton_tg',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://t.me/telegram',
        )
    ));

    $wp_customize->add_setting('yc_chatbutton_ig', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_chatbutton_ig', array(
        'label'        => '輸入 Instagram 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.tech-girlz.com/2020/08/instagram-bio-link.html" target="_blank">如何產生 Telegram 連結</a>',
        'section'      => 'yc_chatbutton',
        'settings'     => 'yc_chatbutton_ig',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://www.instagram.com/instagram',
        )
    ));

    $wp_customize->add_setting('yc_chatbutton_email', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_chatbutton_email', array(
        'label'        => '輸入 EMAIL 連結',
        'section'      => 'yc_chatbutton',
        'settings'     => 'yc_chatbutton_email',
        'type'         => 'email',
        'input_attrs' => array(
            'placeholder' => '例如：my_name@gmail.com',
        )
    ));

    $wp_customize->add_setting('yc_chatbutton_phone', array(
        'default'     => '',
    ));
    $wp_customize->add_control('yc_chatbutton_phone', array(
        'label'        => '輸入手機號碼',
        'description'  => '請不要輸入"-"等符號在數字中間，會造成用戶無法用手機直接撥號',
        'section'      => 'yc_chatbutton',
        'settings'     => 'yc_chatbutton_phone',
        'type'         => 'number',
        'input_attrs' => array(
            'placeholder' => '例如：0912345678',
        )
    ));
/*
    $wp_customize->add_setting('enable_backtotop', array(
        'default'     => '',
    ));
    $wp_customize->add_control('enable_backtotop', array(
        'label'        => '啟用回到頂端按鈕',
        'section'      => 'yc_chatbutton',
        'settings'     => 'enable_backtotop',
        'type'         => 'checkbox',
    ));
    */
}
function nd_dosth_hide_readmore_on_condition($control)
{
    $setting = $control->manager->get_setting( 'enable_countdown' );
    if( 'yes' == $setting->value() ){
        return true;
    }else{
        return false;
    }
}

function nd_dosth_hide_readmore_on_condition_infinity($control)
{
    $setting = $control->manager->get_setting( 'enable_countdown' );
    if( 'infinity' == $setting->value() ){
        return true;
    }else{
        return false;
    }
}

function nd_dosth_hide_readmore_on_condition_text($control)
{
    $setting = $control->manager->get_setting( 'enable_countdown' );
    if( 'no' != $setting->value() ){
        return true;
    }else{
        return false;
    }
}



/**
 * Live Previews
 */

function yc_customizer()
{
    wp_enqueue_script(
        'yc_customizer',
        YC_ROOT_URL . '/assets/js/customizer_live_previews.js',
        array('jquery', 'customize-preview'),
        '',
        true
    );
}
