<?php

class Lucky_Donations_Helper_Data extends Mage_Core_Helper_Data
{
    const XML_PATH_DONATIONS = 'donations/general/';
    const LAST_MONTHS_COUNT = 6;

    protected $_months;

    public function getCommissionRate()
    {
        return Mage::getStoreConfig(self::XML_PATH_DONATIONS.'commission');
    }

    public function getNoDonationCharityId()
    {
        return Mage::getStoreConfig(self::XML_PATH_DONATIONS.'nodonationcharityid');
    }

    public function getLastMonths()
    {
        $currentDate = new Zend_Date(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())));
        $currentMonth = $currentDate->toValue(Zend_Date::MONTH_SHORT);
        $currentYear = $currentDate->toValue(Zend_Date::YEAR);

        $lastMonths = array();
        for($i = 0; $i < self::LAST_MONTHS_COUNT; $i++)
        {
            $number = $currentMonth - $i;
            $year = $currentYear;
            if($number <= 0)
            {
                $year -= 1;
                $number += 12;
            }
            $lastMonths[$number] = $this->getMonthName($number,$year);
        }

        return $lastMonths;
    }

    public function getMonths($suffix = null)
    {
        if(!$this->_months)
        {
            $this->_months = array();
            $months = Mage::app()->getLocale()->getTranslationList('months');
            foreach (array_values($months['format']['wide']) as $code => $name) {
                $this->_months[$code+1] = $name;
            }
        }

        return $this->_months;
    }

    public function getMonthName($i, $suffix = null)
    {
        $months = $this->getMonths();
        $month = $months[$i];

        if($suffix)
            $month .= ' '.$suffix;

        return $month;
    }

    public function isDateBetween($target,$from,$to)
    {
        if(is_null($from) && is_null($to))
        {
            return true;
        }

        $target = new Zend_Date($target);
        $from = new Zend_Date($from);
        $to = new Zend_Date($to);

        if($target->isEarlier($to) && $target->isLater($from))
        {
            return true;
        }
        return false;
    }

    public function getOrderProtectCode(Mage_Sales_Model_Order $order) {
        return sha1($order->getId().$order->getCustomerEmail());
    }

    /**
     * @return Lucky_Donations_Model_Mysql4_Charity_Collection|Lucky_Donations_Model_Charity[]
     */

    public function getFrontendCharities()
    {
        $collection = Mage::getResourceModel('donations/charity_collection');
        $collection
            ->addFieldToFilter('is_system', array('eq' => 0))
            ->addFieldToFilter('name', array('nlike' => '*%'))
            ->setOrder('name', 'asc');
        return $collection;
    }

    /**
     * @return string with total amount of current donations
     */
    public function getValueTotalCharity() {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT sum(donations_donations.amount) as totaldonate FROM donations_donations INNER JOIN donations_charity ON donations_donations.charity_id=donations_charity.charity_id WHERE donations_charity.is_system=0";
        $totaldonations = $read->query($query);

        if (isset($totaldonations)) {
            foreach ($totaldonations as $algo){
                $finalalgo[]=$algo;
            }
            return $finalalgo{0}['totaldonate'];
        }else{
            return '--.--';
        }
    }

    /**
     * this function cache the total donations amount
     * @return string in array  with total amount of current donations
     */
    public function getTotalCharity() {
        $cache = Mage::app()->getCache();
        $cacheId = 'total_donations';

        if (($data = $cache->load($cacheId)) === false) {
            $data = $this->getValueTotalCharity();
            $cache->save($data, $cacheId, array($cacheId));
        }

        return str_split($data);
    }

    public function getCharityByDonate() {

        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        $query = "SELECT donations_donations.charity_id, donations_charity.name, donations_charity.image, donations_charity.description, donations_charity.website, donations_charity.is_system,  sum(donations_donations.amount) as totaldonate FROM donations_donations RIGHT JOIN donations_charity ON donations_donations.charity_id=donations_charity.charity_id WHERE donations_charity.is_system=0 GROUP BY donations_charity.charity_id   ORDER BY totaldonate DESC";

        $charitygroupbydonate = $read->query($query);

        return $charitygroupbydonate;
    }

}
