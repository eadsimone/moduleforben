<?php

class Lucky_Donations_Block_Adminhtml_Charity extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_blockGroup = 'donations';
    $this->_controller = 'adminhtml_charity';
    $this->_headerText = Mage::helper('adminhtml')->__('Manage Charities');
    $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add New');
    parent::__construct();
  }
}