<?php
/**
 * @category    Clearandfizzy
 * @package     Clearandfizzy_EnhancedCMS
 * @copyright   Copyright (c) 2015 Clearandfizzy Ltd. (http://www.clearandfizzy.com/)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */
class Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Block_Upload_Form extends Clearandfizzy_EnhancedCMS_Block_Adminhtml_Cms_Enhanced_Abstract_Upload_Form {

	protected function _prepareForm() {

		$form = new Varien_Data_Form(array(
				'id' => 'upload_form',
				'action' => $this->getUrl('*/cms_enhanced_block/importCsv'),
				'method' => 'post',
				'enctype' => 'multipart/form-data'
		)
		);

		$fieldset = $form->addFieldset('upload_csv', array('legend' => Mage::helper('clearandfizzy_enhancedcms')->__('Upload Static Block CSV')));

		$fieldset->addField('importfile', 'file', array(
				'label'     => Mage::helper('clearandfizzy_enhancedcms')->__('Static Block CSV File'),
				'required'  => true,
				'name'      => 'importfile',
		));


		$fieldset->addField('submit', 'note', array(
				'type' => 'submit',
				'text' => $this->getButtonHtml(
					Mage::helper('clearandfizzy_enhancedcms')->__('Upload & Import'),
					"upload_form.submit();",
					'upload'
				)
		));

        $form->setUseContainer(true);
        $this->setForm($form);

		return parent::_prepareForm();

	} // end fun

}


