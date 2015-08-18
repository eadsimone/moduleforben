<?php

/**
 * Add Organization Form
 *
 * @author gabriel
 */
class Lucky_Donations_Block_Cms_Form_Add extends Mage_Core_Block_Template {
    
    protected $_template = "donations/cms/form/add.phtml";
    
    protected function _construct() {
        if(!$this->hasData('form_action')) {
            $this->setFormAction('donations/donations/submitOrganization');
        }
        parent::_construct();
    }
    
}

