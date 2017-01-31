<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pcstudio extends PC_Controller {

    public function __construct() {
	parent::__construct();
	$this->pc_rootURL = get_base_url();
	$getdata = $this->input->get();
	$this->plateform_solution = plateform_solutions(PLATEFORM);
	$this->load->library('curl');
	$this->load->model('Designnbuy_common_webservice_model');
	$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, must-revalidate, post-check=0, pre-check=0');
	$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    }

    public function index() {
	$param = $this->input->get();
	$param['siteBaseUrl'] = unserialize(base64_decode($param['siteBaseUrl']));
	//$param['language_id'] = 'es';
	$param['language_id'] = $this->getLanguageIdBasedOnConnector($param['language_id']);
	$data['locale'] = $this->Designnbuy_common_webservice_model->getLanguageIsoCode($param['language_id']);
	$data['postData'] = json_encode($param);
	$data['languagedata'] = addslashes(json_encode($this->Designnbuy_common_webservice_model->getLanguageData($param['language_id'])));
	$data['siteBaseUrl'] = $param['siteBaseUrl'];
	$data['configurefeature'] = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();	
	$xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
	$xmldata = simplexml_load_file($xml);
	/*$current_value = $xmldata->option_2->current_value;
	if(isset($current_value) && $current_value != '') {
	    $data['svgcolor'] = $current_value;
	} else {
	    $data['svgcolor'] = $xmldata->option_2->default_value;
	}*/
	$current_value_primary = $xmldata->option_1->current_value;
	if(isset($current_value_primary) && $current_value_primary != '') {
	    $data['primary_color'] = $current_value_primary;
	} else {
	    $data['primary_color'] = $xmldata->option_1->default_value;
	}

		
	$current_value = $xmldata->option_2->current_value;
	if(isset($current_value) && $current_value != '') {
	    $data['svgcolor'] = $current_value;
	} else {
	    $data['svgcolor'] = $xmldata->option_2->default_value;
	}
	
	$current_hover_value = $xmldata->option_3->current_value;	
	if(isset($current_hover_value) && $current_hover_value != '') {
	    $data['svgcolor_hover'] = $current_hover_value;
	} else {
	    $data['svgcolor_hover'] = $xmldata->option_3->default_value;
	}

	$secondary_background_value = $xmldata->option_4->current_value;	
	if(isset($secondary_background_value) && $secondary_background_value != '') {
	    $data['secondary_value'] = $secondary_background_value;
	} else {
	    $data['secondary_value'] = $secondary_background_value->option_4->default_value;
	}
	$this->load->view('pcstudio/tool', $data);
    }

    public function editMyDesign() {
		$getdata = $this->input->get();
		$result = $this->Designnbuy_common_webservice_model->getMyDesignByDesignId($getdata['design_id']);
		$param['product_id'] = $result['product_id'];
		$param['qty'] = '1';
		$param['color_id'] = $result['color_id'];
		$param['size_id'] = $result['size_id'];
		$extraoptions = json_decode($result['product_options_id'], true);
		$param['extraoptions'] = base64_encode(serialize($extraoptions));
		$param['design_id'] = $getdata['design_id'];
		$param['design_name'] = $result['design_name'];
		$param['plateform'] = $getdata['plateform'];
		$param['language_id'] = $this->getLanguageIdBasedOnConnector($getdata['language_id']);
		$data['locale'] = $this->Designnbuy_common_webservice_model->getLanguageIsoCode($param['language_id']);
		$param['siteBaseUrl'] = unserialize(base64_decode($getdata['siteBaseUrl']));
		$param['currency_symbol'] = $getdata['currency_symbol'];
		$param['namenumData'] = $result['name_number_data'];
		$data['postData'] = json_encode($param);
		$data['languagedata'] = addslashes(json_encode($this->Designnbuy_common_webservice_model->getLanguageData($param['language_id'])));
		$data['siteBaseUrl'] = $param['siteBaseUrl'];
		$data['configurefeature'] = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();
		$xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
		$xmldata = simplexml_load_file($xml);
		$current_value_primary = $xmldata->option_1->current_value;
		if(isset($current_value_primary) && $current_value_primary != '') {
			$data['primary_color'] = $current_value_primary;
		} else {
			$data['primary_color'] = $xmldata->option_1->default_value;
		}


		$current_value = $xmldata->option_2->current_value;
		if(isset($current_value) && $current_value != '') {
			$data['svgcolor'] = $current_value;
		} else {
			$data['svgcolor'] = $xmldata->option_2->default_value;
		}

		$current_hover_value = $xmldata->option_3->current_value;
		if(isset($current_hover_value) && $current_hover_value != '') {
			$data['svgcolor_hover'] = $current_hover_value;
		} else {
			$data['svgcolor_hover'] = $xmldata->option_3->default_value;
		}

		$secondary_background_value = $xmldata->option_4->current_value;
		if(isset($secondary_background_value) && $secondary_background_value != '') {
			$data['secondary_value'] = $secondary_background_value;
		} else {
			$data['secondary_value'] = $secondary_background_value->option_4->default_value;
		}
		$this->load->view('pcstudio/tool', $data);
    }
    
    public function editCart() {
	$getdata = $this->input->get();
	$result = $this->Designnbuy_common_webservice_model->getDesignDataByCartDesignId($getdata['cart_design_id']);
	$param['color_id'] = $getdata['color_id'];
	$param['size_id'] = $getdata['size_id'];
	$param['product_id'] = $getdata['product_id'];
	$param['qty'] = $getdata['qty'];
	$param['extraoptions'] = $getdata['extraoptions'];
	$param['cart_id'] = $getdata['cart_id'];
	$param['cart_design_id'] = $getdata['cart_design_id'];
	$param['plateform'] = $getdata['plateform'];
	$param['language_id'] = $this->getLanguageIdBasedOnConnector($getdata['language_id']);
	$data['locale'] = $this->Designnbuy_common_webservice_model->getLanguageIsoCode($param['language_id']);
	$param['siteBaseUrl'] = unserialize(base64_decode($getdata['siteBaseUrl']));
	$param['namenumData'] = $result['name_number_data'];
	$data['postData'] = json_encode($param);
	$data['languagedata'] = addslashes(json_encode($this->Designnbuy_common_webservice_model->getLanguageData($param['language_id'])));
	$data['siteBaseUrl'] = $param['siteBaseUrl'];
	$data['configurefeature'] = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();
	$xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
	$xmldata = simplexml_load_file($xml);
	$current_value_primary = $xmldata->option_1->current_value;
	if(isset($current_value_primary) && $current_value_primary != '') {
	    $data['primary_color'] = $current_value_primary;
	} else {
	    $data['primary_color'] = $xmldata->option_1->default_value;
	}

		
	$current_value = $xmldata->option_2->current_value;
	if(isset($current_value) && $current_value != '') {
	    $data['svgcolor'] = $current_value;
	} else {
	    $data['svgcolor'] = $xmldata->option_2->default_value;
	}
	
	$current_hover_value = $xmldata->option_3->current_value;	
	if(isset($current_hover_value) && $current_hover_value != '') {
	    $data['svgcolor_hover'] = $current_hover_value;
	} else {
	    $data['svgcolor_hover'] = $xmldata->option_3->default_value;
	}

	$secondary_background_value = $xmldata->option_4->current_value;	
	if(isset($secondary_background_value) && $secondary_background_value != '') {
	    $data['secondary_value'] = $secondary_background_value;
	} else {
	    $data['secondary_value'] = $secondary_background_value->option_4->default_value;
	}
	$this->load->view('pcstudio/tool', $data);
    }

    public function addCartDesign() {
	$data = $this->input->post();
	$cart_design_id = $this->Designnbuy_common_webservice_model->addCartDesign($data);
	echo $cart_design_id;
	exit;
    }

    public function preTemplate() {	
	$getdata = $this->input->get();
	$url = $this->pc_rootURL . $this->plateform_solution['product_path'].'&pid=' . $getdata['product_id'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	$data = json_decode($data, true);

	$param['product_id'] = $data['product']['product_id'];
	$param['qty'] = $data['product']['minimum'];

	if ($data['option']['color']) {
	    foreach ($data['option']['color'] as $col) {
		$param['color_id'] = $col['optionID'];
		if ($col['sizes']) {
		    foreach ($col['sizes'] as $size) {
			$param['size_id'] = $size['optionID'];
		    }
		} else {
		    $param['size_id'] = '';
		}
	    }
	} else if ($data['option']['size']) {
	    foreach ($data['option']['size'] as $size) {
		$param['color_id'] = '';
		$param['size_id'] = $size['optionID'];
	    }
	} else {
	    $param['color_id'] = '';
	    $param['size_id'] = '';
	}
	$extraoptions = array();
	$param['extraoptions'] = base64_encode(serialize($extraoptions));
	//$param['pretemplate_id'] = '';
	$param['language_id'] = '1';
	$param['preTemplate'] = '1';
	$param['user'] = 'admin';
	$param['siteBaseUrl'] = get_base_url();
	$data['postData'] = json_encode($param);
	$data['languagedata'] = addslashes(json_encode($this->Designnbuy_common_webservice_model->getLanguageData($param['language_id'])));
	$xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
	$xmldata = simplexml_load_file($xml);
	$current_value_primary = $xmldata->option_1->current_value;
	if(isset($current_value_primary) && $current_value_primary != '') {
	    $data['primary_color'] = $current_value_primary;
	} else {
	    $data['primary_color'] = $xmldata->option_1->default_value;
	}

		
	$current_value = $xmldata->option_2->current_value;
	if(isset($current_value) && $current_value != '') {
	    $data['svgcolor'] = $current_value;
	} else {
	    $data['svgcolor'] = $xmldata->option_2->default_value;
	}
	
	$current_hover_value = $xmldata->option_3->current_value;	
	if(isset($current_hover_value) && $current_hover_value != '') {
	    $data['svgcolor_hover'] = $current_hover_value;
	} else {
	    $data['svgcolor_hover'] = $xmldata->option_3->default_value;
	}

	$secondary_background_value = $xmldata->option_4->current_value;	
	if(isset($secondary_background_value) && $secondary_background_value != '') {
	    $data['secondary_value'] = $secondary_background_value;
	} else {
	    $data['secondary_value'] = $secondary_background_value->option_4->default_value;
	}
	$this->load->view('pcstudio/tool_admin', $data);
    }
    public function getLanguageIdBasedOnConnector($connector = 'en') {
	$language_id = $this->Designnbuy_common_webservice_model->getLanguageIdBasedOnConnector($connector);
	return $language_id;
    }
    
    public function help($language_id) {
	$help = $this->Designnbuy_settings_model->getHelpData($language_id);
	$data['help'] = $help['help_data_text'];
	$this->load->view('pcmedia/help', $data);
    }

}