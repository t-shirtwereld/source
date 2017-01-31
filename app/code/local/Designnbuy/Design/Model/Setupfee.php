<?php

class Designnbuy_Design_Model_Setupfee extends Varien_Object {

    const FEE_AMOUNT = 10;

    public static function getFee() {
	return self::FEE_AMOUNT;
    }

    public static function canApply($address) {
	return true;
    }

    public static function getSetupfee($address) {
	$quote = $address->getQuote();

	$quoteItemCollection = Mage::getModel("sales/quote_item")
		->getCollection()
		->setQuote($quote)
		->addFieldToFilter("quote_id", $quote->getId())
		// ->addFieldToFilter("product_type","configurable")
		->addFieldToFilter("parent_item_id", array('null' => true));

	$artWorkSetupTotalPrice = 0;
	$artWorkSetupTotalPriceFinal = 0;
	$compareId = array();
	foreach ($quoteItemCollection as $quoteItem) {
	    $cart_design_id = $quoteItem->getCartDesignId();

	    if ($cart_design_id) {
		$cartdata = Mage::getModel('design/design')->getCartDesigndata($cart_design_id);
		
		if (!in_array($cartdata['designed_id'], $compareId)) {
		    
		    $pritingMetohdData = Mage::helper('core')->jsonDecode($cartdata['printing_method']);
		    $customizedSides = $pritingMetohdData['isCustomized'];
		    $printMethodId = $pritingMetohdData['printingMethodId'];
		    $printingMethod = Mage::getModel('design/design')->getPrintingMethodsForPricing($printMethodId);
		    $customizedCount = 0;
		    if (isset($printingMethod) && count($printingMethod) > 0) {
				$customizedCount = 0;
				$totalNumberOfColors = 0;			
				/* For Name Number Price */
				/* For Artwork Setup Price
				  1 = Fixed
				  2 = Per Color
				 */
				
				if(isset($pritingMetohdData)){
					$artWorkSetUpType = $printingMethod['artwork_setup_price_type'];
					$artWorkSetUpPrice = $printingMethod['artwork_setup_price'];
					$artWorkSetupTotalPrice = 0;
					
					if ($pritingMetohdData['isCustomized']['Side1'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side2'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side3'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side4'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side5'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side6'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side7'] == true)
						$customizedCount++;
					if ($pritingMetohdData['isCustomized']['Side8'] == true)
						$customizedCount++;


					if ($pritingMetohdData['isCustomized']['Side1'] == true ||
						$pritingMetohdData['isCustomized']['Side2'] == true ||
						$pritingMetohdData['isCustomized']['Side3'] == true ||
						$pritingMetohdData['isCustomized']['Side4'] == true ||
						$pritingMetohdData['isCustomized']['Side5'] == true ||
						$pritingMetohdData['isCustomized']['Side6'] == true ||
						$pritingMetohdData['isCustomized']['Side7'] == true ||
						$pritingMetohdData['isCustomized']['Side8'] == true) 
					{
						if (isset($pritingMetohdData['totalColors']['Side1'])) {
						$side1Colors = intval($pritingMetohdData['totalColors']['Side1']);
						}
						if (isset($pritingMetohdData['totalColors']['Side2'])) {
						$side2Colors = intval($pritingMetohdData['totalColors']['Side2']);
						}
						if (isset($pritingMetohdData['totalColors']['Side3'])) {
						$side3Colors = intval($pritingMetohdData['totalColors']['Side3']);
						}
						if (isset($pritingMetohdData['totalColors']['Side4'])) {
						$side4Colors = intval($pritingMetohdData['totalColors']['Side4']);
						}
						if (isset($pritingMetohdData['totalColors']['Side5'])) {
						$side5Colors = intval($pritingMetohdData['totalColors']['Side5']);
						}
						if (isset($pritingMetohdData['totalColors']['Side6'])) {
						$side6Colors = intval($pritingMetohdData['totalColors']['Side6']);
						}
						if (isset($pritingMetohdData['totalColors']['Side7'])) {
						$side7Colors = intval($pritingMetohdData['totalColors']['Side7']);
						}
						if (isset($pritingMetohdData['totalColors']['Side8'])) {
						$side8Colors = intval($pritingMetohdData['totalColors']['Side8']);
						}
						$totalNumberOfColors = $side1Colors + $side2Colors + $side3Colors + $side4Colors + $side5Colors + $side6Colors + $side7Colors + $side8Colors;
						if ($artWorkSetUpType == 1) {
						$artWorkSetupTotalPrice = $artWorkSetUpPrice * $customizedCount;
						} else {
						$artWorkSetupTotalPrice = $artWorkSetupPrice + ($totalNumberOfColors * $printingMethod['artwork_setup_price']);
						}
					}
				}
		    } 
			else 
			{
				$noofcolor = $printingMethod['totalColors'];
				$totalNumberOfColors = intval($noofcolor['Front']) + intval($noofcolor['Back']) + intval($noofcolor['Left']) + intval($noofcolor['Right']);
				$artWorkSetupTotalPrice = $setupPrice + ($totalNumberOfColors * $artWorkSetUpPrice);
		    }
		    $artWorkSetupTotalPriceFinal = $artWorkSetupTotalPrice + $artWorkSetupTotalPriceFinal;
		}
		$compareId[] = $cartdata['designed_id'];
	    }
	  
	}
	//put here your business logic to check if fee should be applied or not
	//if($address->getAddressType() == 'billing'){
	return $artWorkSetupTotalPriceFinal;
	//}
    }

}