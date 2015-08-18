<?php

$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('donations_charity')} (
  `charity_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  PRIMARY KEY  (`charity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('donations_donations')} (
  `donation_id` int(10) unsigned NOT NULL auto_increment,
  `charity_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `amount` decimal(12,4) NOT NULL,
  PRIMARY KEY  (`donation_id`),
  UNIQUE `charity_order` (
    `charity_id`,
    `order_id`
  )
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$installer->endSetup();
