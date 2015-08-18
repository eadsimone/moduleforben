<?php

/**
 * Displays information about a single charity's raised donations
 *
 * @author Gabriel Somoza <gabriel@usestrategery.com>
 */
class Lucky_Donations_Block_Cms_Summary_Charity extends Mage_Core_Block_Template {

    /** @var Lucky_Donations_Model_Charity */
    protected $_model;
    protected $_template = 'donations/cms/summary/charity.phtml';

    /**
     * Returns the model. If the model is not set it will try to load it
     * from the 'charity_id' attribute.
     *
     * @throws Lucky_Donations_Exception
     * @return Lucky_Donations_Model_Charity
     */
    public function getModel() {
        if(!isset($this->_model)) {
            if(!$this->hasData('charity_id')) {
                throw new Lucky_Donations_Exception('No Charity ID specified');
            }
            $this->_model = Mage::getModel('donations/charity')->load($this->getData('charity_id'));
        }
        return $this->_model;
    }
    
    /**
     * Tries to return the charity id using the model or falling back to the
     * 'charity_id' attribute.
     * 
     * @return int
     */
    public function getCharityId() {
        return $this->_model ? $this->_model->getId() : $this->getData('charity_id');
    }
    
    /**
     * Accessor to manually set the model
     * @param Lucky_Donations_Model_Charity $charity
     * @return \Lucky_Donations_Model_Charity
     */
    public function setModel(Lucky_Donations_Model_Charity $charity) {
        return $this->_model = $charity;
    }
    
    /**
     * Uses reporting functionality to return the total amount donated to this charity.
     * @return Zend_Currency
     */
    public function getTotalDonated() {
        $collection = Mage::getResourceModel('donations/report_bymonth_collection'); /* @var $collection Lucky_Donations_Model_Mysql4_Report_Bymonth_Collection */
        $collection
            ->joinCharity()
            ->joinOrders()
            ->filterOrders()
            ->addFieldToFilter('charity.charity_id', $this->getCharityId())
            ->groupByCharity();
        $collection->getSelect()->columns(new Zend_Db_Expr('SUM(main_table.amount) AS `total_donated`'));
        $total = $collection->getFirstItem()->getTotalDonated();
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->setFormat(array('precision' => 0))->toCurrency(is_numeric($total) ? $total : 0);
    }
    
    /**
     * Returns the charity's name
     * @return String 
     */
    public function getName() {
        return $this->getModel()->getName();
    }

    /**
     * Retrieves and parses the charity's Website URL.
     *
     * @return Zend_Uri_Http The parsed URL
     */
    public function getWebsiteUri() {
        if($uri = $this->getModel()->getWebsite()) {
            try {
                return Zend_Uri_Http::fromString($uri);
            } catch(Lucky_Donations_Exception $e) {
                return '';
            }
        } else {
            return false;
        }
    }
}

