<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Page_Upload extends Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Abstract_Upload {


	public function __construct()
	{
		parent::__construct();

		$this->_mode = 'page_upload';
		$this->_headerText = 'Enhanced CMS Page Uploader';

	} // end

	/**
	 * Get URL for back (reset) button
	 *
	 * @return string
	 */
	public function getBackUrl()
	{
		return $this->getUrl('*/cms_page/index/');
	}


} // end class
