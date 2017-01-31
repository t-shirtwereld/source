<?php

/**
 *
 * @category MultiSafepay
 * @package  MultiSafepay_Msp
 */
class MultiSafepay_Msp_Model_Config_Sources_Accounts {

    const TEST_MODE = 'test';
    const LIVE_MODE = 'live';

    /**
     * @return array
     */
    public function toOptionArray() {
        return array(
            array(
                "value" => self::TEST_MODE,
                "label" => "Test account"
            ),
            array(
                "value" => self::LIVE_MODE,
                "label" => "Live account"
            ),
        );
    }

}
