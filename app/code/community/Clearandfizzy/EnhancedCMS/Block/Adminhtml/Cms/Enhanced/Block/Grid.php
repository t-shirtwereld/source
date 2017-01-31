<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */

class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Block_Grid 
	extends Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Abstract_Grid
{

	protected $_isExport = true;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('staticBlocksGrid');
		$this->setDefaultSort('block_id');
		$this->setDefaultDir('desc');
	}

	/**
	 */
	protected function _prepareColumns()
	{

		$this->addColumn('block_id', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Block_Id'),
				'width'     =>'50px',
				'index'     => 'block_id'
		));

		$this->addColumn('title', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Title'),
				'width'     =>'50px',
				'index'     => 'title'
		));

		$this->addColumn('content', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Content'),
				'width'     =>'50px',
				'index'     => 'content',
            	'frame_callback' => array($this, 'entityDecode')
		));

		$this->addColumn('identifier', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Identifier'),
				'width'     =>'50px',
				'index'     => 'identifier'
		));

		$this->addColumn('creation_time', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Creation_Time'),
				'width'     =>'50px',
				'index'     => 'creation_time'
		));

		$this->addColumn('update_time', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Update_Time'),
				'width'     =>'50px',
				'index'     => 'update_time'
		));

		$this->addColumn('is_active', array(
				'header'    =>Mage::helper('clearandfizzy_enhancedcms')->__('Is_Active'),
				'width'     =>'50px',
				'index'     => 'is_active'
		));

		$this->addColumn('stores', array(
				'header'        => Mage::helper('cms')->__('Stores'),
				'index'         => 'stores',
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('clearandfizzy_enhancedcms')->__('CSV'));

		return parent::_prepareColumns();


	}

	protected function _prepareCollection()
	{
		//$block_collection = Mage::getModel('cms/block')->getCollection();
		$block_collection = Mage::getResourceModel('cms/block_collection');

		// add the stores this block belongs to
		foreach ($block_collection as $key => $block) {
			$stores = $block->getResource()->lookupStoreIds($block->getBlockId());
			$stores = implode(';', $stores);
			$block->setStores($stores);
		} // end

		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		$this->setCollection($block_collection);

		return parent::_prepareCollection();
	}


}
