<?php

namespace YC\Admin\CPT\Bet;

//if (!class_exists('AdminPageFramework_FrontendFormBeta_Base')) return;

class _Frontend_Form extends \AdminPageFramework_FrontendFormBeta_Base
{

    public $sHookName = 'gg_before';
    public $sHookType = 'action';
    public function getFields() {
             return array(
                 array(
                     'title'             => __( '下注金額', 'admin-page-framework' ),
                     'field_id'          => 'bet_amount',
                     'type'              => 'number',
                 ),
                 array(
                     'field_id'          => '_submit',
                     'type'              => 'submit',
                     'value'             => __( 'Submit', 'admin-page-framework' ),
                 ),
             );
        }
        /*public function getStyles() {
                 return array(
                     dirname( __FILE__ )  . '/css/style.css',
                 );
            }*/
}
new _Frontend_Form();
