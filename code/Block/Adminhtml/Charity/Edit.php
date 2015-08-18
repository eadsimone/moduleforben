<?php

class Lucky_Donations_Block_Adminhtml_Charity_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
      parent::__construct();
      $this->_objectId = 'id';
      $this->_blockGroup = 'donations';
      $this->_controller = 'adminhtml_charity';

      $this->_updateButton('save', 'label', Mage::helper('donations')->__('Save'));
      $this->_updateButton('delete', 'label', Mage::helper('donations')->__('Delete'));

      if( $this->getRequest()->getParam($this->_objectId) ) {
        $model = Mage::getModel('donations/charity')
          ->load($this->getRequest()->getParam($this->_objectId));
        Mage::register('charity_data', $model);
      }
    }

    public function getHeaderText()
    {
        if( Mage::registry('charity_data') && Mage::registry('charity_data')->getId() ) {
            return Mage::helper('donations')->__("Edit charity", $this->htmlEscape(Mage::registry('charity_data')->getName()));
        } else {
            return Mage::helper('donations')->__('New charity');
        }
    }
}
