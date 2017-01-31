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

class Clearandfizzy_EnhancedCMS_Model_Import_Block extends Mage_Core_Model_Abstract {


	public function process($filepath) {

		// get the file extension
		$array = pathinfo($filepath);

		switch ($array["extension"] ) {

			case "csv":
			case "txt":
				Mage::getModel('clearandfizzy_enhancedcms/import_block_csv')->process($filepath);
			break;

			default:
				Mage::throwException("File is of unknown format, cannot process to import");
			break;
 		} // end

	} // end

}
