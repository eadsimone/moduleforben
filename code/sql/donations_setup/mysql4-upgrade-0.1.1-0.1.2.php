<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('donations_charity');
$conn = $installer->getConnection();

$conn->addColumn($table,'is_system','BOOL NOT NULL default \'0\'');

$conn->query("
INSERT INTO {$this->getTable('donations_charity')}
  (`name`, `image`,`description`,`is_system`)
VALUES ('no-donation','','',1);
");
$charityId = $conn->lastInsertId();

$conn->query("
REPLACE INTO {$this->getTable('core_config_data')}
  (`scope`, `scope_id`, `path`, `value`)
VALUES ('default', '0', 'donations/general/nodonationcharityid',$charityId);
");

$conn->query("
ALTER TABLE {$this->getTable('donations_donations')} DROP INDEX `charity_order`;
");

$conn->query("
ALTER TABLE {$this->getTable('donations_donations')} ADD UNIQUE `order_idx` (`order_id`);
");

$installer->endSetup();