<?php

class MultiSafepay_Msp_Block_Adminhtml_Servicecost_Totals_Invoice extends Mage_Adminhtml_Block_Sales_Order_Invoice_Totals {
    /* protected function _initTotals() {
      parent::_initTotals();

      $source = $this->getSource();
      $amount = $source->getOrder()->getServicecost();
      $method = $source->getOrder()->getPayment()->getMethodInstance();

      $code = $source->getOrder()->getPayment()->getMethod();

      if ($amount) {
      $this->addTotalBefore(new Varien_Object(array(
      'code' => 'servicecost',
      'value' => $amount,
      'base_value' => $this->_convertFeeCurrency($amount, $source->getOrder()->getOrderCurrencyCode(), $source->getOrder()->getGlobalCurrencyCode()),
      'label' => Mage::helper('msp')->getFeeLabel($code).' (incl Tax)'
      ), array('tax'))
      );
      }

      return $this;
      } */

    protected function _initTotals() {
        parent::_initTotals();

        $source = $this->getSource();
        $amount = $source->getOrder()->getServicecostPdf();
        $method = $source->getOrder()->getPayment()->getMethodInstance();

        $code = $source->getOrder()->getPayment()->getMethod();

        if ($amount) {
            $this->addTotalBefore(new Varien_Object(array(
                'code' => 'servicecost',
                'value' => $amount,
                'base_value' => $this->_convertFeeCurrency($amount, $source->getOrder()->getOrderCurrencyCode(), $source->getOrder()->getGlobalCurrencyCode()),
                'label' => Mage::helper('msp')->getFeeLabel($code)
                    ), array('tax'))
            );
        }

        return $this;
    }

    protected function _convertFeeCurrency($amount, $currentCurrencyCode, $targetCurrencyCode) {
        if ($currentCurrencyCode == $targetCurrencyCode) {
            return $amount;
        }

        $currentCurrency = Mage::getModel('directory/currency')->load($currentCurrencyCode);
        $rateCurrentToTarget = $currentCurrency->getAnyRate($targetCurrencyCode);

        if ($rateCurrentToTarget === false) {
            Mage::throwException(Mage::helper("msp")->__("Imposible convert %s to %s", $currentCurrencyCode, $targetCurrencyCode));
        }

        //Disabled check, fixes PLGMAG-10. Magento seems to not to update USD->EUR rate in db, resulting in wrong conversions. Now we calculate the rate manually and and don't trust Magento stored rate.
        // if (strlen((string) $rateCurrentToTarget) < 12) { 
        $revertCheckingCode = Mage::getModel('directory/currency')->load($targetCurrencyCode);
        $revertCheckingRate = $revertCheckingCode->getAnyRate($currentCurrencyCode);
        $rateCurrentToTarget = 1 / $revertCheckingRate;
        //}

        return round($amount * $rateCurrentToTarget, 2);
    }

}
