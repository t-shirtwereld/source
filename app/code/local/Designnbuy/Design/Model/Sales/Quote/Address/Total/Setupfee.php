<?php
class Designnbuy_Design_Model_Sales_Quote_Address_Total_Setupfee extends Mage_Sales_Model_Quote_Address_Total_Abstract{
	protected $_code = 'setupfee';

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);	
		$this->_setAmount(0);
		$this->_setBaseAmount(0);
		// if ($address->getData('address_type')=='billing') return $this;
		$items = $this->_getAddressItems($address);		
		// if (!count($items)) {
			// return $this; //this makes only address type shipping to come through
		// }		

		$quote = $address->getQuote();
		
		if(Designnbuy_Design_Model_Setupfee::canApply($address)){			
			if ($address->getData('address_type')=='billing') return $this;
			$exist_amount = $quote->getSetupfeeAmount();
			// $fee = Designnbuy_Printingmethod_Model_Setupfee::getFee();
			$fee = Designnbuy_Design_Model_Setupfee::getSetupfee($address);
			
			// $balance = $fee + $exist_amount;
			
			$balance = $fee;
			
			//$this->_setAmount($balance);
			//$this->_setBaseAmount($balance);

			$address->setSetupfeeAmount($balance);
			$address->setBaseSetupfeeAmount($balance);
				
			$quote->setSetupfeeAmount($balance);

			$address->setGrandTotal($address->getGrandTotal() + $address->getSetupfeeAmount());
			$address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseSetupfeeAmount());
		}
		 return $this;
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
	    if ($address->getData('address_type') == 'billing')
	    return $this;
	    $amt = $address->getSetupfeeAmount();
	    
	    $address->addTotal(array(
		'code'=>$this->getCode(),
		'title'=>'Artwork Setup Charge',
		'value'=> $amt
	    ));
	    return $this;
	}
}