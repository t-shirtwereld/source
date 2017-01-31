<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Abstract_Upload extends Mage_Adminhtml_Block_Widget_Form_Container {

	public function __construct()
	{
		parent::__construct();

		$this->_objectId = 'id';
		$this->_blockGroup = 'clearandfizzy_enhancedcms';
		$this->_controller = 'cms_enhanced';
//		$this->_mode = 'upload';
//		$this->_headerText = 'Enhanced Static Block Uploader';

		$this->removeButton('reset');
		$this->removeButton('delete');
		$this->removeButton('save');

	} // end


} // end class
