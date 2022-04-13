<?php

/**
 * 電商設定
 * 優惠功能應該綁訂優惠券設定
 */



if (IS_WC) {
    add_action('customize_register', 'ycwc_setting_action', 99);

    function ycwc_setting_action($wp_customize)
    {
        $wc_advance_setting = add_query_arg( array(
            'page' => 'wc-settings',
        ), admin_url( 'admin.php' ) );
        $wp_customize->add_section( 'ycwc_setting' , array(
            'title'      => '商店設定',
            'priority'   => 100,
            'description' => '這邊是簡易設定，詳細設定請前往<a target="_blank" href="' . $wc_advance_setting . '">商店進階設定</a>',
        ) );


        $wp_customize->add_setting('ycwc_coupon_text', array(
            'default'      => '',
        ));
        $wp_customize->add_control('ycwc_coupon_text', array(
            'label'        => '輸入優惠時間',
            'section'      => 'ycwc_setting',
            'settings'     => 'ycwc_coupon_text',
            'type'         => 'text',
            'input_attrs' => array(
                'placeholder' => '例如：全館滿999免運費',
            ),
        ));

    }
}
