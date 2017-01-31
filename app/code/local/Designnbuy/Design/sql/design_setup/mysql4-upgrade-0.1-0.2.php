<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$installer->run("		
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
");
$installer->run("
ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
");

$installer->run("

	ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
	ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;

");
$installer->run("

	ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `setupfee_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
	ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_setupfee_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;

");

$installer->run("

	ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `setupfee_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
	ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_setupfee_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;

	ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;
	ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_setupfee_amount` DECIMAL( 10, 2 ) NOT NULL;

");		
$this->endSetup();