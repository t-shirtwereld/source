<?php
class Designnbuy_Dnbadmin_Adminhtml_DnbadminbackendController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
	$this->loadLayout();
	$this->_title($this->__("DNB Admin"));
	$this->renderLayout();
    }
}