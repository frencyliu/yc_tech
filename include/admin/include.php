<?php
require_once __DIR__ . '/0_Defaut_Admin.php';
require_once __DIR__ . '/custumizer/include.php';
require_once __DIR__ . '/1_YCWC.php';
require_once __DIR__ . '/css/0_Defaut_CSS_JS.php';
require_once __DIR__ . '/menu/0_Defaut_Menu.php';
require_once __DIR__ . '/post/0_Defaut_Post.php';
require_once __DIR__ . '/CPT/include.php';
require_once __DIR__ . '/column/0_Defaut_Column.php';
require_once __DIR__ . '/user/0_Defaut_User.php';
require_once __DIR__ . '/row_action/0_Defaut_Row_Action.php';
require_once __DIR__ . '/metabox/0_Defaut_Metabox.php';
require_once __DIR__ . '/notice/0_Defaut_Notice.php';
require_once __DIR__ . '/integrate/include.php';




if(class_exists('WCMp', false)){
    require_once __DIR__ . '/class-wcmp.php';
    new \YC\Admin\WCMP;
}
