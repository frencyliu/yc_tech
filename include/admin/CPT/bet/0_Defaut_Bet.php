<?php

/**
 * BET post type
 * 下注系統
 */

namespace YC\Admin\CPT\Bet;

use YC_TECH;

defined('ABSPATH') || exit;

if (!BET) return;

/*
class _Default extends YC_TECH
{
    public function __construct()
    {
        $this->_loadCustomPostType();
    }

    private function _loadCustomPostType()
    {

    }
}
new _Default();
*/


require_once __DIR__ . '/1_Menu_Bet.php';
require_once __DIR__ . '/2_Bet_MetaBox.php';
//require_once __DIR__ . '/3_Frontend_Form.php'; //do not add here 
