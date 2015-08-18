<?php

class Lucky_Donations_Model_Mysql4_Charity extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('donations/charity', 'charity_id');
    }
}