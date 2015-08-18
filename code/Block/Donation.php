<?php

class Lucky_Donations_Block_Donation extends Mage_Core_Block_Template
{
  protected $_donationHelper;
  
  protected function getDonationHelper()
  {
    return Mage::helper('donations');
  }
  
  public function isEnabled()
  {
    if(!$this->getCommissionRate() > 0)
      return false;
      
    if($this->hasCharities())
    {
      return true;
    }
    return false;
  }
  
  public function getCommissionRate()
  {
    $value = $this->getDonationHelper()->getCommissionRate();
    return (float)$value;
  }
  
  public function getCommissionRateFormatted()
  {
    return $this->getCommissionRate().'%';
  }
  
  protected function hasCharities()
  {
    if(count($this->getCharities()))
      return true;
    return false;
  }
  
  public function getCharities()
  {
    if(!$this->getData('charities'))
    {
      $this->setData('charities', Mage::helper('donations')->getFrontendCharities()->toOptionArray());
    }
    return $this->getData('charities');
  }
  
  public function getCalculatedAmount()
  {
    $amount = $this->getCommissionRate() * 0.01 * $this->getOrderTotal();
    return (float) $amount;
  }
  
  public function getCalculatedAmountFormatted()
  {
    return $this->getAmountFormatted($this->getCalculatedAmount());
  }
  
  public function getAmountFormatted($value)
  {
    return Mage::helper('core')->formatPrice($value);
  }
}
