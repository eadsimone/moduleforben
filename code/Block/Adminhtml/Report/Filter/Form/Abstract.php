<?php

abstract class Lucky_Donations_Block_Adminhtml_Report_Filter_Form_Abstract extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $actionUrl = $this->getUrl('*/*/*');
    $form = new Varien_Data_Form(
      array('id' => 'filter_form', 'action' => $actionUrl, 'method' => 'get')
    );
    $htmlIdPrefix = 'donations_report_';
    $form->setHtmlIdPrefix($htmlIdPrefix);
    $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('reports')->__('Filter')));

    $dateFormat = Mage::app()->getLocale()->getDateFormat('short');

    $fieldset->addField('store_ids', 'hidden', array(
      'name'  => 'store_ids'
    ));

    $fieldset->addField('report_from', 'date', array(
      'name'      => 'report_from',
      'format'    => $dateFormat,
      'image'     => $this->getSkinUrl('images/grid-cal.gif'),
      'label'     => Mage::helper('reports')->__('From'),
      'title'     => Mage::helper('reports')->__('From'),
      'required'  => false
    ));

    $fieldset->addField('report_to', 'date', array(
      'name'      => 'report_to',
      'format'    => $dateFormat,
      'image'     => $this->getSkinUrl('images/grid-cal.gif'),
      'label'     => Mage::helper('reports')->__('To'),
      'title'     => Mage::helper('reports')->__('To'),
      'required'  => false
    ));

    $form->setUseContainer(true);
    $this->setForm($form);

    return parent::_prepareForm();
  }
    
  protected function _initFormValues()
  {
    $this->getForm()->addValues($this->getFilterData()->getData());
    return parent::_initFormValues();
  }
}