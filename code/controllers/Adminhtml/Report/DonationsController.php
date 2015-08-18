<?php

class Lucky_Donations_Adminhtml_Report_DonationsController extends Mage_Adminhtml_Controller_Action
{
  protected $_adminSession = null;
  public function _initAction()
  {
    $this->loadLayout()->_addBreadcrumb(Mage::helper('reports')->__('Reports'), Mage::helper('reports')->__('Reports'))
            ->_addBreadcrumb(Mage::helper('reports')->__('Donations'), Mage::helper('reports')->__('Donations'));
    return $this;
  }
  
  public function _initReportAction($blocks)
  {
    if (!is_array($blocks)) {
      $blocks = array($blocks);
    }

    $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
    $requestData = $this->_filterDates($requestData, array('from', 'to'));
    $requestData['store_ids'] = $this->getRequest()->getParam('store_ids');
    $params = new Varien_Object();

    foreach ($requestData as $key => $value) {
      if (!empty($value)) {
        $params->setData($key, $value);
      }
    }

    foreach ($blocks as $block) {
      if ($block) {
        $block->setFilterData($params);
      }
    }

    return $this;
  }
    
  public function byorderAction()
  {
    $this->_title($this->__('Reports'))->_title($this->__('Donations'));

    $this->_initAction()
      ->_setActiveMenu('report/donations/byorder')
      ->_addBreadcrumb(Mage::helper('adminhtml')->__('Donations Report'), Mage::helper('adminhtml')->__('Donations Report'));

    $gridBlock = $this->getLayout()->getBlock('adminhtml_report_byorder.grid');
    $filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');

    $this->_initReportAction(array(
      $gridBlock,
      $filterFormBlock
    ));
    $this->renderLayout();
  }  
  
  public function byOrderExportCsvAction()
  {
    $fileName   = 'donations_by_order.csv';
    $grid       = $this->getLayout()->createBlock('donations/adminhtml_report_byorder_grid');
    $this->_initReportAction($grid);
    $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    exit;
  }
  
  public function byOrderExportExcelAction()
  {
    $fileName   = 'donations_by_order.xls';
    $grid       = $this->getLayout()->createBlock('donations/adminhtml_report_byorder_grid');
    $this->_initReportAction($grid);
    $this->_prepareDownloadResponse($fileName, $grid->getExcelFile());
    exit;
  }
  
  public function bymonthAction()
  {
    $this->_title($this->__('Reports'))->_title($this->__('Donations'));
    
    $this->_initAction()
      ->_setActiveMenu('report/donations/bymonth')
      ->_addBreadcrumb(Mage::helper('adminhtml')->__('Donations Report'), Mage::helper('adminhtml')->__('Donations Report'));
      
    $gridBlock = $this->getLayout()->getBlock('adminhtml_report_bymonth.grid');
    $filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');
    
    $this->_initReportAction(array(
      $gridBlock,
      $filterFormBlock
    ));
    
    $this->renderLayout();
  }
  
  public function byMonthExportCsvAction()
  {
    $fileName   = 'donations_by_month.csv';
    $grid       = $this->getLayout()->createBlock('donations/adminhtml_report_bymonth_grid');
    $this->_initReportAction($grid);
    $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    exit;
  }
  
  public function byMonthExportExcelAction()
  {
    $fileName   = 'donations_by_month.xls';
    $grid       = $this->getLayout()->createBlock('donations/adminhtml_report_bymonth_grid');
    $this->_initReportAction($grid);
    $this->_prepareDownloadResponse($fileName, $grid->getExcelFile());
    exit;
  }
  
  protected function _isAllowed()
  {
    //to implement
    return true;
  }
  
  protected function _getSession()
  {
    if (is_null($this->_adminSession)) {
      $this->_adminSession = Mage::getSingleton('admin/session');
    }
    return $this->_adminSession;
  }
}
