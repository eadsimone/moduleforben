<?php

abstract class Lucky_Donations_Model_Report_Collection_Abstract extends Varien_Data_Collection
{
  protected $_resourceCollection  = array();
  protected $_from = null;
  protected $_to = null;
  protected $_totals = null;
  
  public function setResourceCollection($collection)
  {
    $this->_resourceCollection = $collection;
    return $this;
  }
  
  public function load($printQuery = false, $logQuery = false)
  {
    if ($this->isLoaded()) {
      return $this;
    }
    
    parent::load($printQuery, $logQuery);
    $this->_setIsLoaded();

    $this->_prepareFromResourceData();
    return $this;
  }
  
  protected function _prepareFromResourceData()
  {
    return $this;
  }
  
  public function setInterval(Zend_Date $from,Zend_Date $to)
  {
    $this->_from = $from->toString('yyyy-MM-dd 00:00:00');
    $this->_to = $to->toString('yyyy-MM-dd 23:59:59');
  }
  
  public function getTotals()
  {
    if(is_null($this->_totals))
    {
      $this->_totals = new Varien_Object();
    }
    return $this->_totals;
  }

}