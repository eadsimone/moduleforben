<?php

class Lucky_Donations_Block_Adminhtml_Report_Byorder extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_blockGroup = 'donations';
    $this->_controller = 'adminhtml_report_byorder';
    $this->_headerText = Mage::helper('reports')->__('Donations By Order');
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
    return $this->getUrl('*/*/byorder', array('_current' => true));
  }
}