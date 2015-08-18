<?php

class Lucky_Donations_Block_Adminhtml_Charity_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('charityGrid');
        $this->setDefaultSort('charity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('donations/charity')->getCollection()
          ->addFieldToFilter('charity_id',array('neq'=>Mage::helper('donations')->getNoDonationCharityId())));
        return parent::_prepareCollection();
    }
    
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    protected function _prepareColumns()
    {
      $store = $this->_getStore();

        $this->addColumn('charity_id', array(
            'header'    => Mage::helper('donations')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'charity_id',
            'type'      => 'number',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('donations')->__('Name'),
            'align'     => 'left',
            'index'     => 'name',
        ));
        
        $this->addColumn('website', array(
            'header'    => Mage::helper('donations')->__('Website'),
            'align'     => 'left',
            'index'     => 'website',
        ));
        
        Mage::dispatchEvent('donations_adminhtml_grid_prepare_columns', array('block'=>$this));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
