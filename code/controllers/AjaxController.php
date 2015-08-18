<?php

class Lucky_Donations_AjaxController extends Mage_Core_Controller_Front_Action
{
  public function donateAction()
  {
    try{
      if( ! Mage::helper('donations')->getCommissionRate()) {
        throw new Exception('Could not get commission rate.');
      }

      $orderId = $this->getRequest()->getParam('order_id');
      if( ! $orderId) {
        throw new Exception('Could not get order id.');
      }

      $charityId = $this->getRequest()->getParam('charity_id');
      if( ! $charityId) {
        throw new Exception('Could not get charity id.');
      }

      $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
      $protectCode = $this->getRequest()->getParam('protect_code');
      if( ! $order->getIncrementId() || $protectCode != Mage::helper('donations')->getOrderProtectCode($order) ) {
        throw new Exception('Could not load order or invalid protect_code.');
      }
      Mage::register('order', $order);

      $donation = Mage::getModel('donations/donation')->makeDonation($order, $charityId);
      $block = $this->getLayout()->getBlockSingleton('donations/order_success');
      $block->setCharityId($charityId);
      $block->setAmount($donation->getAmount());
      $block->setTemplate('donations/ajax_tip.phtml');
      $this->getResponse()->setBody($block->toHtml());
    } catch (Exception $e)
    {
      Mage::logException($e);
      $block = $this->getLayout()->getBlockSingleton('core/template');
      $block->setTemplate('donations/ajax_tip_error.phtml');
      $this->getResponse()->setBody($block->toHtml());
    }
    
    return $this;
  }
}
