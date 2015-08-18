<?php

class Lucky_Donations_Model_Report_Bymonth_Item extends Mage_Core_Model_Abstract
{
  public function fromCharityData($data)
  {
    $totalAmount = array();
    $totalByDateAmount = 0;
    $months = Mage::helper('donations')->getLastMonths();

    foreach($months as $number => $name)
    {
      $totalAmount[$number] = 0;
    }
    
    foreach($data as $donation)
    {
      $month = $this->getMonthNumber($donation);
      $totalAmount[$month] += $donation->getAmount();
      if($donation->getFrom() && $donation->getTo())
      {
        if(Mage::helper('donations')->isDateBetween($donation->getCreatedAt(),$donation->getFrom(),$donation->getTo())){
          $totalByDateAmount += $donation->getAmount();
        }
      }
    }
    
    foreach($totalAmount as $monthNumber=>$amount)
    {
      $this->setData('month_'.$monthNumber,$amount);
    }
    
    $this->setData('by_date',$totalByDateAmount);
    $this->setData('charity_name',$donation->getName());
    
    return $this;
  }
  
  protected function getMonthNumber($donation)
  {
    $date = new Zend_Date($donation->getCreatedAt());
    return $date->toValue(Zend_Date::MONTH_SHORT);
  }
}