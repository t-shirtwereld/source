<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */

class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Page_Grid extends Mage_Adminhtml_Block_Cms_Page_Grid {


	protected function _prepareColumns() {
		parent::_prepareColumns();

		$this->addExportType('*/cms_enhanced_page/exportCsv', Mage::helper('clearandfizzy_enhancedcms')->__('CSV'));

		return $this;

	} // end


}