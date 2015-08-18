<?php

class Lucky_Donations_Model_Mysql4_Report_Bymonth_Collection extends Lucky_Donations_Model_Mysql4_Report_Collection_Abstract
{

  /**
   * @param $key
   * @return Lucky_Donations_Model_Mysql4_Report_Bymonth_Collection
   */
  public function addAmountByMonth($key)
  {
    list($year, $month) = explode('-', $key);
    $this->getSelect()->columns(new Zend_Db_Expr(
      "SUM(CASE WHEN (YEAR(order_flat.created_at) = $year AND MONTH(order_flat.created_at) = $month) THEN main_table.amount ELSE 0 END) as `month_$year-$month`"
    ));
    return $this;
  }

}
