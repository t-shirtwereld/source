<?php
class Designnbuy_Design_Model_Sales_Order_Total_Invoice_Setupfee extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		$order = $invoice->getOrder();
		
		$feeAmountLeft = $order->getSetupfeeAmount();;
		$baseFeeAmountLeft = $order->getBaseSetupfeeAmount();
		// if (abs($baseFeeAmountLeft) < $invoice->getBaseGrandTotal()) {
			// $invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmountLeft);
			// $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmountLeft);
		// } else {
			// $feeAmountLeft = $invoice->getGrandTotal() * -1;
			// $baseFeeAmountLeft = $invoice->getBaseGrandTotal() * -1;

			// $invoice->setGrandTotal(0);
			// $invoice->setBaseGrandTotal(0);
		// }
		$invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmountLeft);
		$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmountLeft);	
		$invoice->setSetupfeeAmount($feeAmountLeft);
		$invoice->setBaseSetupfeeAmount($baseFeeAmountLeft);
		return $this;
	}
}
