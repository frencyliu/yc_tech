<?php

/**
 * Game post type
 * 賽事系統
 */

namespace YC\Admin\CPT\Game;

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


require_once __DIR__ . '/1_Menu_Game.php';
require_once __DIR__ . '/2_Game_MetaBox.php';
