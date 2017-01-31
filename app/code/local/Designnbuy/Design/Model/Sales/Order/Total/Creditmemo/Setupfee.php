<?php
class Designnbuy_Design_Model_Sales_Order_Total_Creditmemo_Setupfee extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		$order = $creditmemo->getOrder();		
		$feeAmountLeft = $order->getSetupfeeAmountInvoiced() - $order->getSetupfeeAmountRefunded();
		$basefeeAmountLeft = $order->getBaseSetupfeeAmountInvoiced() - $order->getBaseSetupfeeAmountRefunded();
		// if ($basefeeAmountLeft > 0) {
			$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $feeAmountLeft);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basefeeAmountLeft);
			$creditmemo->setSetupfeeAmount($feeAmountLeft);
			$creditmemo->setBaseSetupfeeAmount($basefeeAmountLeft);
		// }
		return $this;
	}
}
