<?php 
/**
	Admin Page Framework v3.8.34 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/yc_tech>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yc_AdminPageFramework_Form_View___Script_SortableField extends yc_AdminPageFramework_Form_View___Script_Base {
    public function construct() {
        wp_enqueue_script('jquery-ui-sortable');
    }
    static public function getScript() {
        return <<<JAVASCRIPTS
(function($) {
    $.fn.enableyc_AdminPageFrameworkSortableFields = function( sFieldsContainerID ) {

        var _oTarget    = 'string' === typeof sFieldsContainerID
            ? $( '#' + sFieldsContainerID + '.sortable' )
            : this;
        
        _oTarget.off( 'sortupdate' );
        _oTarget.off( 'sortstop' );
        var _oSortable  = _oTarget.sortable(
            // the options for the sortable plugin
            { 
                items: '> div:not( .disabled )',
            } 
        );

        // Callback the registered functions.
        _oSortable.on( 'sortstop', function() {
            $( this ).callBackStoppedSortingFields( 
                $( this ).data( 'type' ),
                $( this ).attr( 'id' ),
                0  // call type 0: fields, 1: sections
            );  
        });
        _oSortable.on( 'sortupdate', function() {
            $( this ).callBackSortedFields( 
                $( this ).data( 'type' ),
                $( this ).attr( 'id' ),
                0  // call type 0: fields, 1: sections
            );
        });                 
    
    };
}( jQuery ));
JAVASCRIPTS;
        
    }
    }
    class yc_AdminPageFramework_Form_View___Script_SortableSection extends yc_AdminPageFramework_Form_View___Script_SortableField {
        static public function getScript() {
            return <<<JAVASCRIPTS
(function($) {
    $.fn.enableyc_AdminPageFrameworkSortableSections = function( sSectionsContainerID ) {

        var _oTarget    = 'string' === typeof sSectionsContainerID 
            ? $( '#' + sSectionsContainerID + '.sortable-section' )
            : this;

        // For tabbed sections, enable the sort to the tabs.
        var _bIsTabbed      = _oTarget.hasClass( 'yc_tech-section-tabs-contents' );
        var _bCollapsible   = 0 < _oTarget.children( '.yc_tech-section.is_subsection_collapsible' ).length;

        var _oTarget        = _bIsTabbed
            ? _oTarget.find( 'ul.yc_tech-section-tabs' )
            : _oTarget;

        _oTarget.off( 'sortupdate' );
        _oTarget.off( 'sortstop' );
        
        var _aSortableOptions = { 
                items: _bIsTabbed
                    ? '> li:not( .disabled )'
                    : '> div:not( .disabled, .yc_tech-collapsible-toggle-all-button-container )', 
                handle: _bCollapsible
                    ? '.yc_tech-section-caption'
                    : false,
                
                stop: function(e,ui) {

                    // Callback the registered callback functions.
                    jQuery( this ).trigger( 
                        'yc_tech_stopped_sorting_sections', 
                        []  // parameters for the callbacks 
                    );                    

                },
			
                
                // @todo Figure out how to allow the user to highlight text in sortable elements.
                // cancel: '.yc_tech-section-description, .yc_tech-section-title'
                
            }
        var _oSortable  = _oTarget.sortable( _aSortableOptions );               
        
        if ( ! _bIsTabbed ) {
            
            _oSortable.on( 'sortstop', function() {
                                    
                jQuery( this ).find( 'caption > .yc_tech-section-title:not(.yc_tech-collapsible-sections-title,.yc_tech-collapsible-section-title)' ).first().show();
                jQuery( this ).find( 'caption > .yc_tech-section-title:not(.yc_tech-collapsible-sections-title,.yc_tech-collapsible-section-title)' ).not( ':first' ).hide();
                
            } );            
            
        }            
    
    };
}( jQuery ));
JAVASCRIPTS;
            
        }
        static private $_aSetContainerIDsForSortableSections = array();
        static public function getEnabler($sContainerTagID, $aSettings, $oMsg) {
            if (empty($aSettings)) {
                return '';
            }
            if (in_array($sContainerTagID, self::$_aSetContainerIDsForSortableSections)) {
                return '';
            }
            self::$_aSetContainerIDsForSortableSections[$sContainerTagID] = $sContainerTagID;
            new self($oMsg);
            $_sScript = <<<JAVASCRIPTS
jQuery( document ).ready( function() {    
    jQuery( '#{$sContainerTagID}' ).enableyc_AdminPageFrameworkSortableSections( '{$sContainerTagID}' ); 
});            
JAVASCRIPTS;
            return "<script type='text/javascript' class='yc_tech-section-sortable-script'>" . '/* <![CDATA[ */' . $_sScript . '/* ]]> */' . "</script>";
        }
    }
    