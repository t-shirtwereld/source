<?php
$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();


//adding CustomProduct attribute set
$sNewSetName = 'CustomProduct';
$iCatalogProductEntityTypeId = (int) $setup->getEntityTypeId('catalog_product');

$oAttributeset = Mage::getModel('eav/entity_attribute_set')
    ->setEntityTypeId($iCatalogProductEntityTypeId)
    ->setAttributeSetName($sNewSetName);
//adding CustomProduct attribute set based on default attribute set
if ($oAttributeset->validate()) {
    $oAttributeset
        ->save()
        ->initFromSkeleton($iCatalogProductEntityTypeId)
        ->save();
} 

 	
// adding CustomProduct attribute group
// the attribute added will be displayed under the group/tab CustomProduct Settings in product edit page
$setup->addAttributeGroup('catalog_product', 'CustomProduct', 'CustomProduct Settings', 2);

$is_customizable = Mage::getModel('catalog/resource_eav_attribute')
					->loadByCode('catalog_product','is_customizable');

if($is_customizable->getAttributeId()== null){
	//add is_customizable attribute
	$setup->addAttribute('catalog_product', 'is_customizable', array(
		'group'     	=> 'CustomProduct Settings',
		'input'         => 'boolean',
		'type'          => 'text',
		'label'         => 'Customizable Product',
		'backend'       => '',
		'visible'       => 1,
		'required'		=> 0,	
		'user_defined' => 1,
		'is_configurable' => 0, 
		'searchable' => 1,
		'filterable' => 0,
		'comparable'	=> 1,
		'visible_on_front' => 1,
		'visible_in_advanced_search'  => 0,
		'is_html_allowed_on_front' => 0,	
		'used_in_product_listing'   => 1,
		'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		// 'apply_to'                   => 'configurable',
	));

	$setup->updateAttribute('catalog_product', 'is_customizable', 'apply_to', 'simple,configurable');
	$setup->updateAttribute('catalog_product', 'is_customizable', 'used_in_product_listing', 1);
	$setup->addAttributeToSet('catalog_product', 'CustomProduct', 'CustomProduct Settings', 'is_customizable', 10);
}

$installer->endSetup();
?>