<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Abstract_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	protected $_isExport = true;

	public function entityDecode( $value ) {

		$value = html_entity_decode($value);
//		$value = str_replace('"', '\"', $value);
		
		return $value;

	} // end

}
