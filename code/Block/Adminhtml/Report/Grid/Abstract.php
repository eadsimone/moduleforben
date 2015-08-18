<?php

abstract class Lucky_Donations_Block_Adminhtml_Report_Grid_Abstract extends Mage_Adminhtml_Block_Report_Grid
{
  protected $_defaultFilter = array(
    'report_from' => '',
    'report_to'   => ''
  );
  
  
  public function __construct()
  {
    parent::__construct(); 
    $this->setId($this->_gridId);
    $this->setDefaultSort($this->_defaultSort);
    $this->setDefaultDir($this->_defaultSortDir);
    $this->setTemplate('widget/grid.phtml');
    $this->setFilterVisibility(false);
    $this->setPagerVisibility(false);
    $this->setCountTotals(true);
    
    $this->prepareFilters();
  }
  
  public function getCountTotals()
  {
    return true;
  }
  
  public function getCurrentCurrencyCode()
  {
    return Mage::app()->getStore()->getBaseCurrencyCode();
  }
  
  protected function prepareFilters()
  {
    $filter = $this->getParam($this->getVarNameFilter(), null);
    
    if (is_null($filter)) {
      $filter = $this->_defaultFilter;
    }
    if (is_string($filter)) {
      $data = array();
      $filter = base64_decode($filter);
      parse_str(urldecode($filter), $data);

      if (!isset($data['report_from'])) {
        // getting all reports from 2001 year
        $date = new Zend_Date(mktime(0,0,0,1,1,2001));
        $data['report_from'] = $date->toString($this->getLocale()->getDateFormat('short'));
      }

      if (!isset($data['report_to'])) {
        // getting all reports from 2001 year
        $date = new Zend_Date();
        $data['report_to'] = $date->toString($this->getLocale()->getDateFormat('short'));
      }
      
      $this->_setFilterValues($data);
    } else if ($filter && is_array($filter)) {
      $this->_setFilterValues($filter);
    } else if(0 !== sizeof($this->_defaultFilter)) {
      $this->_setFilterValues($this->_defaultFilter);
    }
  }
  
}