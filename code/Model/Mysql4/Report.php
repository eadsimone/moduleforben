<?php

class Lucky_Donations_Model_Mysql4_Report extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
    
    }

    public function init($table, $field = 'id')
    {
      $this->_init($table, $field);
      return $this;
    }
}