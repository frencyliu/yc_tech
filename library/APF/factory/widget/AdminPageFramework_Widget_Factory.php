<?php 
/**
	Admin Page Framework v3.8.34 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/yc_tech>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class yc_AdminPageFramework_Widget_Factory extends WP_Widget {
    public $oCaller;
    public function __construct($oCaller, $sWidgetTitle, array $aArguments = array()) {
        $aArguments = $aArguments + array('classname' => 'admin_page_framework_widget', 'description' => '',);
        parent::__construct($oCaller->oProp->sClassName, $sWidgetTitle, $aArguments);
        $this->oCaller = $oCaller;
    }
    public function widget($aArguments, $aFormData) {
        echo $aArguments['before_widget'];
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, 'do_' . $this->oCaller->oProp->sClassName, $this->oCaller);
        $_sContent = $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "content_{$this->oCaller->oProp->sClassName}", $this->oCaller->content('', $aArguments, $aFormData), $aArguments, $aFormData);
        echo $this->_getTitle($aArguments, $aFormData);
        echo $_sContent;
        echo $aArguments['after_widget'];
    }
    private function _getTitle(array $aArguments, array $aFormData) {
        if (!$this->oCaller->oProp->bShowWidgetTitle) {
            return '';
        }
        $_sTitle = apply_filters('widget_title', $this->oCaller->oUtil->getElement($aFormData, 'title', ''), $aFormData, $this->id_base);
        if (!$_sTitle) {
            return '';
        }
        return $aArguments['before_title'] . $_sTitle . $aArguments['after_title'];
    }
    public function update($aSubmittedFormData, $aSavedFormData) {
        $aSubmittedFormData = $this->oCaller->oUtil->addAndApplyFilters($this->oCaller, "validation_{$this->oCaller->oProp->sClassName}", call_user_func_array(array($this->oCaller, 'validate'), array($aSubmittedFormData, $aSavedFormData, $this->oCaller)), $aSavedFormData, $this->oCaller);
        return $this->oCaller->oForm->getInputsUnset($aSubmittedFormData, $this->oCaller->oForm->sStructureType);
    }
    public function form($aSavedFormData) {
        $this->oCaller->oForm->aCallbacks = $this->_getFormCallbacks() + $this->oCaller->oForm->aCallbacks;
        $this->oCaller->oProp->aOptions = $aSavedFormData;
        $this->_loadFrameworkFactory();
        $this->oCaller->_printWidgetForm();
        $_aFieldTypeDefinitions = $this->oCaller->oForm->aFieldTypeDefinitions;
        $_aSectionsets = $this->oCaller->oForm->aSectionsets;
        $_aFieldsets = $this->oCaller->oForm->aFieldsets;
        $this->oCaller->oForm = new yc_AdminPageFramework_Form_widget(array('register_if_action_already_done' => false,) + $this->oCaller->oProp->aFormArguments, $this->oCaller->oForm->aCallbacks, $this->oCaller->oMsg);
        $this->oCaller->oForm->aFieldTypeDefinitions = $_aFieldTypeDefinitions;
        $this->oCaller->oForm->aSectionsets = $_aSectionsets;
        $this->oCaller->oForm->aFieldsets = $_aFieldsets;
    }
    private function _loadFrameworkFactory() {
        if ($this->oCaller->oProp->bAssumedAsWPWidget) {
            return;
        }
        if ($this->oCaller->oUtil->hasBeenCalled('_widget_load_' . $this->oCaller->oProp->sClassName)) {
            $this->oCaller->oForm->aSavedData = $this->_replyToGetSavedFormData();
            return;
        }
        call_user_func(array($this->oCaller, 'load'));
        $this->oCaller->oUtil->addAndDoActions($this->oCaller, array('load_' . $this->oCaller->oProp->sClassName,), $this->oCaller);
    }
    private function _getFormCallbacks() {
        return array('hfID' => array($this, 'get_field_id'), 'hfTagID' => array($this, 'get_field_id'), 'hfName' => array($this, '_replyToGetFieldName'), 'hfInputName' => array($this, '_replyToGetFieldInputName'), 'saved_data' => array($this, '_replyToGetSavedFormData'),) + $this->oCaller->oProp->getFormCallbacks();
    }
    public function _replyToGetSavedFormData() {
        return $this->oCaller->oUtil->addAndApplyFilter($this->oCaller, 'options_' . $this->oCaller->oProp->sClassName, $this->oCaller->oProp->aOptions, $this->id);
    }
    public function _replyToGetFieldName() {
        $_aParams = func_get_args() + array(null, null, null);
        $aFieldset = $_aParams[1];
        return $this->_getNameAttributeDimensions($aFieldset);
    }
    private function _getNameAttributeDimensions($aFieldset) {
        $_sNamePrefix = 'widget-' . $this->id_base . '[' . $this->number . ']';
        $_sDimensions = '';
        if ($this->oCaller->isSectionSet($aFieldset)) {
            $_aSectionPath = $aFieldset['_section_path_array'];
            foreach ($_aSectionPath as $_sDimension) {
                $_sDimensions.= '[' . $_sDimension . ']';
            }
            if (isset($aFieldset['_section_index'])) {
                $_sDimensions.= '[' . $aFieldset['_section_index'] . ']';
            }
        }
        foreach ($aFieldset['_field_path_array'] as $_sPathPart) {
            $_sDimensions.= '[' . $_sPathPart . ']';
        }
        return $_sNamePrefix . $_sDimensions;
    }
    public function _replyToGetFieldInputName() {
        $_aParams = func_get_args() + array(null, null, null);
        $aFieldset = $_aParams[1];
        $sIndex = $_aParams[2];
        $_sIndex = $this->oCaller->oUtil->getAOrB('0' !== $sIndex && empty($sIndex), '', "[" . $sIndex . "]");
        $_sNamePrefix = $this->_replyToGetFieldName('', $aFieldset);
        return $_sNamePrefix . $_sIndex;
    }
    }
    