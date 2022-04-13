<?php

/**
 *
 */

namespace YC\FrontEnd;

use YC_TECH;

defined('ABSPATH') || exit;


class _ChatButton extends YC_TECH
{

    public function __construct()
    {
        //CHATBUTTON 前端顯示
        add_action('wp_footer', [$this, 'yc_add_chatbutton_frontend']);
    }

    public function yc_add_chatbutton_frontend()
    {
        $fb_chat_plugin_activate = is_plugin_active("facebook-messenger-customer-chat/facebook-messenger-customer-chat.php");
        $class = ($fb_chat_plugin_activate) ? 'yc_chatbutton' : 'yc_chatbutton_no_fb';

        $chatbutton_order = [
            'yc_chatbutton_phone',
            'yc_chatbutton_email',
            'yc_chatbutton_ig',
            'yc_chatbutton_tg',
            'yc_chatbutton_line',
        ];

        $html_btn = '';
        foreach ($chatbutton_order as $button) {
            if (!empty(get_theme_mod($button))) {
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
                $html_btn .= '<a href="' . $prefix . get_theme_mod($button) . '" class="' . $button . '" target="_blank"></a>';
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

}
new _ChatButton();
