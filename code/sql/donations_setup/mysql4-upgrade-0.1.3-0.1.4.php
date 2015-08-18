<?php
/**
 * We moved the location of some configuration options. This script migrates
 * existing configuration data to the new location.
 */
$installer = $this;
$installer->startSetup();

//@var $conn Zend_Db_Adapter_Mysqli
$conn = $installer->getConnection(); 

$table = $this->getTable('core_config_data');
$old_base = 'donations/application';
$new_base = 'donations_cms/application'; //the new location
$moved = array('recipient', 'sender', 'email_template', 'message_success', 'message_error');

foreach($moved as $current) {
    $conn->query("UPDATE $table SET path = '{$new_base}/{$current}' WHERE path = '{$old_base}/{$current}' LIMIT 1;");
}

$installer->endSetup();