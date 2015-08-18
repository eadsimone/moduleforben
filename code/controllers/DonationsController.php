<?php

class Lucky_Donations_DonationsController extends Mage_Core_Controller_Front_Action {

    const XML_PATH_EMAIL_RECIPIENT  = 'donations_cms/application/recipient';
    const XML_PATH_EMAIL_SENDER     = 'donations_cms/application/sender';
    const XML_PATH_EMAIL_TEMPLATE   = 'donations_cms/application/email_template';
    const XML_PATH_MESSAGE_SUCCESS   = 'donations_cms/application/message_success';
    const XML_PATH_MESSAGE_ERROR   = 'donations_cms/application/message_error';
    
    public function submitOrganizationAction() {
        if ($post = $this->getRequest()->getPost()) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);

            try {
                $emailRecipients = preg_split('/\s*[,;]\s*/', Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT), null, PREG_SPLIT_NO_EMPTY);
                if( ! $emailRecipients) {
                  throw new Exception("There are no email recipients configured.");
                }
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = !Zend_Validate::is(trim($post['legal_name']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['full_name']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['website']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['phone']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['mailing_address']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['tax_id']), 'NotEmpty') ||
                         !Zend_Validate::is(trim($post['email']), 'EmailAddress');
                if ($error) {
                    throw new Lucky_Donations_Exception('Invalid form submitted!');
                }

                // Check for spam
                $request = Array(
                  'comment_author' => $post['full_name'],
                  'comment_author_email' => $post['email'],
                  'comment_author_url' => $post['website'],
                  'comment_content' => $post['legal_name'],
                );
                $response = new Varien_Object(Array('is_spam' => false));
                Mage::dispatchEvent('antispam_check_spam', Array('request' => $request, 'response' => $response));
                if ($response->getIsSpam()) {
                  throw new Mage_Core_Exception($this->__("We are sorry, an error has occurred. If you continue to have difficulty submitting this form, please contact our office."));
                }

                $mailTemplate = Mage::getModel('core/email_template') /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    ->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), 
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER), 
                            $emailRecipients,
                            Mage::app()->getStore()->getFrontendName(),
                            array('data' => $postObject)
                );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Lucky_Donations_Exception('An error ocurred sending the email.');
                }
                
                $translate->setTranslateInline(true);
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('contacts')->__(Mage::getStoreConfig(self::XML_PATH_MESSAGE_SUCCESS)));
            } catch (Mage_Core_Exception $e) {
                $translate->setTranslateInline(true);
                Mage::getSingleton('core/session')->addError($e->getMessage());
            } catch (Exception $e) {
                $translate->setTranslateInline(true);
                Mage::logException($e);
                Mage::getSingleton('core/session')->addError(Mage::helper('contacts')->__(Mage::getStoreConfig(self::XML_PATH_MESSAGE_ERROR)));
            }
        }
        $this->_redirect('donations');
    }

}
