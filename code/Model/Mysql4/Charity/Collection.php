<?php

class Lucky_Donations_Model_Mysql4_Charity_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
  protected function _construct()
  {
    $this->_init('donations/charity');
  }
  
  public function toOptionArray()
  {
    return $this->_toOptionArray('charity_id', 'name');
  }
}
