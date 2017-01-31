<?php

require_once 'Mage/Sales/Model/Quote.php';

class Designnbuy_Sales_Model_Quote extends Mage_Sales_Model_Quote {

    function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
	$arrData = array();
	// if input is object, convert into array
	if (is_object($arrObjData)) {
	    $arrObjData = get_object_vars($arrObjData);
	}

	if (is_array($arrObjData)) {
	    foreach ($arrObjData as $index => $value) {
		if (is_object($value) || is_array($value)) {
		    $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
		}
		if (in_array($index, $arrSkipIndices)) {
		    continue;
		}
		$arrData[$index] = $value;
	    }
	}
	return $arrData;
    }

    public function addProduct(Mage_Catalog_Model_Product $product, $request = null, $orderItem = null) {
	return $this->addProductAdvanced(
			$product, $request, Mage_Catalog_Model_Product_Type_Abstract::PROCESS_MODE_FULL, $orderItem
	);
    }

    public function addProductAdvanced(Mage_Catalog_Model_Product $product, $request = null, $processMode = null, $orderItem = null) {

	if ($request === null) {
	    $request = 1;
	}
	if (is_numeric($request)) {
	    $request = new Varien_Object(array('qty' => $request));
	}
	if (!($request instanceof Varien_Object)) {
	    Mage::throwException(Mage::helper('sales')->__('Invalid request for adding product to quote.'));
	}
//print "<pre>"; print_r($request); print_r($product); print_r($processMode); exit;
	$cartCandidates = $product->getTypeInstance(true)
		->prepareForCartAdvanced($request, $product, $processMode);


	/**
	 * Error message
	 */
	if (is_string($cartCandidates)) {
	    return $cartCandidates;
	}

	/**
	 * If prepare process return one object
	 */
	if (!is_array($cartCandidates)) {
	    $cartCandidates = array($cartCandidates);
	}

	if (!empty($cartCandidates)) {
	    if (isset($_REQUEST['designed_id'])) {
		if (isset($_REQUEST['addtocartparam'])) {
		    $addtocartparam = json_decode(html_entity_decode($_REQUEST['addtocartparam']), true);
		} else {
		    $addtocartparam = 0;
		}

		$product_id = $addtocartparam['productID'];
		$option = array();
		foreach ($addtocartparam as $id => $value) {
		    if ($id != 'productID') {
			$option += array(
			    $id => $value
			);
		    }
		}

		if (isset($_REQUEST['designed_id'])) {
		    $designed_id = $_REQUEST['designed_id'];
		} else {
		    $designed_id = 0;
		}

		if (isset($_REQUEST['no_of_side'])) {
		    $no_of_sides = $_REQUEST['no_of_side'];
		} else {
		    $no_of_sides = 0;
		}

		if (isset($_REQUEST['notes'])) {
		    $notes = $_REQUEST['notes'];
		} else {
		    $notes = '';
		}

		if (isset($_REQUEST['qty'])) {
		    $quantity = $_REQUEST['qty'];
		} else {
		    $quantity = 0;
		}

		if (isset($_REQUEST['printingMethod'])) {
		    $printingMethod = json_decode(html_entity_decode($_REQUEST['printingMethod']), true);
		} else {
		     $printingMethod = array();
		}

		$side1_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side1'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side1'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side1']
		);

		$side2_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side2'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side2'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side2']
		);

		$side3_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side3'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side3'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side3']
		);

		$side4_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side4'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side4'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side4']
		);

		$side5_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side5'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side5'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side5']
		);

		$side6_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side6'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side6'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side6']
		);

		$side7_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side7'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side7'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side7']
		);

		$side8_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side8'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side8'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side8']
		);
		$side1_otherdata = json_encode($side1_otherdata);
		$side2_otherdata = json_encode($side2_otherdata);
		$side3_otherdata = json_encode($side3_otherdata);
		$side4_otherdata = json_encode($side4_otherdata);
		$side5_otherdata = json_encode($side5_otherdata);
		$side6_otherdata = json_encode($side6_otherdata);
		$side7_otherdata = json_encode($side7_otherdata);
		$side8_otherdata = json_encode($side8_otherdata);

		$side1_svg = $_REQUEST['side1_svg'];
		$side2_svg = $_REQUEST['side2_svg'];
		$side3_svg = $_REQUEST['side3_svg'];
		$side4_svg = $_REQUEST['side4_svg'];
		$side5_svg = $_REQUEST['side5_svg'];
		$side6_svg = $_REQUEST['side6_svg'];
		$side7_svg = $_REQUEST['side7_svg'];
		$side8_svg = $_REQUEST['side8_svg'];

		$side1_png = $_REQUEST['side1_png'];
		$side2_png = $_REQUEST['side2_png'];
		$side3_png = $_REQUEST['side3_png'];
		$side4_png = $_REQUEST['side4_png'];
		$side5_png = $_REQUEST['side5_png'];
		$side6_png = $_REQUEST['side6_png'];
		$side7_png = $_REQUEST['side7_png'];
		$side8_png = $_REQUEST['side8_png'];
		$customization_unique_id = $_REQUEST['customization_unique_id'];
		$name = $_REQUEST['totalname'];
		$number = $_REQUEST['totalnumber'];
		$name_number_data = $_REQUEST['namenumData'];

		$product_options_id = json_encode($option);
		$printing_method = json_encode($printingMethod);
		$compareid = md5(rand());

		$data = array(
		    'designed_id' => $designed_id,
		    'product_id' => $product_id,
		    'compare_id' => $compareid,
		    'customization_unique_id' => $customization_unique_id,
		    'product_options_id' => $product_options_id,
		    'no_of_sides' => $no_of_sides,
		    'printing_method' => $printing_method,
		    'notes' => $notes,
		    'side1_svg' => $side1_svg,
		    'side1_png' => $side1_png,
		    'side1_otherdata' => $side1_otherdata,
		    'side2_svg' => $side2_svg,
		    'side2_png' => $side2_png,
		    'side2_otherdata' => $side2_otherdata,
		    'side3_svg' => $side3_svg,
		    'side3_png' => $side3_png,
		    'side3_otherdata' => $side3_otherdata,
		    'side4_svg' => $side4_svg,
		    'side4_png' => $side4_png,
		    'side4_otherdata' => $side4_otherdata,
		    'side5_svg' => $side5_svg,
		    'side5_png' => $side5_png,
		    'side5_otherdata' => $side5_otherdata,
		    'side6_svg' => $side6_svg,
		    'side6_png' => $side6_png,
		    'side6_otherdata' => $side6_otherdata,
		    'side7_svg' => $side7_svg,
		    'side7_png' => $side7_png,
		    'side7_otherdata' => $side7_otherdata,
		    'side8_svg' => $side8_svg,
		    'side8_png' => $side8_png,
		    'side8_otherdata' => $side8_otherdata,
		    'name' => $name,
		    'number' => $number,
		    'name_number_data' => $name_number_data
		);

		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio/addCartDesign';
		$cart_design_id = $this->httpPost($url, $data);
	    } else if (!empty($request)) {
		if (isset($request['addtocartparam'])) {
		    $addtocartparam = json_decode(html_entity_decode($request['addtocartparam']), true);
		} else {
		    $addtocartparam = 0;
		}

		$product_id = $addtocartparam['productID'];
		$option = array();
		foreach ($addtocartparam as $id => $value) {
		    if ($id != 'productID') {
			$option += array(
			    $id => $value
			);
		    }
		}

		if (isset($request['designed_id'])) {
		    $designed_id = $request['designed_id'];
		} else {
		    $designed_id = 0;
		}

		if (isset($request['no_of_side'])) {
		    $no_of_sides = $request['no_of_side'];
		} else {
		    $no_of_sides = 0;
		}

		if (isset($request['notes'])) {
		    $notes = $request['notes'];
		} else {
		    $notes = '';
		}

		if (isset($request['qty'])) {
		    $quantity = $request['qty'];
		} else {
		    $quantity = 0;
		}

		if (isset($request['printingMethod'])) {
		    $printingMethod = json_decode(html_entity_decode($request['printingMethod']), true);
		} else {
		    $printingMethod = array();
		}
		$side1_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side1'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side1'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side1']
		);

		$side2_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side2'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side2'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side2']
		);

		$side3_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side3'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side3'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side3']
		);

		$side4_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side4'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side4'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side4']
		);

		$side5_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side5'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side5'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side5']
		);

		$side6_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side6'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side6'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side6']
		);

		$side7_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side7'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side7'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side7']
		);

		$side8_otherdata = array(
		    'colors' => $printingMethod['sideWiseColorInfo']['Side8'],
		    'fonts' => $printingMethod['sideWiseFontIDs']['Side8'],
		    'images' => $printingMethod['sideWiseImgIDs']['Side8']
		);
		$side1_otherdata = json_encode($side1_otherdata);
		$side2_otherdata = json_encode($side2_otherdata);
		$side3_otherdata = json_encode($side3_otherdata);
		$side4_otherdata = json_encode($side4_otherdata);
		$side5_otherdata = json_encode($side5_otherdata);
		$side6_otherdata = json_encode($side6_otherdata);
		$side7_otherdata = json_encode($side7_otherdata);
		$side8_otherdata = json_encode($side8_otherdata);

		$side1_svg = $request['side1_svg'];
		$side2_svg = $request['side2_svg'];
		$side3_svg = $request['side3_svg'];
		$side4_svg = $request['side4_svg'];
		$side5_svg = $request['side5_svg'];
		$side6_svg = $request['side6_svg'];
		$side7_svg = $request['side7_svg'];
		$side8_svg = $request['side8_svg'];

		$side1_png = $request['side1_png'];
		$side2_png = $request['side2_png'];
		$side3_png = $request['side3_png'];
		$side4_png = $request['side4_png'];
		$side5_png = $request['side5_png'];
		$side6_png = $request['side6_png'];
		$side7_png = $request['side7_png'];
		$side8_png = $request['side8_png'];
		$customization_unique_id = $request['customization_unique_id'];
		$name = $request['totalname'];
		$number = $request['totalnumber'];
		$name_number_data = $request['namenumData'];
		$product_options_id = json_encode($option);
		$printing_method = json_encode($printingMethod);
		$compareid = md5(rand());

		$data = array(
		    'designed_id' => $designed_id,
		    'product_id' => $product_id,
		    'compare_id' => $compareid,
		    'customization_unique_id' => $customization_unique_id,
		    'product_options_id' => $product_options_id,
		    'no_of_sides' => $no_of_sides,
		    'printing_method' => $printing_method,
		    'notes' => $notes,
		    'side1_svg' => $side1_svg,
		    'side1_png' => $side1_png,
		    'side1_otherdata' => $side1_otherdata,
		    'side2_svg' => $side2_svg,
		    'side2_png' => $side2_png,
		    'side2_otherdata' => $side2_otherdata,
		    'side3_svg' => $side3_svg,
		    'side3_png' => $side3_png,
		    'side3_otherdata' => $side3_otherdata,
		    'side4_svg' => $side4_svg,
		    'side4_png' => $side4_png,
		    'side4_otherdata' => $side4_otherdata,
		    'side5_svg' => $side5_svg,
		    'side5_png' => $side5_png,
		    'side5_otherdata' => $side5_otherdata,
		    'side6_svg' => $side6_svg,
		    'side6_png' => $side6_png,
		    'side6_otherdata' => $side6_otherdata,
		    'side7_svg' => $side7_svg,
		    'side7_png' => $side7_png,
		    'side7_otherdata' => $side7_otherdata,
		    'side8_svg' => $side8_svg,
		    'side8_png' => $side8_png,
		    'side8_otherdata' => $side8_otherdata,
		    'name' => $name,
		    'number' => $number,
		    'name_number_data' => $name_number_data
		);

		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio/addCartDesign';
		$cart_design_id = $this->httpPost($url, $data);
	    }
	}
	$parentItem = null;
	$errors = array();
	$items = array();
	foreach ($cartCandidates as $candidate) {
	    // Child items can be sticked together only within their parent
	    $stickWithinParent = $candidate->getParentProductId() ? $parentItem : null;
	    $candidate->setStickWithinParent($stickWithinParent);
	    $item = $this->_addCatalogProduct($candidate, $candidate->getCartQty());
	    if ($request->getResetCount() && !$stickWithinParent && $item->getId() === $request->getId()) {
		$item->setData('qty', 0);
	    }
	    $items[] = $item;
	    $total_color = '';

	    if (isset($cart_design_id)) {
		$item->setCartDesignId($cart_design_id);
	    }

	    /**
	     * As parent item we should always use the item of first added product
	     */
	    if (!$parentItem) {
		$parentItem = $item;
	    }
	    if ($parentItem && $candidate->getParentProductId()) {
		$item->setParentItem($parentItem);
	    }

	    /**
	     * We specify qty after we know about parent (for stock)
	     */
	    $item->addQty($candidate->getCartQty());

	    // collect errors instead of throwing first one
	    if ($item->getHasError()) {
		$message = $item->getMessage();
		if (!in_array($message, $errors)) { // filter duplicate messages
		    $errors[] = $message;
		}
	    }
	}
	if (!empty($errors)) {
	    Mage::throwException(implode("\n", $errors));
	}

	Mage::dispatchEvent('sales_quote_product_add_after', array('items' => $items));

	return $item;
    }

    public function getItemByProduct($product) {
	// foreach ($this->getAllItems() as $item) {           
	// if(!isset($_REQUEST['dataxml']))
	// {
	// if ($item->representProduct($product)) {				
	// return $item;
	// }
	// }
	// }
	return false;
    }

    public function merge(Mage_Sales_Model_Quote $quote) {
	Mage::dispatchEvent(
		$this->_eventPrefix . '_merge_before', array(
	    $this->_eventObject => $this,
	    'source' => $quote
		)
	);

	foreach ($quote->getAllVisibleItems() as $item) {
	    $found = false;
	    foreach ($this->getAllItems() as $quoteItem) {
		
	    }

	    if (!$found) {
		$newItem = clone $item;
		$this->addItem($newItem);
		if ($item->getHasChildren()) {
		    foreach ($item->getChildren() as $child) {
			$newChild = clone $child;
			$newChild->setParentItem($newItem);
			$this->addItem($newChild);
		    }
		}
	    }
	}
	/**
	 * Init shipping and billing address if quote is new
	 */
	if (!$this->getId()) {
	    $this->getShippingAddress();
	    $this->getBillingAddress();
	}

	if ($quote->getCouponCode()) {
	    $this->setCouponCode($quote->getCouponCode());
	}

	Mage::dispatchEvent(
		$this->_eventPrefix . '_merge_after', array(
	    $this->_eventObject => $this,
	    'source' => $quote
		)
	);

	return $this;
    }

    public function httpPost($url, $params) {
	$postData = '';
	//create name value pairs seperated by &
	foreach ($params as $k => $v) {
	    $postData .= $k . '=' . $v . '&';
	}
	rtrim($postData, '&');

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	$output = curl_exec($ch);
	if ($output === false) {
	    echo "Error Number:" . curl_errno($ch) . "<br>";
	    echo "Error String:" . curl_error($ch);
	}
	curl_close($ch);
	return $output;
    }

}
