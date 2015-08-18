<?php

class Lucky_Donations_Model_Report_Byorder_Collection extends Lucky_Donations_Model_Report_Collection_Abstract
{
  protected $_totalsIdx = array('subtotal','amount');
  protected function _prepareFromResourceData()
  {
    // we are making real data for grid HERE
    if (count($this->_items) == 0) {
      foreach ($this->_resourceCollection as $item) {
        if(Mage::helper('donations')->isDateBetween($item->getCreatedAt(),$this->_from,$this->_to))
        {
          $this->_items[] = $item;
          foreach($item->getData() as $idx => $value)
          {
            if(in_array($idx,$this->_totalsIdx))
            {
              $this->getTotals()->setData($idx,$this->getTotals()->getData($idx) + $value);
            }
          }
        }
      }
    }
    
    return $this;
  } 
}