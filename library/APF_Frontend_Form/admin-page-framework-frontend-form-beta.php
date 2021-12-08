<?php
/** 
 *  Plugin Name:    Admin Page Framework - Front-end Form (beta)
 *  Plugin URI:     http://en.michaeluno.jp/admin-page-framework
 *  Description:    Demonstrates front-end forms using Admin Page Framework.
 *  Author:         Michael Uno
 *  Author URI:     http://michaeluno.jp
 *  Version:        1.0.0
 * 
 */
     
final class AdminPageFramework_FrontEndFormBeta_Bootstrap {
    
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'replyToLoadPlugin' ) );
    }
    
    public function replyToLoadPlugin() {

        $_sAPFPath = defined( 'WP_DEBUG' ) && WP_DEBUG
            ? dirname( dirname( __FILE__ ) ) . '/admin-page-framework/development/admin-page-framework.php'
            : dirname( dirname( __FILE__ ) ) . '/admin-page-framework/library/apf/admin-page-framework.php';
        include( $_sAPFPath );
        if ( ! class_exists( 'AdminPageFramework' ) ) {
            return;
        }    
        
        include( dirname( __FILE__ ) . '/AdminPageFramework_FormBeta_frontend.php' );
        include( dirname( __FILE__ ) . '/AdminPageFramework_FrontendFormBeta_Base.php' );
        include( dirname( __FILE__ ) . '/AdminPageFramework_FrontendFormBeta.php' );
        
    }

}
new AdminPageFramework_FrontEndFormBeta_Bootstrap;
