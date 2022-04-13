<?php

/**
 * Theme Customizer
 *
 * https://developer.wordpress.org/reference/classes/wp_customize_control/__construct/
 */
add_action('customize_register', 'yc_customizer_settings', 99);
function yc_customizer_settings($wp_customize)
{

    /**
     * 登入頁背景
     * Display Image using customizer image control
     * <img src="<?php echo get_theme_mod('diwp_logo'); ?>" />
     */
    $wp_customize->add_setting( 'yc_login_bg_img', array(
        'default' => 'https://images.unsplash.com/photo-1585409677983-0f6c41ca9c3b?ixlib=rb-1.2.1&q=80&fm=jpg', // Add Default Image URL
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'yc_login_bg_img_control', array(
        'label' => '登入頁背景圖片',
        'priority' => 1000,
        'section' => 'title_tagline',
        'settings' => 'yc_login_bg_img',
        'button_labels' => array(// All These labels are optional
                    'select' => 'Select Logo',
                    'remove' => 'Remove Logo',
                    'change' => 'Change Logo',
                    )
    )));

    /**
     * 聊天按鈕
     */

    $wp_customize->add_section('yc_chat_button', array(
        'title'      => '聊天按鈕',
        'priority'   => 100,
        'description' => '會出現在螢幕右下角',
    ));

    $wp_customize->add_setting('enable_fb_plugin', array(
        'default'     => '',
    ));
    $wp_customize->add_control('enable_fb_plugin', array(
        'label'        => '啟用 FB 即時聊天',
        'description'  => '用戶可在網頁直接傳送訊息',
        'section'      => 'yc_chat_button',
        'settings'     => 'enable_fb_plugin',
        'type'         => 'checkbox',
    ));

    $wp_customize->add_setting('line_link', array(
        'default'     => '',
    ));
    $wp_customize->add_control('line_link', array(
        'label'        => '輸入 LINE 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.pkstep.com/archives/5261" target="_blank">如何產生 LINE 連結</a>',
        'section'      => 'yc_chat_button',
        'settings'     => 'line_link',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://page.line.me/https://page.line.me/xxxxxxxx',
        )
    ));

    $wp_customize->add_setting('telegram_link', array(
        'default'     => '',
    ));
    $wp_customize->add_control('telegram_link', array(
        'label'        => '輸入 Telegram 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.inside.com.tw/article/18743-Telegram-username" target="_blank">如何產生 Telegram 連結</a>',
        'section'      => 'yc_chat_button',
        'settings'     => 'telegram_link',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://t.me/telegram',
        )
    ));

    $wp_customize->add_setting('instagram_link', array(
        'default'     => '',
    ));
    $wp_customize->add_control('instagram_link', array(
        'label'        => '輸入 Instagram 連結',
        'description'  => '<span class="dashicons dashicons-info"></span><a href="https://www.tech-girlz.com/2020/08/instagram-bio-link.html" target="_blank">如何產生 Telegram 連結</a>',
        'section'      => 'yc_chat_button',
        'settings'     => 'instagram_link',
        'type'         => 'text',
        'input_attrs' => array(
            'placeholder' => '例如：https://www.instagram.com/instagram',
        )
    ));

    $wp_customize->add_setting('email_link', array(
        'default'     => '',
    ));
    $wp_customize->add_control('email_link', array(
        'label'        => '輸入 EMAIL 連結',
        'section'      => 'yc_chat_button',
        'settings'     => 'email_link',
        'type'         => 'email',
        'input_attrs' => array(
            'placeholder' => '例如：my_name@gmail.com',
        )
    ));

    $wp_customize->add_setting('phone_link', array(
        'default'     => '',
    ));
    $wp_customize->add_control('phone_link', array(
        'label'        => '輸入手機號碼',
        'description'  => '請不要輸入"-"等符號在數字中間，會造成用戶無法用手機直接撥號',
        'section'      => 'yc_chat_button',
        'settings'     => 'phone_link',
        'type'         => 'number',
        'input_attrs' => array(
            'placeholder' => '例如：0912345678',
        )
    ));

    var_dump('123180561');

    $wp_customize->add_setting('enable_backtotop', array(
        'default'     => '',
    ));
    $wp_customize->add_control('enable_backtotop', array(
        'label'        => '啟用回到頂端按鈕',
        'section'      => 'yc_chat_button',
        'settings'     => 'enable_backtotop',
        'type'         => 'checkbox',
    ));

}




/**
 * Live Previews
 */
add_action('customize_preview_init', 'yc_customizer');
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
