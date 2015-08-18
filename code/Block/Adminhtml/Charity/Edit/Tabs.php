<?php

class Lucky_Donations_Block_Adminhtml_Charity_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('charity_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('donations')->__('Manage Charity'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('donations')->__('General Info'),
            'title'     => Mage::helper('donations')->__('General Info'),
            'content'   => $this->getLayout()->createBlock('donations/adminhtml_charity_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}