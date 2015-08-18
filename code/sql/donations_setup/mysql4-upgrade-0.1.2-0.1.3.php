<?php
/**
 * Adds the column 'website' to the charity table
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('donations_charity'), 'website',
    'VARCHAR(255) NULL AFTER `description`');

$installer->endSetup();