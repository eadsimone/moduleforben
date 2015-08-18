<?php

class Lucky_Donations_Model_Mysql4_Report_Byorder_Collection extends Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
{
  public function joinOrders()
  {
    $this->getSelect()
      ->join(array('order_flat'=>$this->getTable('sales/order')),'main_table.order_id = order_flat.entity_id', array('order_flat.created_at','order_flat.store_id','order_flat.increment_id','order_flat.subtotal','order_flat.entity_id'));
    return $this;
  }
}