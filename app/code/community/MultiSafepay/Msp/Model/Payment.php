<?php

/**
 *
 * @category MultiSafepay
 * @package  MultiSafepay_Msp
 */
require_once(Mage::getBaseDir('lib') . DS . 'multisafepay' . DS . 'MultiSafepay.combined.php');

class MultiSafepay_Msp_Model_Payment extends Varien_Object {

    protected $_config;
    protected $_gateway;
    protected $_issuer;
    protected $_idealissuer;
    protected $_notification_url;
    protected $_cancel_url;
    protected $_return_url;
    protected $_order = null;
    public $base;
    public $api;
    public $payafterapi;
    public $pay_factor = 1;
    public $_configCode = 'msp';
    public $giftcards = array(
        'msp_webgift',
        'msp_ebon',
        'msp_babygiftcard',
        'msp_boekenbon',
        'msp_erotiekbon',
        'msp_giveacard',
        'msp_parfumnl',
        'msp_parfumcadeaukaart',
        'msp_degrotespeelgoedwinkel',
        'msp_yourgift',
        'msp_wijncadeau',
        'msp_lief',
        'msp_gezondheidsbon',
        'msp_fashioncheque',
        'msp_fashiongiftcard',
        'msp_podium',
        'msp_vvvgiftcard',
        'msp_sportenfit',
        'msp_beautyandwellness',
    );
    public $gateways = array(
        'msp_ideal',
        'msp_creditcard',
        'msp_dotpay',
        'msp_payafter',
        'msp_einvoice',
        'msp_klarna',
        'msp_mistercash',
        'msp_visa',
        'msp_eps',
        'msp_ferbuy',
        'msp_mastercard',
        'msp_banktransfer',
        'msp_maestro',
        'msp_paypal',
        'msp_giropay',
        'msp_multisafepay',
        'msp_directebanking',
        'msp_directdebit',
        'msp_amex',
    );

    /**
     * Set some vars
     */
    public function setNotificationUrl($url) {
        $this->_notification_url = $url;
    }

    public function setReturnUrl($url) {
        $this->_return_url = $url;
    }

    public function setCancelUrl($url) {
        $this->_cancel_url = $url;
    }

    public function setGateway($gateway) {
        $this->_gateway = $gateway;
    }

    public function setIdealIssuer($idealissuer) {
        $this->_idealissuer = $idealissuer;
    }

    public function setIssuer($issuer) {
        $this->_issuer = $issuer;
    }

    /**
     * Set the config object
     */
    public function setConfigObject($config) {
        $this->_config = $config;
        return $this;
    }

    function getConfigData($name) {
        if (isset($this->_config[$name])) {
            return $this->_config[$name];
        }

        return false;
    }

    /**
     * Returns an instance of the Base
     */
    public function getBase($id = null) {
        if ($this->base) {
            if ($id) {
                $this->base->setLogId($id);
                $this->base->setLockId($id);
            }

            return $this->base;
        }

        $this->base = Mage::getSingleton("msp/base");
        $this->base->setConfigObject($this->_config);
        $this->base->setLogId($id);
        $this->base->setLockId($id);

        return $this->base;
    }

    /**
     * Returns an instance of the Api
     */
    public function getApi($id = null) {
        if ($this->api) {
            if ($id) {
                $this->getBase($id);
            }

            return $this->api;
        }

        $base = $this->getBase($id);
        $this->api = $base->getApi();

        return $this->api;
    }

    /**
     * Returns an instance of the payafter Api
     */
    public function getPayAfterApi($id = null, $order = null) {
        if ($this->api) {
            if ($id) {
                $this->getBase($id);
            }
            return $this->api;
        }

        $base = $this->getBase($id);
        $this->api = $base->getPayAfterApi($order);

        return $this->api;
    }

    //get API based on new gateway specific configuration (added for coupons as they now use their own account settings)
    public function getGatewaysApi($id = null, $order = null, $gateway_code = null) {
        if ($this->api) {
            if ($id) {
                $this->getBase($id);
            }
            return $this->api;
        }

        $base = $this->getBase($id);
        $this->api = $base->getGatewaysApi($order, $gateway_code); //gateway code is like msp_payafter

        return $this->api;
    }

    /**
     * Get the current order object
     */
    public function getOrder() {
        if ($this->_order == null) {
            $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        }

        return $this->_order;
    }

    /**
     * Get the checkout order object
     */
    public function getCheckout() {
        return Mage::getSingleton("checkout/session");
    }

    /**
     * Get the gateway list
     */
    public function getGateways() {
        $billing = $this->getCheckout()->getQuote()->getBillingAddress();
        if ($billing) {
            $country = $billing->getCountry();
        } else {
            $country = "NL";
        }

        $api = $this->api;
        $api->customer['country'] = $country;

        // get the gateways
        $gateways = $api->getGateways();

        if ($api->error) {
            // let's not crash on a error with the gateway request
            return array();
        }

        return $gateways;
    }

    /**
     *    Function that will use the fastcheckout xml data to process connect transactions.
     *    For now this will only be used for pay after delivery.
     */
    public function startPayAfterTransaction() {
        $session = Mage::getSingleton('customer/session');

        /**
         *    We will check if the quote total is the same as the order total.
         *    Throw an exception if these amounts are not the same to avoid an transaction amount mismatch!
         */
        $orderId = $this->getCheckout()->getLastRealOrderId();
        $order = $this->getOrder();
        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());



        $gateway_data = $quote->getPayment()->getData();
        $gateway = strtoupper(str_replace("msp_", '', $gateway_data['method']));


        $quote_base_grand_total = $quote->getBaseGrandTotal();
        $order_base_grand_total = $order->getBaseGrandTotal();

        $quote_grand_total = $quote->getGrandTotal();
        $order_grand_total = $order->getGrandTotal();

        if ($quote_base_grand_total == $order_base_grand_total) {
            $checked_amount = $order_base_grand_total;
            $checked_amount_current = $order_grand_total;
        } else {
            Mage::throwException(Mage::helper("msp")->__("The cart total is not the same as the order total! Creation of the transaction is halted."));
        }

        // currency check
        $isAllowConvert = Mage::getStoreConfigFlag('msp/settings/allow_convert_currency');
        $currencies = explode(',', Mage::getStoreConfig('msp_gateways/' . strtolower($gateway_data['method']) . '/allowed_currency'));
        $canUseCurrentCurrency = in_array(Mage::app()->getStore()->getCurrentCurrencyCode(), $currencies);

        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        $baseCurrencyCode = Mage::app()->getBaseCurrencyCode();

        if ($order->getGlobalCurrencyCode() == 'EUR' && Mage::getStoreConfigFlag('msp/settings/allow_convert_currency')) {
            $amount = $order_base_grand_total;
            $currencyCode = 'EUR';
        } elseif ($canUseCurrentCurrency) {
            $amount = $checked_amount_current;
            $currencyCode = $currentCurrencyCode;
        } elseif ($isAllowConvert) {
            $targetCurrencyCode = MultiSafepay_Msp_Helper_Data::CONVERT_TO_CURRENCY_CODE;

            $amount = $this->_convertCurrency($checked_amount_current, $currentCurrencyCode, $targetCurrencyCode);
            $currencyCode = MultiSafepay_Msp_Helper_Data::CONVERT_TO_CURRENCY_CODE;
        } else {
            $amount = $checked_amount;
            $currencyCode = $baseCurrencyCode;
        }

        $amount = intval((string) (round($amount * 100)));
        $amount = round($amount * $this->pay_factor);

        $storename = Mage::app()->getStore()->getName();
        $billing = $this->getOrder()->getBillingAddress();
        $shipping = $this->getOrder()->getShippingAddress();

        $items = "<ul>\n";
        foreach ($this->getOrder()->getAllVisibleItems() as $item) {
            $items .= "<li>" . ($item->getQtyOrdered() * 1) . " x : " . $item->getName() . "</li>\n";
        }
        $items .= "</ul>\n";

        $isTestMode = $this->_isTestPayAfterDelivery($order->getPayment()->getMethodInstance()->getCode());

        $suffix = '';
        if ($isTestMode) {
            $suffix = '_test';
        }

        // build request
        $this->api = new MultiSafepay();
        $this->api->plugin_name = 'Magento';
        $this->api->version = Mage::getConfig()->getNode('modules/MultiSafepay_Msp/version');
        $this->api->use_shipping_notification = false;
        $this->api->merchant['account_id'] = $this->getConfigData("account_id_pad" . $suffix);
        $this->api->merchant['site_id'] = $this->getConfigData("site_id_pad" . $suffix);
        $this->api->merchant['site_code'] = $this->getConfigData("secure_code_pad" . $suffix);
        $this->api->plugin['shop'] = 'Magento';
        $this->api->plugin['shop_version'] = Mage::getVersion();
        $this->api->plugin['plugin_version'] = $this->api->version;
        $this->api->plugin['partner'] = '';
        $this->api->plugin['shop_root_url'] = '';

        $this->api->test = $isTestMode;
        $this->api->merchant['notification_url'] = $this->_notification_url . "?type=initial";
        $this->api->merchant['cancel_url'] = $this->_cancel_url;
        $this->api->merchant['redirect_url'] = $this->_return_url;
        //$this->api->merchant['redirect_url']     		= 	($this->getConfigData('use_redirect')) ? $this->_return_url.'?transactionid='.$orderId : '';
        $this->api->parseCustomerAddress($billing->getStreet(1));

        if ($this->api->customer['housenumber'] == '') {
            $this->api->customer['housenumber'] = $billing->getStreet(2);
            $this->api->customer['address1'] = $billing->getStreet(1);
        }


        $this->api->customer['locale'] = Mage::app()->getLocale()->getLocaleCode(); //Mage::app()->getLocale()->getDefaultLocale();
        $this->api->customer['firstname'] = $billing->getFirstname();
        $this->api->customer['lastname'] = $billing->getLastname();
        $this->api->customer['zipcode'] = $billing->getPostcode();
        $this->api->customer['city'] = $billing->getCity();
        $this->api->customer['state'] = $billing->getState();
        $this->api->customer['country'] = $billing->getCountry();
        $this->api->customer['phone'] = $billing->getTelephone();
        $this->api->customer['email'] = $this->getOrder()->getCustomerEmail();

        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->api->customer['referrer'] = $_SERVER['HTTP_REFERER'];
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->api->customer['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->api->customer['ipaddress'] = $_SERVER['REMOTE_ADDR'];

        $this->api->gatewayinfo['email'] = $this->getOrder()->getCustomerEmail();
        $this->api->gatewayinfo['phone'] = $billing->getTelephone();

        if (isset($_GET['gender'])) {
            $this->api->customer['gender'] = $_GET['gender'];
            $this->api->gatewayinfo['gender'] = $_GET['gender'];
        } else {
            $this->api->customer['gender'] = ''; //not available
            $this->api->gatewayinfo['gender'] = ''; //not available
        }


        if (isset($_GET['phonenumber'])) {
            $this->api->gatewayinfo['phone'] = $_GET['phonenumber'];
            $this->api->customer['phone'] = $_GET['phonenumber'];
        } else {
            $this->api->gatewayinfo['phone'] = ''; //not available
        }


        if (isset($_GET['accountnumber'])) {
            $this->api->gatewayinfo['bankaccount'] = $_GET['accountnumber'];
            $this->api->customer['bankaccount'] = $_GET['accountnumber'];
        } else {
            $this->api->gatewayinfo['bankaccount'] = ''; //not available
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->api->gatewayinfo['referrer'] = $_SERVER['HTTP_REFERER'];
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->api->gatewayinfo['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }

         if (isset($_GET['birthday'])) {
	        $birthday = str_replace('/', '-', $_GET['birthday']);
            $this->api->gatewayinfo['birthday'] = $birthday;
            $this->api->customer['birthday'] = $birthday;
        } else {
            $this->api->gatewayinfo['birthday'] = ''; //not available
        }

        if (($this->_gateway == "PAYAFTER" || $this->_gateway == "KLARNA") && $this->api->gatewayinfo['bankaccount'] != '' && $this->api->customer['birthday'] != '') {
            $this->api->transaction['special'] = true;
        }

        if ($this->_gateway == "EINVOICE") {
            $this->api->transaction['special'] = true;
        }

        $this->api->transaction['id'] = $orderId;
        $this->api->transaction['amount'] = $amount;
        $this->api->transaction['currency'] = $currencyCode;
        $this->api->transaction['var1'] = $session->getSessionId();
        $this->api->transaction['var2'] = Mage::helper('customer')->getCustomer()->getId();
        $this->api->transaction['var3'] = Mage::app()->getStore()->getStoreId();
        $this->api->transaction['description'] = Mage::helper("msp")->__("Order") . ' #' . $orderId . Mage::helper("msp")->__("@") . $storename;
        $this->api->transaction['gateway'] = $this->_gateway;
        $this->api->transaction['issuer'] = $this->_issuer;
        $this->api->transaction['items'] = $items;
        $this->api->transaction['daysactive'] = $this->getConfigData("pad_daysactive" . $suffix);
        $this->api->setDefaultTaxZones();

        $this->getItems($this->getOrder(), $currencyCode);

        $discountAmount = $this->getOrder()->getData('base_discount_amount');
        $discountAmount = $this->_convertCurrency($discountAmount, $baseCurrencyCode, $currencyCode);
        // $discountAmountFinal = round($discountAmount, 4);
        $discountAmountFinal = number_format($discountAmount, 4, '.', '');

        //Add discount line item
        if ($discountAmountFinal != 0) {
            $c_item = new MspItem('Discount', 'Discount', 1, $discountAmountFinal, 'KG', 0); // Todo adjust the amount to cents, and round it up.
            $c_item->SetMerchantItemId('Discount');
            $c_item->SetTaxTableSelector('BTW0');
            $this->api->cart->AddItem($c_item);
        }


        $taxClass = Mage::getStoreConfig('msp_gateways/msp_' . strtolower($this->_gateway) . '/fee_tax_class');

        if ($taxClass == 0) {
            $this->_rate = 1;
        }

        $taxCalculationModel = Mage::getSingleton('tax/calculation');
        $request = $taxCalculationModel->getRateRequest(
                $quote->getShippingAddress(), $quote->getBillingAddress(), $quote->getCustomerTaxClassId(), Mage::app()->getStore()->getId()
        );
        $request->setStore(Mage::app()->getStore())->setProductClassId($taxClass);

        $rate = $taxCalculationModel->getRate($request);
        $bigRate = 100 + $rate;
        $feeRate = $rate / 100;

        if ($feeRate == 0) {
            $feeRate = '0.00';
        }

        $table = new MspAlternateTaxTable();
        $table->name = 'FEE';
        $rule = new MspAlternateTaxRule($feeRate);
        $table->AddAlternateTaxRules($rule);
        $this->api->cart->AddAlternateTaxTables($table);

        //todo max the fee and tax configurable
        /* $tax = (Mage::getStoreConfig('msp/msp_payafter/fee_amount') / $bigRate) * $rate;

          $fee = Mage::getStoreConfig('msp/msp_payafter/fee_amount') - $tax;
          $fee = $this->_convertCurrency($fee, $baseCurrencyCode, $currencyCode);
          $fee = number_format($fee, 4, '.', ''); */

        $total_fee = 0;
        $fee = Mage::getStoreConfig('msp_gateways/msp_' . strtolower($this->_gateway) . '/fee_amount');
        $fee_array = explode(':', $fee);

        //fee is not configured
        if ($fee_array[0] != '') {
            $fixed_fee = str_replace(',', '.', $fee_array[0]);

            //check for configured percentage value
            if (!empty($fee_array[1])) {
                $fee_array[1] = str_replace(',', '.', $fee_array[1]);
                $serviceCostPercentage = str_replace('%', '', $fee_array[1]);
                //if the service cost is added, then first remove it before calcualting the fee
                if ($quote->getBaseServicecost()) {
                    $fee_percentage = ($quote->getBaseGrandTotal() - $quote->getBaseServicecost()) * ($serviceCostPercentage / 100);
                } else {
                    $fee_percentage = $quote->getBaseGrandTotal() * ($serviceCostPercentage / 100);
                }
                $total_fee += $fee_percentage;
            }
            $total_fee += $fixed_fee;
            $fee = $total_fee;
            $tax = ($fee / $bigRate) * $rate;
            $fee = $fee - $tax;


            //add pay after delivery fee if enabled
            if (Mage::getStoreConfig('msp_gateways/msp_' . strtolower($this->_gateway) . '/fee')) {
                $c_item = new MspItem('Fee', 'Fee', 1, $fee, 'KG', 0); // Todo adjust the amount to cents, and round it up.
                $c_item->SetMerchantItemId('payment-fee');
                $c_item->SetTaxTableSelector('FEE');
                $this->api->cart->AddItem($c_item);
            }
        }

        //add none taxtable
        $table = new MspAlternateTaxTable();
        $table->name = 'none';
        $rule = new MspAlternateTaxRule('0.00');
        $table->AddAlternateTaxRules($rule);
        $this->api->cart->AddAlternateTaxTables($table);

        //Add shipping line item
        $title = $this->getOrder()->getShippingDescription();

        //Code blow added to recalculate excluding tax for the shipping cost. Older Magento installations round differently, causing a 1 cent mismatch. This is why we recalculate it.
        $diff = $this->getOrder()->getShippingInclTax() - $this->getOrder()->getShippingAmount();
        $test = ($diff / $this->getOrder()->getShippingAmount()) * 100;
        $shipping_percentage = 1 + round($test, 0) / 100;
        $shippin_exc_tac_calculated = $this->getOrder()->getShippingInclTax() / $shipping_percentage;
        $percentage = round($test, 0) / 100;
        $price = number_format($this->_convertCurrency($shippin_exc_tac_calculated, $currentCurrencyCode, $currencyCode), 10, '.', '');
        /* End code */

        //$price = number_format($this->_convertCurrency($this->getOrder()->getShippingAmount(), $currentCurrencyCode, $currencyCode), 10, '.', '');

        /* $shipping_tax_id = 'none';

          if (is_array($this->_getShippingTaxRules())) {
          foreach ($this->_getShippingTaxRules() as $key => $value) {
          $shipping_tax_id = $key;
          }
          } elseif ($this->_getShippingTaxRules()) {
          $shipping_tax_id = $this->_getShippingTaxRules() / 100;
          $table = new MspAlternateTaxTable();
          $table->name = $shipping_tax_id;
          $rule = new MspAlternateTaxRule($shipping_tax_id);
          $table->AddAlternateTaxRules($rule);
          $this->api->cart->AddAlternateTaxTables($table);
          } */

        $table = new MspAlternateTaxTable();
        $table->name = $percentage;
        $rule = new MspAlternateTaxRule($percentage);
        $table->AddAlternateTaxRules($rule);
        $this->api->cart->AddAlternateTaxTables($table);


        $c_item = new MspItem($title, 'Shipping', 1, $price, 'KG', 0);
        $c_item->SetMerchantItemId('msp-shipping');
        $c_item->SetTaxTableSelector($percentage);
        $this->api->cart->AddItem($c_item);
        //End shipping line item
        //Add available taxes to the fco transaction request

        $this->getTaxes();


        /* Onestepcheckout compat fix. Some add a - when no phonenumber is added or the field is disabled */
        if ($this->api->customer['phone'] == '-' || $this->api->gatewayinfo['phone'] == '-') {
            $this->api->customer['phone'] = '';
            $this->api->gatewayinfo['phone'] = '';
        }



        //ALL data available? Then request the transaction link
        $url = $this->api->startCheckout();

        $this->getBase($orderId)->log($this->api->request_xml);
        $this->getBase($orderId)->log($this->api->reply_xml);

        if (!$this->api->error and $url == false) {
            $url = $this->api->merchant['redirect_url'] . '?transactionid=' . $orderId;
        }

        // error
        if ($this->api->error) {
            $this->getBase()->log("Error %s: %s", $this->api->error_code, $this->api->error);

            // add error status history
            $this->getOrder()->addStatusToHistory($this->getOrder()->getStatus(), Mage::helper("msp")->__("Error creating transaction") . '<br/>' . $this->api->error_code . " - " . $this->api->error);
            $this->getOrder()->save();

            // raise error
            //Mage::throwException(Mage::helper("msp")->__("An error occured: ") . $this->api->error_code . " - " . $this->api->error);
            if ($this->api->error_code == '1024' && $this->_gateway != "EINVOICE" && $this->_gateway != "KLARNA") {
                $errorMessage = Mage::helper("msp")->__("An error occured: ") . $this->api->error_code . /* " - " . $this->api->error . */ '<br />' . Mage::helper("msp")->__('We are sorry to inform you that your request for payment after delivery has been denied by Multifactor.<BR /> If you have questions about this rejection, you can checkout the FAQ on the website of Multifactor') . '<a href="http://www.multifactor.nl/contact" target="_blank">http://www.multifactor.nl/faq</a>' . Mage::helper("msp")->__('You can also contact Multifactor by calling 020-8500533 (at least 2 hours after this rejection) or by sending an email to ') . ' <a href="mailto:support@multifactor.nl">support@multifactor.nl</a>.' . Mage::helper("msp")->__('Please retry placing your order and select a different payment method.');
            }elseif($this->_gateway == "EINVOICE" && $this->api->error_code == '1024' ){
                $errorMessage = Mage::helper("msp")->__("An error occured: ") . $this->api->error_code . /* " - " . $this->api->error . */ '<br />' . Mage::helper("msp")->__('We are sorry to inform you that your request for E-invoicing has been denied.<BR /> Please select another payment method and try again');
            }else {
                $errorMessage = Mage::helper("msp")->__("An error occured: ") . $this->api->error_code . " - " . $this->api->error . '<br />' . Mage::helper("msp")->__("Please retry placing your order and select a different payment method.");
            }
            Mage::log($errorMessage);

            Mage::getSingleton('checkout/session')->addError($errorMessage);
            session_write_close();
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
            Mage::app()->getResponse()->sendResponse();
            $newState = Mage_Sales_Model_Order::STATE_CANCELED;
            $statusMessage = Mage::helper("msp")->__("Canceled because of transaction request error");
            $newStatus = $this->getConfigData("void_status");

            $order->cancel(); // this trigers stock updates
            $order->setState($newState, $newStatus, $statusMessage)->save();

            exit;
        }

        // save payment link to status history
        if ($this->getConfigData("save_payment_link") || true) {
            $this->getOrder()->addStatusToHistory($this->getOrder()->getStatus(), Mage::helper("msp")->__("User redirected to MultiSafepay") . '<br/>' . Mage::helper("msp")->__("Payment link:") . '<br/>' . $url);
            $this->getOrder()->save();
        }

        $send_order_email = $this->getConfigData("new_order_mail");


        if ($this->getOrder()->getCanSendNewEmailFlag()) {
            if ($send_order_email == 'after_confirmation') {
                if (!$this->getOrder()->getEmailSent()) {
                    $this->getOrder()->sendNewOrderEmail();
                    $this->getOrder()->setEmailSent(true);
                    $this->getOrder()->save();
                }
            }
        }

        return $url;
    }

    /**
     * @return bool
     */
    protected function _isTestPayAfterDelivery($gateway) {
        $isTest = ($this->getConfigData('test_api_pad') == MultiSafepay_Msp_Model_Config_Sources_Accounts::TEST_MODE);
        if ($isTest) {
            return true;
        }

        if ($ips = Mage::getStoreConfig('msp_gateways/' . $gateway . '/ip_filter_test_for_live_mode')) {
            if (in_array($_SERVER["REMOTE_ADDR"], explode(';', $ips))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function _isTestGiftcard($gateway) {
        $isTest = ($this->getConfigData('test_api_pad') == MultiSafepay_Msp_Model_Config_Sources_Accounts::TEST_MODE);
        if ($isTest) {
            return true;
        }
        return false;
    }

    protected function getTaxes() {
        $this->_getTaxTable($this->_getShippingTaxRules(), 'default');
        $this->_getTaxTable($this->_getTaxRules(), 'alternate');
        $this->_getTaxTable($this->_getShippingTaxRules(), 'alternate');
        // add 'none' group?
    }

    protected function _getTaxTable($rules, $type) {
        if (is_array($rules)) {
            foreach ($rules as $group => $taxRates) {
                if ($type != 'default') {
                    $table = new MspAlternateTaxTable($group, 'true');
                    $shippingTaxed = 'false';
                } else {
                    $shippingTaxed = 'true';
                }

                if (is_array($taxRates)) {
                    foreach ($taxRates as $rate) {
                        if ($type != 'default') {
                            $rule = new MspAlternateTaxRule($rate['value']);
                            $rule->AddPostalArea($rate['country']);
                            $table->AddAlternateTaxRules($rule);
                        } else {
                            $rule = new MspDefaultTaxRule($rate['value'], $shippingTaxed);
                            $rule->AddPostalArea($rate['country']);
                            $this->api->cart->AddDefaultTaxRules($rule);
                        }
                    }
                } else {
                    $taxRate = $taxRates / 100;
                    if ($type != 'default') {
                        $rule = new MspAlternateTaxRule($taxRate);
                        $rule->SetWorldArea();

                        $table->AddAlternateTaxRules($rule);
                    } else {
                        $rule = new MspDefaultTaxRule($taxRate, $shippingTaxed);
                        $rule->SetWorldArea();
                        $this->api->cart->AddDefaultTaxRules($rule);
                    }
                }
                if ($type != 'default') {
                    $this->api->cart->AddAlternateTaxTables($table);
                }
            }
        } else {
            if (is_numeric($rules)) {
                $taxRate = $rules / 100;
                if ($type != 'default') {
                    $table = new MspAlternateTaxTable();
                    $rule = new MspAlternateTaxRule($taxRate);
                    $rule->SetWorldArea();
                    $table->AddAlternateTaxRules($rule);
                    $this->api->cart->AddAlternateTaxTables($table);
                    // print_r($table);//Validate this one!
                } else {
                    $rule = new MspDefaultTaxRule($taxRate, 'true');
                    $rule->SetWorldArea();
                    $this->api->cart->AddDefaultTaxRules($rule);
                }
            }
        }
    }

    protected function _getTaxRules() {
        $customerTaxClass = $this->_getCustomerTaxClass();
        if (Mage::helper('tax')->getTaxBasedOn() == 'origin') {
            $request = Mage::getSingleton('tax/calculation')->getRateRequest();
            return Mage::getSingleton('tax/calculation')->getRatesForAllProductTaxClasses($request->setCustomerClassId($customerTaxClass));
        } else {
            $customerRules = Mage::getSingleton('tax/calculation')->getRatesByCustomerTaxClass($customerTaxClass);
            $rules = array();
            foreach ($customerRules as $rule) {
                $rules[$rule['product_class']][] = $rule;
            }

            return $rules;
        }
    }

    protected function _getShippingTaxRules() {
        $customerTaxClass = $this->_getCustomerTaxClass();

        //validate the returned data. Doesn't work with connect pad
        if ($shippingTaxClass = Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_SHIPPING_TAX_CLASS, $this->getOrder()->getStoreId())) {
            if (Mage::helper('tax')->getTaxBasedOn() == 'origin') {
                $request = Mage::getSingleton('tax/calculation')->getRateRequest();
                $request->setCustomerClassId($customerTaxClass)->setProductClassId($shippingTaxClass);
                return Mage::getSingleton('tax/calculation')->getRate($request);
            }
            $customerRules = Mage::getSingleton('tax/calculation')->getRatesByCustomerAndProductTaxClasses($customerTaxClass, $shippingTaxClass);
            $rules = array();
            foreach ($customerRules as $rule) {
                $rules[$rule['product_class']][] = $rule;
            }

            return $rules;
        }

        return array();
    }

    protected function _getCustomerTaxClass() {
        $customerGroup = $this->getOrder()->getCustomerGroupId();
        if (!$customerGroup) {
            $customerGroup = Mage::getStoreConfig('customer/create_account/default_group', $this->getOrder()->getStoreId());
        }

        return Mage::getModel('customer/group')->load($customerGroup)->getTaxClassId();
    }

    protected function getItems($order, $targetCurrencyCode) {
        $items = $order->getAllItems();


        foreach ($items as $item) {
            $product_id = $item->getProductId();


            foreach ($order->getAllItems() as $order_item) {
                $order_product_id = $order_item->getProductId();
                if ($order_product_id == $product_id) {
                    //$quantity = round($order_item->getQtyOrdered(), 2);
                    //changed to solve bug with 1027 error because of product quantity mismatch.
                    $quantity = $item->getQtyOrdered();
                }
            }

            if ($item->getParentItem()) {
                continue;
            }
            $taxClass = ($item->getTaxPercent() == 0 ? 'none' : $item->getTaxPercent());
            $rate = $item->getTaxPercent() / 100;

            $table = new MspAlternateTaxTable();
            $table->name = $item->getTaxPercent();
            $rule = new MspAlternateTaxRule($rate);
            $table->AddAlternateTaxRules($rule);
            $this->api->cart->AddAlternateTaxTables($table);

            $weight = (float) $item->getWeight();
            $product_id = $item->getProductId();

            // name and options
            $itemName = $item->getName();
            $options = $this->getProductOptions($item);
            if (!empty($options)) {
                $optionString = '';
                foreach ($options as $option) {
                    $optionString = $option['label'] . ": " . $option['print_value'] . ",";
                }
                $optionString = substr($optionString, 0, -1);

                $itemName .= ' (';
                $itemName .= $optionString;
                $itemName .= ')';
            }

            $proddata = Mage::getModel('catalog/product')->load($product_id);
            $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();

            //$quantity = round($item->getQtyOrdered(), 2);

            $ndata = $item->getData();


            if ($this->api->transaction['gateway'] == 'KLARNA') {
                $rounding = 10;
            } else {
                $rounding = 10;
            }

            if ($ndata['price'] != 0) {
                //Test-> Magento rounds at 2 decimals so the recalculation goes wrong with large quantities.
                /* $price_with_tax = $ndata['price_incl_tax'];
                  $tax_rate = $rate;
                  $divided_value = 1 + ($tax_rate);
                  $price_without_tax = $price_with_tax / $divided_value;
                  $price = round($price_without_tax, 4); */

                $price = number_format($this->_convertCurrency($ndata['price'], $currentCurrencyCode, $targetCurrencyCode), $rounding, '.', '');

                $tierprices = $proddata->getTierPrice();
                if (count($tierprices) > 0) {
                    $product_tier_prices = (object) $tierprices;
                    $product_price = array();
                    foreach ($product_tier_prices as $key => $value) {
                        $value = (object) $value;
                        if ($item->getQtyOrdered() >= $value->price_qty)
                        /* $price_with_tax = $value->price;
                          $tax_rate = $rate;
                          $divided_value = 1 + ($tax_rate);
                          $price_without_tax = $price_with_tax / $divided_value;
                          $price = round($price_without_tax, 4); */
                            if ($ndata['price'] < $value->price) {
                                $price = $ndata['price'];
                            } else {
                                $price = $value->price;
                            }

                        $price = number_format($this->_convertCurrency($price, $currentCurrencyCode, $targetCurrencyCode), $rounding, '.', '');
                    }
                }

                // Fix for 1027 with catalog prices including tax 
                if (Mage::getStoreConfig('tax/calculation/price_includes_tax')) {
                    $price = ($item->getRowTotalInclTax() / $item->getQtyOrdered() / (1 + ($item->getTaxPercent() / 100)));
                    $price = round($price, $rounding);
                }


                // create item
                $c_item = new MspItem($itemName, $item->getDescription(), $quantity, $price, 'KG', $item->getWeight());
                $c_item->SetMerchantItemId($item->getId());
                $c_item->SetTaxTableSelector($taxClass);
                $this->api->cart->AddItem($c_item);
            }
        }
    }

    /**
     * Send a transaction request to MultiSafepay and return the payment_url
     */
    public function startTransaction() {
        $session = Mage::getSingleton('customer/session');

        /**
         *    We will check if the quote total is the same as the order total.
         *    Throw an exception if these amounts are not the same to avoid an transaction amount mismatch!
         */
        $orderId = $this->getCheckout()->getLastRealOrderId();
        $order = $this->getOrder();
        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());

        $quote_base_grand_total = $quote->getBaseGrandTotal();
        $order_base_grand_total = $order->getBaseGrandTotal();

        $quote_grand_total = $quote->getGrandTotal();
        $order_grand_total = $order->getGrandTotal();


        $checked_amount = $order_base_grand_total;
        $checked_amount_current = $order_grand_total;


        /*
          Code below is disabled because this check causes error with different thirt party modules. Also this was more meant for BNO, so in stratPayAfterTransaction it remains.

          if ($quote_base_grand_total == $order_base_grand_total) {
          $checked_amount         =     $order_base_grand_total;
          $checked_amount_current =     $order_grand_total;
          } else {
          Mage::throwException(Mage::helper("msp")->__("The cart total is not the same as the order total! Creation of the transaction is halted."));
          } */

        $gateway_data = $quote->getPayment()->getData();

        if (in_array($gateway_data['method'], $this->gateways)) {
            $this->_configCode = 'msp_gateways';
        } elseif (in_array($gateway_data['method'], $this->giftcards)) {
            $this->_configCode = 'msp_giftcards';
        }


        // currency check
        $isAllowConvert = Mage::getStoreConfigFlag('msp/settings/allow_convert_currency');

        if ($gateway_data['method'] == 'msp') {
            $currencies = explode(',', Mage::getStoreConfig('payment/msp/allowed_currency'));
        } else {
            $currencies = explode(',', Mage::getStoreConfig($this->_configCode . '/' . strtolower($gateway_data['method']) . '/allowed_currency'));
        }

        $canUseCurrentCurrency = in_array(Mage::app()->getStore()->getCurrentCurrencyCode(), $currencies);

        if ($order->getGlobalCurrencyCode() == 'EUR' && Mage::getStoreConfigFlag('msp/settings/allow_convert_currency')) {
            $amount = $order_base_grand_total;
            $currencyCode = 'EUR';
        } elseif ($canUseCurrentCurrency) {
            $amount = $checked_amount_current;
            $currencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        } elseif ($isAllowConvert) {
            $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
            $targetCurrencyCode = MultiSafepay_Msp_Helper_Data::CONVERT_TO_CURRENCY_CODE;

            $amount = $this->_convertCurrency($checked_amount_current, $currentCurrencyCode, $targetCurrencyCode);
            $currencyCode = MultiSafepay_Msp_Helper_Data::CONVERT_TO_CURRENCY_CODE;
        } else {
            $amount = $checked_amount;
            $currencyCode = Mage::app()->getBaseCurrencyCode();
        }

        $amount = intval((string) (round($amount * 100)));
        $amount = round($amount * $this->pay_factor);

        $storename = Mage::app()->getStore()->getName();
        $billing = $this->getOrder()->getBillingAddress();
        $shipping = $this->getOrder()->getShippingAddress();

        // generate items list
        $items = "<ul>\n";
        foreach ($this->getOrder()->getAllVisibleItems() as $item) {
            $items .= "<li>" . ($item->getQtyOrdered() * 1) . " x : " . $item->getName() . "</li>\n";
        }
        $items .= "</ul>\n";

        // build request
        //if selected gateway was ebon or giftcard then use those account config
        if ($gateway_data['method'] == 'msp_ebon' ||
                $gateway_data['method'] == 'msp_webgift' ||
                $gateway_data['method'] == 'msp_babygiftcard' ||
                $gateway_data['method'] == 'msp_boekenbon' ||
                $gateway_data['method'] == 'msp_degrotespeelgoedwinkel' ||
                $gateway_data['method'] == 'msp_erotiekbon' ||
                $gateway_data['method'] == 'msp_giveacard' ||
                $gateway_data['method'] == 'msp_fashioncheque' ||
                $gateway_data['method'] == 'msp_fashiongiftcard' ||
                $gateway_data['method'] == 'msp_gezondheidsbon' ||
                $gateway_data['method'] == 'msp_lief' ||
                $gateway_data['method'] == 'msp_parfumcadeaukaart' ||
                $gateway_data['method'] == 'msp_parfumnl' ||
                $gateway_data['method'] == 'msp_wijncadeau' ||
                $gateway_data['method'] == 'msp_podium' ||
                $gateway_data['method'] == 'msp_vvvgiftcard' ||
                $gateway_data['method'] == 'msp_sportenfit' ||
                $gateway_data['method'] == 'msp_beautyandwellness' ||
                $gateway_data['method'] == 'msp_yourgift'
        ) {
            $api = $this->getGatewaysApi(null, $order, $gateway_data['method']);
        } else {
            $api = $this->getApi();
        }
        //$api->test = ($this->getConfigData("test_api") == 'test');
        $api->merchant['notification_url'] = $this->_notification_url . "?type=initial";
        $api->merchant['cancel_url'] = $this->_cancel_url;
        $api->merchant['redirect_url'] = ($this->getConfigData('use_redirect')) ? $this->_return_url : '';
        //$api->merchant['redirect_url']     	   = 	($this->getConfigData('use_redirect')) ? $this->_return_url.'?transactionid='.$orderId : '';
        $api->customer['locale'] = Mage::app()->getLocale()->getLocaleCode(); //Mage::app()->getLocale()->getDefaultLocale();

        if (is_object($billing)) {
            $this->api->parseCustomerAddress($billing->getStreet(1));

            if ($this->api->customer['housenumber'] == '') {
                $this->api->customer['housenumber'] = $billing->getStreet(2);
                $this->api->customer['address1'] = $billing->getStreet(1);
            }

            $api->customer['firstname'] = $billing->getFirstname();
            $api->customer['lastname'] = $billing->getLastname();
            //$api->customer['address2']           =     $billing->getStreet(2);
            $api->customer['zipcode'] = $billing->getPostcode();
            $api->customer['city'] = $billing->getCity();
            $api->customer['state'] = $billing->getState();
            $api->customer['country'] = $billing->getCountry();
            $api->customer['phone'] = $billing->getTelephone();
        }
        $api->customer['email'] = $this->getOrder()->getCustomerEmail();
        if (isset($_SERVER['HTTP_REFERER'])) {
            $api->customer['referrer'] = $_SERVER['HTTP_REFERER'];
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $api->customer['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }


        //add shipping details, used for PayPal
        if (is_object($shipping)) {
            $api->parseDeliveryAddress($shipping->getStreet(1));

            if ($api->delivery['housenumber'] == '') {
                $api->delivery['housenumber'] = $shipping->getStreet(2);
                $api->delivery['address1'] = $shipping->getStreet(1);
            }

            $api->delivery['firstname'] = $shipping->getFirstname();
            $api->delivery['lastname'] = $shipping->getLastname();
            $api->delivery['address2'] = $shipping->getStreet(2);
            $api->delivery['zipcode'] = $shipping->getPostcode();
            $api->delivery['city'] = $shipping->getCity();
            $api->delivery['state'] = $shipping->getState();
            $api->delivery['country'] = $shipping->getCountry();
            $api->delivery['phone'] = $shipping->getTelephone();
        }


        $api->transaction['id'] = $orderId;
        $api->transaction['amount'] = $amount;
        $api->transaction['currency'] = $currencyCode;
        $api->transaction['var1'] = $session->getSessionId();
        $api->transaction['var2'] = Mage::helper('customer')->getCustomer()->getId();
        $api->transaction['var3'] = Mage::app()->getStore()->getStoreId();
        $api->transaction['description'] = Mage::helper("msp")->__("Order") . ' #' . $orderId . Mage::helper("msp")->__("@") . $storename;
        $api->transaction['items'] = $items;
        $api->transaction['gateway'] = $this->_gateway;
        $api->transaction['daysactive'] = $this->getConfigData("daysactive");
        if ($api->transaction['gateway'] == '') {
            $api->transaction['gateway'] = $this->_gateway;
        }




        $ideal_issuer = "";
        $session = Mage::getSingleton('checkout/session');
        $payment_data = $session->getData('payment_additional');

		$iss=$payment_data->msp_ideal_bank;
		$cc= $payment_data->msp_creditcard_cc;

        if (is_object($payment_data)) {
	        if(!empty($iss)){
            	$ideal_issuer = $payment_data->msp_ideal_bank;
            }elseif(!empty($cc)){
	            $api->transaction['gateway'] = $payment_data->msp_creditcard_cc;
            }
        }


        if ($this->_gateway == 'IDEAL' && $ideal_issuer) {
            $api->transaction['issuer'] = $ideal_issuer;
            $api->transaction['gateway'] = 'IDEAL';
            $api->merchant['redirect_url'] = $this->_return_url;
            $api->extravars = $ideal_issuer;
            $url = $api->startDirectXMLTransaction();
        } elseif ($this->_gateway == 'BANKTRANS' && Mage::getStoreConfig('msp_gateways/msp_banktransfer/direct_transfer')) {
            $data = $api->startDirectBankTransfer();

            if (!$api->error) {
                Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false);
                Mage::getSingleton('checkout/session')->getQuote()->save();
            }

            $url = Mage::getUrl("checkout/onepage/success/", array("_secure" => true));
        } else {
            //$url = $this->api->startCheckout();
            $url = $api->startTransaction();
        }

        $this->getBase($orderId)->log($api->request_xml);
        $this->getBase($orderId)->log($api->reply_xml);

        // error
        if ($api->error) {
            $this->getBase()->log("Error %s: %s", $api->error_code, $api->error);

            // add error status history
            $this->getOrder()->addStatusToHistory($this->getOrder()->getStatus(), Mage::helper("msp")->__("Error creating transaction") . '<br/>' . $api->error_code . " - " . $api->error);

            if ($orderId = !'') {
                $this->getOrder()->save();
                // raise error
                //Mage::throwException(Mage::helper("msp")->__("An error occured: ") . $api->error_code . " - " . $api->error);
                $errorMessage = Mage::helper("msp")->__("An error occured: ") . $api->error_code . " - " . $api->error . '<br />' . Mage::helper("msp")->__("Please retry placing your order and select a different payment method.");
                Mage::log($errorMessage);

                Mage::getSingleton('checkout/session')->addError($errorMessage);
                session_write_close();
                Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
                Mage::app()->getResponse()->sendResponse();
            } else {
                //Mage::throwException(Mage::helper("msp")->__("An error occured: ") . $api->error_code . " - " . $api->error);
                $errorMessage = Mage::helper("msp")->__("An error occured: ") . '<br />' . Mage::helper("msp")->__("Please retry placing your order and select a different payment method.");
                Mage::log($errorMessage);

                Mage::getSingleton('checkout/session')->addError($errorMessage);
                session_write_close();
                Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
                Mage::app()->getResponse()->sendResponse();
            }
            $newState = Mage_Sales_Model_Order::STATE_CANCELED;
            $statusMessage = Mage::helper("msp")->__("Canceleld because of transaction request error");
            $newStatus = $this->getConfigData("void_status");

            $order->cancel(); // this trigers stock updates
            $order->setState($newState, $newStatus, $statusMessage)->save();
            exit;
        }

        // save payment link to status history
        if ($this->getConfigData("save_payment_link") || true) {
            $this->getOrder()->addStatusToHistory($this->getOrder()->getStatus(), Mage::helper("msp")->__("User redirected to MultiSafepay") . '<br/>' . Mage::helper("msp")->__("Payment link:") . '<br/>' . $url);
            $this->getOrder()->save();
        }

        $send_order_email = $this->getConfigData("new_order_mail");
        if ($this->getOrder()->getCanSendNewEmailFlag()) {
            if ($send_order_email == 'after_confirmation') {
                if (!$this->getOrder()->getEmailSent()) {
                    $this->getOrder()->sendNewOrderEmail();
                    $this->getOrder()->setEmailSent(true);
                    $this->getOrder()->save();
                }
            }
        }

        return $url;
    }

    public function notification($orderId, $initial = false) {
        // get the order
        /** @var $order Mage_Sales_Model_Order */
        $order = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);

        $base = $this->getBase($orderId);

        // check lock
        if ($base->isLocked()) {
            $base->preventLockDelete();

            if ($initial) {
                return false;
            } else {

                return false;
            }
        }

        // lock
        $base->lock();

        $orderexist = $order->getIncrementId();


        if ($orderexist) {
            $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
        } else {
            $orderId = $_GET['transactionid'];
            $payment_method_code = '';
        }

        // get the status
        if ($payment_method_code == 'msp_payafter' || $payment_method_code == 'msp_klarna' || $payment_method_code == 'msp_einvoice') {
            $api = $this->getPayAfterApi($orderId, $order);
        } elseif ($payment_method_code == 'msp_ebon' ||
                $payment_method_code == 'msp_webgift' ||
                $payment_method_code == 'msp_babygiftcard' ||
                $payment_method_code == 'msp_boekenbon' ||
                $payment_method_code == 'msp_degrotespeelgoedwinkel' ||
                $payment_method_code == 'msp_erotiekbon' ||
                $payment_method_code == 'msp_giveacard' ||
                $payment_method_code == 'msp_fashioncheque' ||
                $payment_method_code == 'msp_fashiongiftcard' ||
                $payment_method_code == 'msp_gezondheidsbon' ||
                $payment_method_code == 'msp_lief' ||
                $payment_method_code == 'msp_parfumcadeaukaart' ||
                $payment_method_code == 'msp_parfumnl' ||
                $payment_method_code == 'msp_podium' ||
                $payment_method_code == 'msp_vvvgiftcard' ||
                $payment_method_code == 'msp_sportenfit' ||
                $payment_method_code == 'msp_beautyandwellness' ||
                $payment_method_code == 'msp_wijncadeau' ||
                $payment_method_code == 'msp_yourgift'
        ) {
            $api = $this->getGatewaysApi(null, $order, $payment_method_code);
        } else {
            $api = $this->getApi($orderId);
        }

        $api->transaction['id'] = $orderId;
        $status = $api->getStatus();

        // log the transaction status requests and responses
        $this->getBase($orderId)->log($this->api->request_xml);
        $this->getBase($orderId)->log($this->api->reply_xml);
        
        /** @var $helper MultiSafepay_Msp_Helper_Data */
        $helper = Mage::helper('msp');
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getSingleton('sales/quote')->load($order->getQuoteId());
        $isRestored = $helper->restoreCart($quote, $status);
        $base->log("Quote was restored: " . ($isRestored ? 'TRUE' : 'FALSE'));

        if ($api->error) {
            $base->unlock();
            Mage::throwException(Mage::helper("msp")->__("An error occured: ") . $api->error_code . " - " . $api->error);
            echo 'Error : ' . $api->error_code . " - " . $api->error;
            exit();
        }

        // determine status
        $status = strtolower($status);

        // update order status in Magento
        $ret = $base->updateStatus($order, $status, $api->details);

        // unlock
        $base->unlock();

        return $ret;
    }

    /**
     * @param float  $amount
     * @param string $currentCurrencyCode
     * @param string $targetCurrencyCode
     * @return float
     */
    protected function _convertCurrency($amount, $currentCurrencyCode, $targetCurrencyCode) {
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
