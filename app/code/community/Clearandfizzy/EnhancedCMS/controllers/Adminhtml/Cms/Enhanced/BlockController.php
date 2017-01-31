<?php
/**
 * Clearandfizzy
 *
 * NOTICE OF LICENSE
 *
 *
 * THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS CREATIVE
 * COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED BY
 * COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS
 * AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED.

 * BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE
 * TO BE BOUND BY THE TERMS OF THIS LICENSE. TO THE EXTENT THIS LICENSE MAY
 * BE CONSIDERED TO BE A CONTRACT, THE LICENSOR GRANTS YOU THE RIGHTS
 * CONTAINED HERE IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND
 * CONDITIONS.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.clearandfizzy.com for more information.
 *
 * @category    Community
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com)
 * @license     http://www.clearandfizzy.com/licence.txt
 * @author		Gareth Price <gareth@clearandfizzy.com>
 * 
 */
require_once 'Mage/Adminhtml/controllers/Cms/BlockController.php';
class Clearandfizzy_EnhancedCMS_Adminhtml_Cms_Enhanced_BlockController extends Mage_Adminhtml_Cms_BlockController {

	private $destination_filename = 'staticblocks.csv';

	/**
	 * Exports a CSV file
	 */
	public function exportCsvAction() {
        $fileName   = 'staticblocks.csv';
        $content    = $this->getLayout()->createBlock('clearandfizzy_enhancedcms/cms_enhanced_block_grid')->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);

	}

	/**
	 * Renders the Upload CSV Form
	 */
	public function uploadCsvAction() {
		$this->loadLayout();
		$block = $this->getLayout()->createBlock('clearandfizzy_enhancedcms/cms_enhanced_block_upload');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	} // end

	/**
	 * Imports a csv into the static block model
	 */
	public function importCsvAction() {

		// get uploaded file
		$filepath = $this->getUploadedFile();

		if ($filepath != null) {
			try {
				// import into model
				Mage::getSingleton('clearandfizzy_enhancedcms/import_block')->process($filepath);
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('CSV Imported Successfully'));

			} catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('An Error occured importing CSV.'));
			}
		} // end

		// redirect to grid page.
		$this->_redirect('*/cms_block/index');

	} // end


	/**
	 * Handles CSV upload
	 * @return string $filepath
	 */
	private function getUploadedFile() {
		$filepath = null;

		if(isset($_FILES['importfile']['name']) and (file_exists($_FILES['importfile']['tmp_name']))) {
			try {
				$uploader = new Varien_File_Uploader('importfile');
				$uploader->setAllowedExtensions(array('csv','txt')); // or pdf or anything
				$uploader->setAllowRenameFiles(false);
				$uploader->setFilesDispersion(false);

				$path = Mage::helper('clearandfizzy_enhancedcms/data')->getImportPath();
				$uploader->save($path, $this->destination_filename);
				$filepath = $path . $this->destination_filename;

			} catch(Exception $e) {
				// log error
				Mage::logException($e);
			} // end if

		} // end if

		return $filepath;

	}

}
