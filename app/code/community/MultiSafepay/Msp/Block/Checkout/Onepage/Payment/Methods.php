<?php

/**
 *
 * @category MultiSafepay
 * @package  MultiSafepay_Msp
 */
class MultiSafepay_Msp_Block_Checkout_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods {

    /**
     * @param Mage_Payment_Model_Method_Abstract $method
     * @return string
     */
    public function getMethodTitle(Mage_Payment_Model_Method_Abstract $method) {
        if ($paymentTitle = Mage::helper('msp')->getPaymentTitle($method)) {
            return $paymentTitle;
        }

        return parent::getMethodTitle($method);
    }

}
