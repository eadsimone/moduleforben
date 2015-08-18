<?php

/**
 * Displays donation totals for all charities in the system
 *
 * @author Gabriel Somoza <gabriel@usestrategery.com>
 */
class Lucky_Donations_Block_Cms_Summary extends Mage_Core_Block_Template
{
    protected $_template = 'donations/cms/summary.phtml';

    public function _prepareLayout()
    {
        $this->setData('cache_lifetime', 86400);
        $this->setData('cache_tags', array(Lucky_Donations_Model_Donation::CACHE_TAG)); // Cache will be cleaned every time a donation is made
        $this->setData('cache_key', 'donations_cms_summary_'.Mage::app()->getStore()->getId());
    }

    /**
     * @return Array An array of Lucky_CmsDonations_Block_Summary_Charity blocks. 
     */
    public function getCharities() {
        $collection = array();
        $i = 0;
        foreach(Mage::helper('donations')->getFrontendCharities() as $charity) {
            $block = $this->getLayout()->createBlock('donations/cms_summary_charity');
            $block->setModel($charity);

            $block->setNameByDonate($charity['name']);
            $block->setImageByDonate($charity['image']);
            $block->setDescriptionByDonate($charity['description']);
            $block->setWebsiteByDonate($charity['website']);
            $collection[] = $block;
        }
        return $collection;
    }

    /**
     * @return Array of charity group by amount in Lucky_CmsDonations_Block_Summary_Charity blocks.
     */
    public function getCharitiesByAmount() {
        $collection = array();
        $i = 0;

        $arrayOfCharities =  Mage::helper('donations')->getCharityByDonate();

        foreach( $arrayOfCharities as $charity) {

            $block = $this->getLayout()->createBlock('donations/cms_summary_charity');
            //Add alternating 'left' / 'right' classes to style (and position) charities properly
            $block->setCssPosition(($i++ % 2 == 0) ? 'left' : 'right');

            $block->setNameByDonate($charity['name']);
            $block->setImageByDonate($charity['image']);
            $block->setDescriptionByDonate($charity['description']);
            $block->setWebsiteByDonate($charity['website']);
            If( ($i>8)||(empty($charity['totaldonate']))){
                $block->setTotalDonateByDonate('VISIT');
            }else{
                $block->setTotalDonateByDonate($charity['totaldonate']);
            }


            $collection[] = $block;
        }
        return $collection;
    }
}

