<?php

class Lucky_Donations_Model_Donation extends Mage_Core_Model_Abstract
{
  const CACHE_TAG = 'donations';

  protected $_idFieldName = 'donation_id';
  protected $_cacheTag = self::CACHE_TAG;

  protected function _construct()
  {
    $this->_init('donations/donation');
  }

  public function makeDonation(Mage_Sales_Model_Order $order, $charityId)
  {
    $amount = Mage::helper('donations')->getCommissionRate() * 0.01 * $order->getSubtotal();

    $this->setOrderId($order->getId())
      ->setAmount((float)$amount)
      ->setCharityId($charityId)
      ->save();
    
    return $this;
  }

  protected function _beforeSave()
  {
    // Prevent duplicate donations
    $toDelete = Mage::getModel('donations/donation')->load($this->getOrderId(),'order_id');
    if($toDelete->getId() && $toDelete->getId() != $this->getId()) {
      $toDelete->delete();
    }
    parent::_beforeSave();
    return $this;
  }

}
