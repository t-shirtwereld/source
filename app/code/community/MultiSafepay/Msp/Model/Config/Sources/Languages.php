<?php

/**
 *
 * @category MultiSafepay
 * @package  MultiSafepay_Msp
 */
class MultiSafepay_Msp_Model_Config_Sources_Languages {

    /**
     * @return array
     */
    public function toOptionArray() {
        return array(
            array(
                "value" => "nl",
                "label" => Mage::helper("msp")->__('Dutch')
            ),
            array(
                "value" => "en",
                "label" => Mage::helper("msp")->__('English')
            ),
            array(
                "value" => "de",
                "label" => Mage::helper("msp")->__('German')
            ),
            array(
                "value" => "fr",
                "label" => Mage::helper("msp")->__('French')
            ),
            array(
                "value" => "es",
                "label" => Mage::helper("msp")->__('Spanish')
            )
        );
    }

}
