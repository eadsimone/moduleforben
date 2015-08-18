<?php

class Lucky_Donations_Model_Report_Bymonth_Collection extends Lucky_Donations_Model_Report_Collection_Abstract
{
  protected function getItemModel()
  {
    return Mage::getModel('donations/report_bymonth_item');
  }
  
  protected function _prepareFromResourceData()
  {
    // we are making real data for grid HERE
    if (count($this->_items) == 0) {
      $i = 0;
      $charities = array();
      
      foreach ($this->_resourceCollection as $item) {
        $item->setData('from',$this->_from);
        $item->setData('to',$this->_to);
        $charities[$item->getData('charity_id')][] = $item;
      }
      foreach($charities as $id => $charity)
      {
        $item = $this->getItemModel()->fromCharityData($charity);
        foreach($item->getData() as $idx => $value)
        {
          $this->getTotals()->setData($idx,$this->getTotals()->getData($idx) + $value);
        }
        $this->_items[] = $item;
      }
    }
    
    return $this;
  }
}