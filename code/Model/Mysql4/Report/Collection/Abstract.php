<?php

abstract class Lucky_Donations_Model_Mysql4_Report_Collection_Abstract extends Mage_Core_Model_Mysql4_Collection_Abstract
{

  protected function _construct()
  {
    $this->_init('donations/donation');
  }

  /**
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function joinOrders()
  {
    $this->getSelect()
      ->join(array('order_flat'=>$this->getTable('sales/order')),'main_table.order_id = order_flat.entity_id', array('order_flat.created_at','order_flat.store_id'));
    return $this;
  }

  /**
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function joinCharity()
  {
    $this->getSelect()
      ->join(array('charity'=>$this->getTable('donations/charity')),'main_table.charity_id = charity.charity_id',array('charity_name' => 'name'));
      
    return $this;
  }

  /**
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function groupByCharity() {
      $this->getSelect()
        ->group('charity_id');
      
      return $this;
  }

  /**
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function filterOrders()
  {
    $this->getSelect()
      ->where('order_flat.state != ?', Mage_Sales_Model_Order::STATE_CANCELED);

    return $this;
  }

  /**
   * @param $from
   * @param $to
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function setDateRange($from, $to)
  {
//    $this->_reset()
//        ->joinCharity()
//        ->joinOrders()
//        ->filterOrders()
    $this
        ->addFieldToFilter('order_flat.created_at', array('from' => $from, 'to' => $to, 'date' => true));
    
    return $this;
  }

  /**
   * @param $storeIds
   * @return Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
   */
  public function setStoreIds($storeIds)
  {
    $this->addFieldToFilter('order_flat.store_id',array('in'=>$storeIds));
    return $this;
  }

}
