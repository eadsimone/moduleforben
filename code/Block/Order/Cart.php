<?php

class Lucky_Donations_Block_Order_Cart extends Lucky_Donations_Block_Donation
{ 
  public function getOrderTotal()
  {
    $checkout = Mage::getSingleton('checkout/session');
    $quote = $checkout->getQuote();
    $price = $quote->getSubtotal();
    return $price;
  }
}