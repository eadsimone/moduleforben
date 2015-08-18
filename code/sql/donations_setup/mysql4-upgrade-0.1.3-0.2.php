<?php
/*
 * Add indexes and foreign keys.
 */
/* @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->run("
ALTER TABLE `donations_donations`
  ADD FOREIGN KEY (`order_id`) REFERENCES `sales_flat_order` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE `donations_donations`
  ADD INDEX (`charity_id`);
ALTER TABLE `donations_donations`
  ADD FOREIGN KEY (`charity_id`) REFERENCES  `donations_charity` (`charity_id`) ON DELETE CASCADE ON UPDATE CASCADE ;
");

$this->endSetup();
