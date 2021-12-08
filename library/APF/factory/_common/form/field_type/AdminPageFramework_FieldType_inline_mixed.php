<?php 
/**
	Admin Page Framework v3.8.34 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/YC_TECH>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yc_AdminPageFramework_FieldType__nested extends yc_AdminPageFramework_FieldType {
    public $aFieldTypeSlugs = array('_nested');
    protected $aDefaultKeys = array();
    protected function getStyles() {
        return ".YC_TECH-fieldset > .YC_TECH-fields > .YC_TECH-field.with-nested-fields > .YC_TECH-fieldset.multiple-nesting {margin-left: 2em;}.YC_TECH-fieldset > .YC_TECH-fields > .YC_TECH-field.with-nested-fields > .YC_TECH-fieldset {margin-bottom: 1em;}.with-nested-fields > .YC_TECH-fieldset.child-fieldset > .YC_TECH-child-field-title {display: inline-block;padding: 0 0 0.4em 0;}.YC_TECH-fieldset.child-fieldset > label.YC_TECH-child-field-title {display: table-row; white-space: nowrap;}";
    }
    protected function getField($aField) {
        $_oCallerForm = $aField['_caller_object'];
        $_aInlineMixedOutput = array();
        foreach ($this->getAsArray($aField['content']) as $_aChildFieldset) {
            if (is_scalar($_aChildFieldset)) {
                continue;
            }
            if (!$this->isNormalPlacement($_aChildFieldset)) {
                continue;
            }
            $_aChildFieldset = $this->getFieldsetReformattedBySubFieldIndex($_aChildFieldset, ( integer )$aField['_index'], $aField['_is_multiple_fields'], $aField);
            $_oFieldset = new yc_AdminPageFramework_Form_View___Fieldset($_aChildFieldset, $_oCallerForm->aSavedData, $_oCallerForm->getFieldErrors(), $_oCallerForm->aFieldTypeDefinitions, $_oCallerForm->oMsg, $_oCallerForm->aCallbacks);
            $_aInlineMixedOutput[] = $_oFieldset->get();
        }
        return implode('', $_aInlineMixedOutput);
    }
    }
    class yc_AdminPageFramework_FieldType_inline_mixed extends yc_AdminPageFramework_FieldType__nested {
        public $aFieldTypeSlugs = array('inline_mixed');
        protected $aDefaultKeys = array('label_min_width' => '', 'show_debug_info' => false,);
        protected function getStyles() {
            return ".YC_TECH-field-inline_mixed {width: 98%;}.YC_TECH-field-inline_mixed > fieldset {display: inline-block;overflow-x: visible;padding-right: 0.4em;vertical-align: middle;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields{display: inline;width: auto;table-layout: auto;margin: 0;padding: 0;vertical-align: middle;white-space: nowrap;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field {float: none;clear: none;width: 100%;display: inline-block;margin-right: auto;vertical-align: middle; }.with-mixed-fields > fieldset > label {width: auto;padding: 0;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field .YC_TECH-input-label-string {padding: 0;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > .YC_TECH-input-label-container,.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > * > .YC_TECH-input-label-container{padding: 0;display: inline;width: 100%;vertical-align: middle;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > .YC_TECH-input-label-container > label,.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > * > .YC_TECH-input-label-container > label{display: inline;vertical-align: middle;}.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > .YC_TECH-input-label-container > label > input,.YC_TECH-field-inline_mixed > fieldset > .YC_TECH-fields > .YC_TECH-field > * > .YC_TECH-input-label-container > label > input{display: inline;min-width: 100%;margin-right: 0.2em;margin-left: auto;vertical-align: middle;}.YC_TECH-field-inline_mixed .YC_TECH-input-label-container,.YC_TECH-field-inline_mixed .YC_TECH-input-label-string{min-width: 0;}.YC_TECH-field-inline_mixed .YC_TECH-input-label-container button.button {margin: 0;}";
        }
    }
    