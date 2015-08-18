<?php

class Lucky_Donations_Block_Adminhtml_Charity_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('charity_form', array(
            'legend'=>Mage::helper('donations')->__('Information')
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('donations')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
        ));

        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('donations')->__('Image'),
            'title'     => Mage::helper('donations')->__('Image'),
            'required'  => false
            //'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('website', 'text', array(
            'name'      => 'website',
            'label'     => Mage::helper('donations')->__('Website'),
            'required'  => false,
        ));

        $fieldset->addField('description', 'editor', array(
            'name'      => 'description',
            'label'     => Mage::helper('donations')->__('Description'),
            'title'     => Mage::helper('donations')->__('Description'),
            'style'     => 'height:36em; width: 600px;',
            'required'  => false,
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig()
        ));

        Mage::dispatchEvent('donations_adminhtml_edit_prepare_form', array('block'=>$this, 'form'=>$form));

        if (Mage::registry('charity_data')) {
            $form->setValues(Mage::registry('charity_data')->getData());
        }

        return parent::_prepareForm();
    }
}