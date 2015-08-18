<?php

class Lucky_Donations_Adminhtml_CharityController extends Mage_Adminhtml_Controller_Action
{
  public function indexAction()
  {
    $this->loadLayout();

    $this->_setActiveMenu('cms/charity');
    $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Charities'), Mage::helper('adminhtml')->__('Charities'));
    $this->_addContent($this->getLayout()->createBlock('donations/adminhtml_charity'));

    $this->renderLayout();
  }

  public function editAction()
  {
    $this->loadLayout();

    $this->_setActiveMenu('cms/charity');
    $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Charities'), Mage::helper('adminhtml')->__('Charities'));

    $id = $this->getRequest()->getParam('id');
    $model = Mage::getModel('donations/charity');

    if ($id) {
      $model->load($id);
      if (! $model->getCharityId()) {
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error, there is no charity with ID: '. $id));
        $this->_redirect('*/*');
        return;
      }
    }

    $this->_addContent($this->getLayout()->createBlock('donations/adminhtml_charity_edit'))
        ->_addLeft($this->getLayout()->createBlock('donations/adminhtml_charity_edit_tabs'));
    $this->renderLayout();
  }

  public function newAction()
  {
    $this->editAction();
  }

  private function validationFile($allname)
  {

    $onlynamearray=explode(".",$allname);
    $onlyname = $onlynamearray[0];

    if (!preg_match("/^[a-zA-Z0-9_-]*$/",$onlyname)) {
      throw new Exception('Please use only letters (a-z or A-Z) or numbers (0-9) or underscore(_) or dash(-) only in this field. No spaces or other characters are allowed.');
    }
    return true;
  }

  public function saveAction()
  {
    if ($this->getRequest()->getPost()) {
      try {
        $model = Mage::getModel('donations/charity');
        Mage::dispatchEvent('adminhtml_controller_donations_prepare_save', array('request' => $this->getRequest()));
        $data = $this->getRequest()->getPost();
        Mage::getSingleton('adminhtml/session')->setPageData($data);

        /*for image */

        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
          try {
            $this->validationFile($_FILES['image']['name']);

            $uploader = new Varien_File_Uploader('image');

            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything

            $uploader->setAllowRenameFiles(false);

            $uploader->setFilesDispersion(false);

            $path = Mage::getBaseDir('media').'/';

            $uploader->save($path, $_FILES['image']['name']);

            $data['image'] = $_FILES['image']['name'];

          } catch (Exception $e) {

            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);

            $this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));

            return;
          }
        }
        else if((isset($data['image']['delete']) && $data['image']['delete'] == 1)){
          $data['image'] = '';

        }
        /*end for image */

        if ($id = $this->getRequest()->getParam('id')) {
          $model->load($id);
          $model->setId($id);
          if ($id != $model->getCharityId()) {
            Mage::throwException(Mage::helper('donations')->__('Error, there is no charity with ID: '. $id));
          }
        }
        $validateResult = $model->validateData(new Varien_Object($data));
        if ($validateResult !== true) {
          foreach($validateResult as $errorMessage) {
            $this->_getSession()->addError($errorMessage);
          }
          $this->_getSession()->setPageData($data);
          $this->_redirect('*/*/edit', array('id'=>$model->getId()));
          return;
        }

        $model->loadPost($data);

        Mage::getSingleton('adminhtml/session')->setPageData($model->getData());

        $model->save();

        /*----clean cache--------*/
        $allTypes = Mage::app()->useCache();
        foreach($allTypes as $type => $blah) {
          Mage::app()->getCacheInstance()->cleanType($type);
        }


        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('donations')->__('Charity '.$data['name'].' has been successfully saved.'));
        Mage::getSingleton('adminhtml/session')->setPageData(false);

        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        $this->_getSession()->addError($e->getMessage());
        Mage::logException($e);
        $this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));
        return;
      }
    }
    $this->_redirect('*/*/');
  }

  public function deleteAction()
  {
    if( $this->getRequest()->getParam('id') > 0 ) {
      try {
        $model = Mage::getModel('donations/charity');
        /* @var $model Mage_Rating_Model_Rating */
        $model->setId($this->getRequest()->getParam('id'))
            ->delete();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Charity has been deleted.'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }

  protected function _isAllowed()
  {
    return Mage::getSingleton('admin/session')->isAllowed('cms/charity');
  }

}
