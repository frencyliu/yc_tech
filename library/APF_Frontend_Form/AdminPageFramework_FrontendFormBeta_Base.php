<?php

/**
 * Provides an abstract base class for front-end forms.
 * 
 * Usage
 * 
 * 1. In your desired location, print an output of your custom WordPress filter.
 * ```
 * echo apply_filters( 'your_custom_filter', '' );
 * ```
 * 
 * Or, do an action.
 * ```
 * do_action( 'your_custom_action' );
 * ```
 * 
 * 2. Extend this class.
 * ```
 * class YourFrontendFormClass extends AdminPageFramework_FrontendFormBeta_Base {
 *  
 * }
 * ```
 * 
 * 3. Set your filter or action in the `$sHookName` property.
 * ```
 * public $sHookName = 'your_custom_filter';
 * public $sHookType = 'filter';
 * ```
 * Or
 * ```
 * public $sHookName = 'your_custom_action';
 * public $sHookType = 'action';
 * ```
 * 
 * 4. In the `getFields()` method, define field definition arrays to return.
 * ```
 * public function getFields() {
 *      return array(
 *          array(
 *              'title'             => __( 'Text Input', 'admin-page-framework' ),
 *              'field_id'          => 'text',
 *              'type'              => 'text',
 *          ),  
 *          array(
 *              'field_id'          => '_submit',
 *              'type'              => 'submit',
 *              'value'             => __( 'Submit', 'admin-page-framework' ),
 *          ),
 *      );
 * }
 * ```
 * Similarly, define section in the `getSections()` method.
 * 
 * 5. In the `getStyles()` method, define your CSS rules or set paths for stylesheets.
 * ```
 * public function getStyles() {
 *      return array(
 *          dirname( __FILE__ )  . '/css/style.css',
 *      );
 * }
 * Similarly, set script paths in the `getScripts()` method.
 * 
 * ```
 * 6. Instantiate the class.
 * ```
 * new YourFrontendFormClass;
 * ```
 * 
 * When you include this class with your generated framework files, 
 * make sure the used class names here with AdminPageFramework are all prefixed with the one you set in Generator.
 * 
 * @remark      Required Admin Page Framework 3.8.11 or above.
 */
abstract class AdminPageFramework_FrontendFormBeta_Base {
    
    /* Override these properties from here */
    public $sHookName = ''; // e.g `the_content`
    public $iPriority = 11;
    public $sHookType = 'filter';   // or action    
    /* to here */
    
    /**
     * Stores the form field values.
     */
    public $aFormData  = array();
    
    public $aFieldErrors = array();
    
    public $aSubmitNotices = array();
    
    public $oForm;
        
    private $___sNonceActionName = 'admin-page-framework-frontend_form';
    
    /**
     * Sets up hooks.
     */
    public function __construct() {                 
        add_action( 'wp', array( $this, '_replyToDetermineWhetherToLoad' ) );       
        $this->construct();
    }
        /**
         * Determines whether to load the form.
         * @callback    action      wp
         * @return      void
         */
        public function _replyToDetermineWhetherToLoad() {
            
            if ( ! class_exists( 'AdminPageFramework_Message' ) ) {
                return;
            }
            
            if ( ! $this->isInThePage() ) {
                return;
            }
                        
            $this->___setHooks();
            $this->___setProperties();            
            
            /**
             * Let the user do necessary routines before setting up the form such as showing a notification message based on the form submission etc.
             */
            $this->load();
            
            $this->___setUp();
            
            if ( $this->___isFormSubmitted() ) {
                $this->___handleFormSubmission();
            }            
            
        }
            /**
             * @return      boolean
             */
            private function ___isFormSubmitted() {
                if ( ! isset( $_REQUEST[ $this->___sNonceActionName ] ) ) {
                    return false;
                }
                if ( ! wp_verify_nonce( $_REQUEST[ $this->___sNonceActionName ], $this->___sNonceActionName ) ) {
                    return false;
                }                                
                return true;
            }        
            private function ___handleFormSubmission() {
                $aInputs = $_REQUEST;
                unset( 
                    $aInputs[ '_admin-page-framework_frontend_form' ],
                    $aInputs[ '_wp_http_referer' ],
                    $aInputs[ '__submit' ],
                    $aInputs[ '__repeatable_elements_' . $this->oForm->aArguments[ 'structure_type' ] ]
                );
                $this->validate( $aInputs );

            }
        
            private function ___setProperties() {
                $this->oForm = new AdminPageFramework_FormBeta_frontend(
                    array(  // form object arguments
                        'caller_id'                     => get_class( $this ),
                        'structure_type'                => 'frontend',
                        'action_hook_form_registration' => 'wp_enqueue_scripts',                            
                        
                        // custom arguments
                        'caller_object'                 => $this,
                    ),  
                    array(  // callbacks
                        'saved_data'   => array( $this, 'replyToSetFormData' ),
                    )
                );
            }
            private function ___setHooks() {
                if ( ! $this->sHookName ) {
                    return;
                }
                $_aFunctionNames = array(
                    'filter'    => 'add_filter',            
                    'action'    => 'add_action',
                );
                $_sFunctionName = isset( $_aFunctionNames[ $this->sHookType ] ) 
                    ? $_aFunctionNames[ $this->sHookType ] 
                    : $_aFunctionNames[ 'filter' ];
                call_user_func_array(
                    $_sFunctionName,
                    array(
                        $this->sHookName, 
                        array( $this, '_replyToGetForm' ),
                        $this->iPriority
                    )
                );
            }        
                /**
                 * @callback        filter|action
                 */
                public function _replyToGetForm( $sContent ) {
                    if ( 'filter' === $this->sHookType ) {
                        return $sContent . $this->___getFormWrapped();
                    }
                    echo $this->___getFormWrapped();
                }
                    /**
                     * @return      string
                     */
                    private function ___getFormWrapped() {  
                        return $this->___getSubmitNotices()
                            . "<form class='admin-page-framework-frontend-form' method='post'>"
                                . $this->___getHiddenFields()
                                . $this->___getFormOutput()
                            . "</form>";
                    }
                        /**
                         * @return      string
                         */
                        private function ___getSubmitNotices() {
                            $_sOutput = '';
                            if ( empty( $this->aSubmitNotices ) ) {
                                return $_sOutput;
                            }
                            foreach( $this->aSubmitNotices as $_aNotice ) {
                                $_sOutput .= "<div class='submit-notice " . esc_attr( $_aNotice[ 1 ] ) . "'>"
                                        . "<p>" . $_aNotice[ 0 ] . "</p>"
                                    . "</div>";
                            }
                            return $_sOutput;
                        }
                        private function ___getFormOutput() {
                            return $this->content( $this->oForm->get() );
                        }
                        private function ___getHiddenFields() {                            
                            return wp_nonce_field( 
                                $this->___sNonceActionName,   // nonce action name
                                $this->___sNonceActionName,  // input name
                                true, // embed referrer
                                false // echo 
                            );
                        }
                /**
                 * Sets up form elements.
                 */
                private function ___setUp() {
                    
                    $this->___setResourcesByType( ( array ) $this->getStyles(), 'style' );
                    $this->___setResourcesByType( ( array ) $this->getScripts(), 'script' );
                    
                    $_aSections = $this->getSections();
                    $_aSections = is_array( $_aSections ) ? $_aSections : array();
                    foreach( $_aSections as $_asSection ) {
                        $this->oForm->addSection( $_asSection );
                    }
                    $_aFields   = $this->getFields();
                    $_aFields   = is_array( $_aFields ) ? $_aFields : array();
                    foreach( $_aFields as $_asField ) {
                        $this->oForm->addField( $_asField );
                    }
                    
                }
                    private function ___setResourcesByType( array $aItems, $sType ) {
                        foreach( $aItems as $_iItem ) {
                            if ( ! $_iItem ) {
                                continue;
                            }
                            if ( $this->___isURL( $_iItem ) || file_exists( $_iItem ) ) {
                                $_sKey = 'style' === $sType ? 'src_styles' : 'src_scripts';
                                $this->oForm->addResource( $_sKey, $_iItem );
                            }
                            $_sKey = 'style' === $sType ? 'internal_styles' : 'internal_scripts';
                            $this->oForm->addResource( $_sKey, $_iItem );
                        }                            
                    }
                        /**
                         * @return      boolean
                         */
                        private function ___isURL( $sItem ) {
                            return ( ! filter_var( $sItem, FILTER_VALIDATE_URL ) === false );                
                        }   
                        
    /**
     * @callback    form_filter        saved_data        
     * @return      array
     */
    public function replyToSetFormData( $aData ) {
        return $this->aFormData + $aData;
    }
      
    /**
     * Sets a given field errors.
     * @return      void
     */
    public function setFieldErrors( $aErrors ) {       
        $this->aFieldErrors = $aErrors;
    }

    /**
     * Sets the given message to be displayed in the next page load. 
     * 
     * This is used to inform users about the submitted input data, such as "Updated successfully." or "Problem occurred." etc. 
     * and normally used in validation callback methods.
     * 
     * <h4>Example</h4>
     * `
     * if ( ! $bVerified ) {
     *       $this->setFieldErrors( $aErrors );     
     *       $this->setSubmitNotice( 'There was an error in your input.' );
     *       return $aOldPageOptions;
     * }
     * `
     * @access       public
     * @param        string      $sMessage       the text message to be displayed.
     * @param        string      $sType          (optional) the type of the message, either "error" or "updated"  is used.
     * @param        array       $asAttributes   (optional) the tag attribute array applied to the message container HTML element. If a string is given, it is used as the ID attribute value.
     * @param        boolean     $bOverride      (optional) If true, only one message will be shown in the next page load. false: do not override when there is a message of the same id. true: override the previous one.
     * @return       void
     */
    public function setSubmitNotice( $sMessage, $sType='error', $asAttributes=array(), $bOverride=true ) {
         $_aNotice = func_get_args() + array( '', 'error', array(), false );
        // Has a message?
        if ( ! $_aNotice[ 0 ] ) {
            return;
        }
        // Override? 
        if ( $_aNotice[ 3 ] ) {
            $this->aSubmitNotices = $_aNotice;
            return;
        }
        $this->aSubmitNotices[] = $_aNotice;
    }
    
    /** ----------------------------------------------------------------------------------- 
     * Override the methods below.
     */
    
    /**
     * User constructor.
     * @remark      Override this method to do necessary set-ups.
     */
    public function construct() {}

    /**
     * Gets called when the page loads. Do some necessary set-ups here.
     */
    public function load() {}
    
    /**
     * @remark      Override this method in an extended class.
     * @return      boolean
     */
    public function isInThePage() {
        return true;
    }    
    
    /**
     * Return the form output.
     * @return      string
     */
    public function content( $sFormOutput ) {
        return $sFormOutput;
    }

    /**
     * Retrieves form section definition arrays.
     * @return  array
     */
    public function getSections() {
        return array();
    }
    
    /**
     * Retrieves form field definition arrays.
     * @return      array
     */
    public function getFields() {
        return array();
    }
    
    /**
     * @return      array
     */
    public function validate( $aInputs ) {
        return array();
    }
    
    /**
     * Return internal CSS rules.
     * @return      array
     * ```
     * return array(
     *      dirname( __FILE__ ) . '/css/style.css',     
     *      dirname( __FILE__ ) . '/css/style2.css',    // path
     *      plugins_url( '/asset/css/style.css' ),      // url
     *      '.form-field { max-width: 100; }',          // text rules
     * );
     * ```
     */
    public function getStyles() {
        return array();
    }
    /**
     * Return internal JavaSript script.
     * @return      array
     * ```
     * return array(
     *      dirname( __FILE__ ) . '/js/script.js',     
     *      dirname( __FILE__ ) . '/js/script2.js',    // path
     *      plugins_url( '/asset/js/script.js' ),      // url
     *      'jQuery( '.admin-page-framework-section-tabs-contents' ).createTabs(); ', // text script
     * );
     * ```
     */
    public function getScripts() {
        return array();
    }
}
