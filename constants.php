<?php

/**
 * 設定常數
 */

namespace YC;
use YC_TECH;

defined('ABSPATH') || exit;

class _Const extends YC_TECH
{

    public function __construct()
    {
            if (!defined('BET')) define('BET', false);

            define('YC_ROOT_DIR', plugin_dir_path( __FILE__ ));


            if (!defined('IS_WC')) define('IS_WC', class_exists('WooCommerce', false));

            if (!defined('COMMENTS_ENABLE')) define('COMMENTS_ENABLE', false);
            if (!defined('FLUSH_METABOX')) define('FLUSH_METABOX', false);
            if (!defined('ONESHOP')) define('ONESHOP', false);
            if (!defined('ENABLE_DOWNLOAD_PRODUCT')) define('ENABLE_DOWNLOAD_PRODUCT', false);

            if (!defined('YCWC_LINK_TO_PRODUCT')) define('YCWC_LINK_TO_PRODUCT', true);
            if (!defined('YCWC_SHOW_ADD_TO_CART_WHEN_LOOP')) define('YCWC_SHOW_ADD_TO_CART_WHEN_LOOP', true);
            if (!defined('YCWC_SHOW_DIRECT_BUY_WHEN_LOOP')) define('YCWC_SHOW_DIRECT_BUY_WHEN_LOOP', true);
            if (!defined('YCWC_SHOW_EXCERPT_WHEN_LOOP')) define('YCWC_SHOW_EXCERPT_WHEN_LOOP', false);
            if (!defined('FA_ENABLE')) define('FA_ENABLE', true);
            if (!defined('TAG_ENABLE')) define('TAG_ENABLE', false);
            if (!defined('CAT_RADIO')) define('CAT_RADIO', false);//設分類為單選
            if (!defined('VIDEO_CPT')) define('VIDEO_CPT', false);//設分類為單選


            if (!defined('ROW_ACTION_ENABLE')) define('ROW_ACTION_ENABLE', false);

            //可以改寫成用到再載入
            if (!defined('FLIPSTER_ENABLE')) define('FLIPSTER_ENABLE', false);
            if (!defined('SLICK_ENABLE')) define('SLICK_ENABLE', false);
            //是否啟用擴充模組
            if (!defined('EXTENSIONS_ENABLE')) define('EXTENSIONS_ENABLE', false);

    }

}
new _Const();