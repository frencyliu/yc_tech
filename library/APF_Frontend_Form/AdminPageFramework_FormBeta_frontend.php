<?php

     
class AdminPageFramework_FormBeta_frontend extends AdminPageFramework_Form {
   
    /**
     * Stores sub-object class names.
     * 
     * 
     * @since       3.8.11
     */
    public $aSubClasses = array(
        'submit_notice' => '',  // disable it
        'field_error'   => '',  // disable it
        'last_input'    => '',  // disable it
        'message'       => 'AdminPageFramework_Message',
    );
   
    public function setFieldErrors( $aErrors ) {}
   
    public function getFieldErrors() {                
        return $this->aArguments[ 'caller_object' ]->aFieldErrors;
    }
   
}
