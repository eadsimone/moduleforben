<?php

class Lucky_Donations_Block_Adminhtml_Report_Bymonth_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  
  protected $_gridId = 'donationBymonthReportGrid';
  protected $_defaultSort = 'charity_name';
  protected $_defaultSortDir = 'asc';

  protected $months = NULL;

  public function __construct()
  {
    $from = Mage::app()->getLocale()->date(null, null, null, false)->subMonth(6);
    $this->_defaultFilters = array(
        'report_from' => $from->toString(Mage::app()->getLocale()->getDateFormat('short')),
        'report_to' => '',
    );
    $this->setFilterVisibility(false);
    $this->setPagerVisibility(false);
    parent::__construct(); 
  }
  
  /**
   * Loads all items in the grid.
   * @todo Use a stored procedure instead.
   */
  protected function _prepareCollection()
  {
    $report = new Varien_Data_Collection();
      
    $filter = $this->getDateRange();
    $charities = Mage::getResourceModel('donations/charity_collection');
    
    foreach($charities as $charity) { // each charity is a row in the grid
        $collection = Mage::getResourceModel('donations/report_bymonth_collection'); /* @var $collection Lucky_Donations_Model_Mysql4_Report_Bymonth_Collection */
        $collection
          ->joinCharity()
          ->joinOrders()
          ->filterOrders()
          ->setDateRange($filter['report_from'], $filter['report_to'])
          ->addFieldToFilter('charity.charity_id', $charity->getCharityId())
          ->setOrder('charity.is_system','DESC')
          ->setOrder('charity_name','ASC')
          ->groupByCharity();
        
        foreach($this->getMonths() as $key => $label) {
          $collection->addAmountByMonth($key);
        }
        
        $firstItem = $collection->getFirstItem();
        if($collection->count() && !$report->getItemById($firstItem->getId())) {
            $report->addItem($firstItem); // add the row
        }
    }
    $this->setCollection($report);

    return $this;
  }
    
  protected function _prepareColumns()
  {
    $this->addColumn('charity_name', array(
      'header'    => Mage::helper('donations')->__('Organization'),
      'index'     => 'charity_name',
      'width'     => '200',
      'sortable'  => false,
      'totals_label' => 'Total'
    ));

    $currencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
    foreach($this->getMonths() as $monthNumber => $columnName)
    {
      $this->addColumn('month_'.$monthNumber, array(
        'header'        => $columnName,
        'index'         => 'month_'.$monthNumber,
        'sortable'      => false,
        'format'        => 'price',
        'type'          => 'currency',
        'currency_code' => $currencyCode,
        'total'         => 'sum'
      ));
    }

    $this->addExportType('*/*/bymonthExportCsv', Mage::helper('adminhtml')->__('CSV'));
    $this->addExportType('*/*/bymonthExportExcel', Mage::helper('adminhtml')->__('Excel'));

    return parent::_prepareColumns(); 
  }

  public function getDateRange()
  {
    $filter = $this->getParam($this->getVarNameFilter(), null);

    if (is_null($filter)) {
      $filter = $this->_defaultFilter;
    }

    $data = array();
    if (is_string($filter)) {
      $filter = base64_decode($filter);
      parse_str(urldecode($filter), $data);
      $filter = $data;
    }
    if ($filter) {
      if(isset($filter['report_from'])) {
        $data['report_from'] = Mage::app()->getLocale()->date($filter['report_from'], Mage::app()->getLocale()->getDateFormat('short'), null, false);
      }
      if(isset($filter['report_to'])) {
        $data['report_to'] = Mage::app()->getLocale()->date($filter['report_to'], Mage::app()->getLocale()->getDateFormat('short'), null, false);
      }
    } else {
      $data = $this->_defaultFilters;
    }
    if (!isset($data['report_from'])) {
      // getting all reports from 2001 year
      $data['report_from'] = new Zend_Date(mktime(0,0,0,1,1,2001));
    }
    if (!isset($data['report_to'])) {
      $data['report_to'] = new Zend_Date;
      $data['report_to']->addDay(1); // tomorrow at 0am; otherwise today's donations will not be displayed
    }
    //var_dump("{$data['report_from']} {$data['report_to']}");
    return $data;
  }

  public function getMonths()
  {
    if($this->months === NULL) {
      $data = $this->getDateRange();

      $from = clone $data['report_from'];
      $from->setDay(1);
      $to = clone $data['report_to'];
      $to->setDay(1);
      $date = clone $to; /* @var $date Zend_Date */
      $months = array();
      $i = 0;
      while( ! $date->isEarlier($from)) {
        $months[$date->toString('yyyy-MM')] = $date->toString('MMMM yyyy');
        $date->subMonth(1);
        if(++$i > 12) break;
      }
      $this->months = $months;
    }
    return $this->months;
  }
  
}
