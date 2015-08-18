<?php

class Lucky_Donations_Block_Order_Success extends Lucky_Donations_Block_Donation
{

  /**
   * @return float
   */
  protected function getOrderTotal()
  {
    return $this->getOrder()->getSubtotal();
  }

  /**
   * @return Mage_Sales_Model_Order
   */
  public function getOrder()
  {
    if( ! Mage::registry('order')) {
      $order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
      if ($order && $order->getId()) {
        Mage::register('order', $order);
      }
    }
    return Mage::registry('order') ? Mage::registry('order') : new Varien_Object();
  }

  /**
   * @return string
   */
  public function getLastRealOrderId()
  {
    return $this->getOrder()->getIncrementId();
  }

  /**
   * @return string
   */
  public function getCharityName()
  {
    if( ! $this->hasData('charity_name')) {
      $charity = Mage::getModel('donations/charity')->load($this->getData('charity_id'));
      $this->setData('charity_name', $charity->getName());
    }
    return $this->getData('charity_name');
  }

  /**
   * @return string
   */
  public function getDonateNowAction()
  {
    $order = $this->getOrder();
    return $this->getUrl('donations/ajax/donate', array(
        'order_id' => $order->getIncrementId(),
        'protect_code' => Mage::helper('donations')->getOrderProtectCode($order),
        '_secure' => Mage::app()->getRequest()->isSecure(),
    ));
  }

}
