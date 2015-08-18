<?php

/**
 * Displays a single Badge chooser.
 *
 * @author Gabriel Somoza <gabriel@usestrategery.com>
 */
class Lucky_Donations_Block_Cms_Badges extends Mage_Core_Block_Template {
    
    protected $_template = 'donations/cms/badges.phtml';
    
    const BADGE_DIRECTORY = 'images/donations';
    const DEFAULT_EXTENSION = '.jpg';
    const DEFAULT_TYPE = 'large';
    const DEFAULT_TYPE_NAME = 'size';
    const DEFAULT_BADGE_NAME = 'default';
    const DEFAULT_LINK_URL = 'donations';
    const DEFAULT_LINK_ALT = 'Join the Tribe at Ammo.net';
    const DEFAULT_LINK_TITLE = 'People of the Gun at Ammo.net';
    
    protected static $_count = 0;
    protected $_uniqueId;
    
    /**
     * Sets the unique ID and default data for this instance
     */
    public function _construct() {
        $this->_uniqueId = self::$_count++;
        
        $defaults = array(
            'types' => array('large', 'medium', 'small'),
            'type_name' => self::DEFAULT_TYPE_NAME,
            'badge_name' => self::DEFAULT_BADGE_NAME,
            'default_type' => self::DEFAULT_TYPE,
            'extension' => self::DEFAULT_EXTENSION,
            'link_url' => $this->getUrl(self::DEFAULT_LINK_URL),
            'link_alt' => self::DEFAULT_LINK_ALT,
            'link_title' => self::DEFAULT_LINK_TITLE,
        );
        $this->setData(array_merge($defaults, $this->getData()));
        
        parent::_construct();
    }
    
    /**
     * Load the badge-generator.js script at before_body_end
     */
    public function _beforeToHtml() {
        if(!$this->getLayout()->getBlock('badges_script')) { //if this is the first element
            $script = $this->getLayout()->createBlock('donations/cms_badges_script', 'badges_script');
            //$script->setMethod('toHtml');
            $this->getLayout()->getBlock('before_body_end')->append($script);
        }
        parent::_beforeToHtml();
    }
    
    /**
     * Returns the base url for all badge images that corresond to this badge
     */
    public function getBadgeImageBaseUrl() {
       return self::BADGE_DIRECTORY . '/badge-' . $this->getBadgeName() . '-'; 
    }
    
    /**
     * Returns the full URL for a give badge type.
     * @param String $type The badge type (e.g.: 'medium')
     */
    public function getBadgeImage($type = null) {
        if(is_null($type))
            $type = $this->getDefaultType();
        return $this->getSkinUrl($this->getBadgeImageBaseUrl() . $type . $this->getExtension());
    }
    
    /**
     * Accessor to the $_uniqueId property.
     * @return int
     */
    public function getUniqueId() {
        return $this->_uniqueId;
    }
    
    /**
     * Returns whether a type is set as the default type.
     * @param String $type 
     */
    public function isDefaultType($type) {
        return strtolower($this->getDefaultType()) == strtolower($type);
    }
    
}

