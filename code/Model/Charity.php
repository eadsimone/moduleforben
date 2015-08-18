<?php

class Lucky_Donations_Model_Charity extends Mage_Core_Model_Abstract
{
  protected function _construct()
  {
    $this->_init('donations/charity');
    $this->setIdFieldName('charity_id');
  }

  protected function _beforeSave()
  {
    // image saving logic
    parent::_beforeSave();
  }
  
  public function validateData(Varien_Object $object)
  {
    return true;
  }
  
  public function loadPost($data)
  {
    $this->addData($data);
    return $this;
  }
  
}
