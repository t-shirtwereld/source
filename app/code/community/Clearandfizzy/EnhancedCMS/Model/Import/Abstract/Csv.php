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

class Clearandfizzy_EnhancedCMS_Model_Import_Abstract_Csv extends Mage_Core_Model_Abstract {

	private $array_delimiter = ';';
	private $delimiter = ',';
	private $header_columns;
	protected $_modelname; // = 'cms/block'; = 'cms/page';

	private function openFile($filepath) {
		$handle = null;

		if (($handle = fopen($filepath, "r")) !== FALSE) {
			return $handle;
		} else {
			throw new Exception('Error opening file ' . $filepath);
		} // end

	} // end


	public function process($filepath) {

		$handle = $this->openfile($filepath);

		$row = 0;
		if ( $handle != null ) {

			// loop thru all rows
			while (($data = fgetcsv($handle, 0, $this->delimiter)) !== FALSE) {
				$row++;

				// if this is the head row keep this as a column reference
				if ($row == 1) {
					$this->mapHeader($data);
					continue;
				}

				// make sure we have a reset model object
				//$staticblock = Mage::getSingleton($this->_modelname)->clearInstance();
				$staticblock = Mage::getModel($this->_modelname);
				
				// get the identifier column for this row
				$identifier = $data[$this->getIdentifierColumnIndex()];

				// if a static block already exists for this identifier - load the data
				$staticblock->load($identifier);

				// loop through each column
				foreach ($this->header_columns as $index => $keyname) {
					$keyname = strtolower($keyname);

					// switch statement incase we need to do logic depending on the column name
					switch ($keyname) {

						case "stores":
							// stores are separated with ";" when they're exported
							$stores = $data[$index];
							$stores_array = explode(';', $stores);
							$staticblock->setData($keyname, $stores_array);
							$staticblock->setData('store_id', $stores_array);
						break;

						case "block_id":
						case "page_id":
							// dont need to add this. @todo remove this column from export.
						break;

						default:
							// fgetcsv encodes everything
							$staticblock->setData($keyname, html_entity_decode($data[$index]));
						break;

					} // end switch
				} // end for

				// save our block
				try {
					$staticblock->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('Updated ' . $identifier));
				} catch (Exception $e) {
					Mage::throwException($e->getMessage() . 'ID = ' . $data[$this->getIdentifierColumnIndex()]
							);
				}
			} // end while
		}// end if

	} // end

	/**
	 * 
	 * @param unknown $data_array
	 */
	private function mapHeader($data_array) {
		$this->header_columns = $data_array;
	}
	
	/**
	 * 
	 * @param unknown $name
	 * @return mixed
	 */
	private function getIndexByName($name) {
		$header = $this->header_columns;
		$index = array_search($name, $header);
		return $index;
	}

	/**
	 * 
	 * @return mixed
	 */
	private function getIdentifierColumnIndex() {
		$header = $this->header_columns;
		$index = array_search('Identifier', $header);
		return $index;
	}

}
