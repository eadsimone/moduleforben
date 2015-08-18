<?php

class Lucky_Donations_Block_Adminhtml_Report_Byorder_Grid extends Lucky_Donations_Block_Adminhtml_Report_Grid_Abstract
{
  
  protected $_gridId = 'donationByorderReportGrid';
  protected $_defaultSort = 'created_at';
  protected $_defaultSortDir = 'desc';
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function getCollection()
  {
    if (is_null($this->_collection)) {
      $this->setCollection(Mage::getModel('donations/report_byorder_collection'));
    }
    return $this->_collection;
  }
  
  public function _prepareCollection()
  {
    $resourceCollection = Mage::getResourceModel('donations/report_byorder_collection')
      ->joinOrders()
      ->joinCharity()
      ->setOrder('charity.is_system','DESC')
      ->setOrder('charity_name','ASC');
    
    if ($this->getFilter('report_from') && $this->getFilter('report_to')) {
      try {
        $from = $this->getLocale()->date($this->getFilter('report_from'), $this->getLocale()->getDateFormat('short'), null, false);
        $to   = $this->getLocale()->date($this->getFilter('report_to'), $this->getLocale()->getDateFormat('short'), null, false);
        $this->getCollection()->setInterval($from,$to);
      }
      catch (Exception $e) {
        $this->_errors[] = Mage::helper('reports')->__('Invalid date specified.');
      }
    }

    $storeIds = $this->getRequest()->getParam('store_ids',null);

    if(!is_null($storeIds))
    {
      $resourceCollection->addStoreFilter($storeIds);
    }
    
    $this->getCollection()->setResourceCollection($resourceCollection);
    
    // workaround for getting totals
    $this->setTotals($this->getCollection()->load()->getTotals());
    //return parent::_prepareCollection();
  }
  
  protected function _prepareColumns()
  {
    $currencyCode = $this->getCurrentCurrencyCode();
    
    $this->addColumn('created_at', array(
      'header'    => Mage::helper('donations')->__('Date'),
      'index'     => 'created_at',
      'width'     => '200px',
      'sortable'  => false,
      'totals_label' => 'Total'
    ));
    
    $this->addColumn('increment_id',array(
      'header'  => Mage::helper('donations')->__('Order #'),
      'index'   => 'increment_id',
      'sortable'=> 'true'
    ));
    
    $this->addColumn('charity_name',array(
      'header'  => Mage::helper('donations')->__('Charity Name'),
      'index'   => 'charity_name',
      'sortable'=> 'false'
    ));
    
    $this->addColumn('subtotal',array(
      'header'        => Mage::helper('donations')->__('Order Subtotal'),
      'index'         => 'subtotal',
      'sortable'      => 'false',
      'format'        => 'price',
      'type'          => 'currency',
      'currency_code' => $currencyCode,
      'total'         => 'sum'
    ));
    
    $this->addColumn('amount',array(
      'header'        => Mage::helper('donations')->__('Donation Amount'),
      'index'         => 'amount',
      'sortable'      => 'false',
      'format'        => 'price',
      'type'          => 'currency',
      'currency_code' => $currencyCode,
      'total'         => 'sum'
    ));
    
    $this->addExportType('*/*/byOrderExportCsv', Mage::helper('adminhtml')->__('CSV'));
    $this->addExportType('*/*/byOrderExportExcel', Mage::helper('adminhtml')->__('Excel'));

    return parent::_prepareColumns(); 
  }
  
  public function getRowUrl($row)
  {
    return $this->getUrl('*/sales_order/view', array('order_id' => $row->getEntityId()));
  }
  
}
