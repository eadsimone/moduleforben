<?php

class Lucky_Donations_Model_Observer
{

    /**
     * @param $observer
     */
    public function addNoDonation($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $amount = Mage::helper('donations')->getCommissionRate() * 0.01 * $order->getSubtotal();
        $charityId = Mage::helper('donations')->getNoDonationCharityId();

        if ($charityId) {
            $charity = Mage::getModel('donations/charity')->load($charityId);
            if ($charity->getId()) {
                $donations = Mage::getModel('donations/donation');
                $donations->setOrderId($order->getId())
                          ->setAmount($amount)
                          ->setCharityId($charityId)
                          ->save();
            }
        }
    }

}
