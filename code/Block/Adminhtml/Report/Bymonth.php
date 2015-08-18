<?php

class Lucky_Donations_Block_Adminhtml_Report_Bymonth extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_blockGroup = 'donations';
    $this->_controller = 'adminhtml_report_bymonth';
    $this->_headerText = Mage::helper('reports')->__('Donations By Month');
    parent::__construct();
    $this->setTemplate('report/grid/container.phtml');
    $this->_removeButton('add');
    $this->addButton('filter_form_submit', array(
      'label'     => Mage::helper('reports')->__('Show Report'),
      'onclick'   => 'filterFormSubmit()'
    ));
  }

  public function getFilterUrl()
  {
    $this->getRequest()->setParam('filter', null);
    return $this->getUrl('*/*/bymonth', array('_current' => true));
  }
}