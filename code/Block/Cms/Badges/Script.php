<?php

/**
 * Displays a single Badge chooser.
 *
 * @author Gabriel Somoza <gabriel@usestrategery.com>
 */
class Lucky_Donations_Block_Cms_Badges_Script extends Mage_Core_Block_Text {
    
    const SCRIPT_URL = 'js/badge-generator.js';
    
    public function _toHtml() {
        return '<script type="text/javascript" src="' . $this->getSkinUrl(self::SCRIPT_URL) . '"></script>';
    }
    
}

