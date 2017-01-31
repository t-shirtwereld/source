<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends PC_Controller {

    public function __construct() {
	parent::__construct();
    }

    public function index() {
	$data['title'] = 'General Configuration';
	$data['setting'] = $this->Designnbuy_settings_model->getGeneralSettingsRow();
	$data['emails'] = $this->Designnbuy_settings_model->getAdminNotificationEmails();
	$data['sidelabels'] = $this->Designnbuy_settings_model->getGlobalSideLabels();
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
//print "<pre>"; print_r($data); exit;
	$this->layout_admin->view('admin/settings/general_settings', $data);
    }

    public function social_media() {
	$data['title'] = 'Social Media Configuration';
	$data['setting'] = $this->Designnbuy_settings_model->getGeneralSettingsRow();
	$this->layout_admin->view('admin/settings/social_media', $data);
    }

    public function configure_feature() {
	$data['title'] = 'Configure Features';
	$data['setting'] = $this->Designnbuy_settings_model->getConfigureFeature();
	$this->layout_admin->view('admin/settings/configure_features', $data);
    }

    public function product_advance_configuration() {
	$data['title'] = 'Product Advance Configuration';
	$result = $this->Designnbuy_settings_model->getProductAdvanceConfiguration();
	$data['id'] = $result['id'];
	$data['name'] = $result['name'];
	$data['setting'] = json_decode($result['value'], true);
	$this->layout_admin->view('admin/settings/product_advance_configuration', $data);
    }

    public function multilanguage($lang = '') {
	if (isset($lang) && $lang != '') {
	    $locale = $lang;
	} else {
	    $locale = 'en_US';
	}
	$this->setLanguageCodeData($locale);
	$labels = array();

	$xml = ASSETS_PATH . 'parent_multilanguage/' . $locale . '.xml';
	$xmldata = simplexml_load_file($xml);
	foreach ($xmldata as $_key => $_data) {
	    $labels[$_key] = (array) $_data;
	}
	$childxml = ASSETS_PATH . 'child_multilanguage/' . $locale . '.xml';
	$childxmldata = simplexml_load_file($childxml);
	foreach ($childxmldata as $_key => $_data) {
	    if (array_key_exists($_key, $labels)) {
		$labels[$_key]['pc_text'] = (string) $_data->pc_text;
	    }
	}

	$data['title'] = 'Multi Language';
	$data['lang'] = $locale;
	$data['labels'] = $labels;
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$this->layout_admin->view('admin/settings/multilanguage', $data);
    }

    public function setLanguageCodeData($locale) {
	$parent_multilanguage_dir = ASSETS_PATH . 'parent_multilanguage/';
	$child_multilanguage_dir = ASSETS_PATH . 'child_multilanguage/';
	$parent_multilanguage_path = base_url('assets/parent_multilanguage') . '/';
	$child_multilanguage_path = base_url('assets/child_multilanguage') . '/';

	if ($locale != 'en_US' && !file_exists($parent_multilanguage_dir . $locale . '.xml'))
	    @copy($parent_multilanguage_dir . 'en_US.xml', $parent_multilanguage_dir . $locale . '.xml');

	$xml = $child_multilanguage_dir . $locale . '.xml';
	if (!file_exists($xml)) {
	    if (!file_exists($child_multilanguage_dir))
		mkdir($child_multilanguage_dir, 0755);

	    if (file_exists($parent_multilanguage_dir . $locale . '.xml')) {
		@copy($parent_multilanguage_dir . $locale . '.xml', $child_multilanguage_dir . $locale . '.xml');
	    }
	    else
		@copy($parent_multilanguage_dir . $locale . '.xml', $child_multilanguage_dir . 'en_US.xml');
	}
    }

    public function saveMultiLanguage($locale) {
	$languageData = $this->input->post('language_data');
	$xml = ASSETS_PATH . 'parent_multilanguage/' . $locale . '.xml';
	$xmldata = simplexml_load_file($xml);
	$childxml = ASSETS_PATH . 'child_multilanguage/' . $locale . '.xml';
	$doc = new DOMDocument('1.0', 'utf-8');
	$doc->formatOutput = true;
	$root = $doc->createElement('printcommerce_multilanguage');
	$root = $doc->appendChild($root);

	foreach ($xmldata as $_key => $_data) {
	    $optioncodenode = $doc->createElement($_key);
	    $newdoc = $root->appendChild($optioncodenode);

	    $em1 = $doc->createElement('pc_type');
	    $type = $doc->createTextNode($_data->pc_type);
	    $em1->appendChild($type);
	    $newdoc->appendChild($em1);

	    $em = $doc->createElement('pc_text');
	    $text = $doc->createTextNode(isset($languageData[$_key]) ? $languageData[$_key] : $_data->pc_text);
	    $em->appendChild($text);
	    $newdoc->appendChild($em);
	}
	$doc->save($childxml);
	$this->session->set_flashdata('msg', 'Success: MultiLanguage module updated');
	redirect(BASE_ADMIN_URL . 'settings/multilanguage/' . $locale);
    }

    /**
     * Update Genreal settings
     */
    public function update_general_settings() {
// fetch all varialbe into one variable
	$param = $this->input->post();
	$setting = $this->Designnbuy_settings_model->getGeneralSettingsRow();
// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('image_resolution', 'Image Resolution', 'numeric');
	if ($param['watermark_status'] == '1' && empty($_FILES['watermark_logo']['name']) && empty($setting['watermark_logo'])) {
	    $this->form_validation->set_rules('watermark_logo', 'Image', 'required|callback_image_type');
	    $this->form_validation->set_message('image_type', 'Please upload only gif,jpg,jpeg,png files');
	}
	if ($this->form_validation->run() == FALSE) {
// if clipart_id is available then show the edit page else show add page
	    if ($param['id'] != '') {
		$this->index();
	    }
	} else {
	    $id = $this->Designnbuy_settings_model->updateGeneralSettings($param);
	    if ($_FILES['watermark_logo']['tmp_name'] != '') {

		$this->upload_image('watermark_logo', $id);
	    }
	    $this->session->set_flashdata('msg', 'Success: Configuration settings saved');
	    redirect(BASE_ADMIN_URL . 'settings');
	}
    }

    /**
     * Update Social Media settings
     */
    public function update_social_media() {
// fetch all varialbe into one variable
	$param = $this->input->post();
	$this->Designnbuy_settings_model->updateSocialMediaSettings($param);
	$this->session->set_flashdata('msg', 'Success: Social media settings saved');
	redirect(BASE_ADMIN_URL . 'settings/social_media');
    }

    /**
     * Update Configure Features
     */
    public function update_configure_feature() {
	$param = $this->input->post();
	$this->Designnbuy_settings_model->updateConfigureFeature($param);
	$this->session->set_flashdata('msg', 'Success: Configure Feature settings saved');
	redirect(BASE_ADMIN_URL . 'settings/configure_feature');
    }

    /**
     * Upload logo
     */
    public function upload_image($image_field_name = "", $id = "") {


	$CI = &get_instance();
// set the image configuration parametter
	$config['upload_path'] = TOOL_IMG_PATH . '/logo/';
	$config['allowed_types'] = 'gif|jpg|png|jpeg';

	$file = $_FILES['watermark_logo']['name'];
	$config['file_name'] = substr(md5(rand()), 0, 8) . '-' . basename($file);

	$this->load->library('upload', $config);
// get the CI instence
	if ($CI->upload->do_upload($image_field_name)) {
	    $data = $CI->upload->data();
	    $this->Designnbuy_settings_model->updateWatermarkLogo($id, $data['orig_name']);
	} else {
	    $error = array('error' => $this->upload->display_errors());
	    return $error;
	}
    }

    public function image_type($image = '') {
	if (isset($_FILES['watermark_logo']['name']) && $_FILES['watermark_logo']['name'] != '') {
	    $file = $_FILES['project_image']['name'];
	    $extension = pathinfo($file, PATHINFO_EXTENSION);
	    if ($extension != '') {
		$ext = array('gif', 'jpeg', 'jpg', 'png');
		if (!in_array(strtolower($extension), $ext)) {
		    return false;
		} else {
		    return true;
		}
	    } else {
		return false;
	    }
	} else {
	    return false;
	}
    }

    /*
     * Update Advance Tool Settings
     */

    public function update_product_advance_configuration() {
	$param = $this->input->post();
	$this->Designnbuy_settings_model->updateProductAdvanceConfiguration($param);
	$this->session->set_flashdata('msg', 'Success: Product Advance Confiugration settings saved');
	redirect(BASE_ADMIN_URL . 'settings/product_advance_configuration');
    }

    public function personalizer() {
	/*if (isset($lang) && $lang != '') {
	    $locale = $lang;
	} else {
	    $locale = 'en_US';
	}
	$this->setLanguageCodeData($locale); */
	$labels = array();

	$xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
	$xmldata = simplexml_load_file($xml);
	foreach ($xmldata as $_key => $_data) {
	    $labels[$_key] = (array) $_data;
	}

	$data['title'] = 'Personalize Design Studio';
	$data['labels'] = $labels;
	$this->layout_admin->view('admin/settings/personalizer', $data);
    }

    public function save_personlizer() {
	$postData = $this->input->post();
	$appUrlXmlFile = $xml = ASSETS_PATH . 'js/html5/css/personalizer/personalization.xml';
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;
	$root = $doc->createElement('printcommerce_personalizer');
	$root = $doc->appendChild($root);
	foreach ($postData['personalizer'] as $keyOption => $option_value) {
	    $optioncodenode = $doc->createElement($keyOption);
	    $newdoc = $root->appendChild($optioncodenode);
	    foreach ($option_value as $optioncode => $value) {
		$em = $doc->createElement($optioncode);
		$text = $doc->createTextNode($value);
		$em->appendChild($text);
		$newdoc->appendChild($em);
	    }
	}
	$doc->save($appUrlXmlFile);

	/*
	 * Create Css File
	 */
	if (file_exists($appUrlXmlFile)) {
	    $appCssFile = $xml = ASSETS_PATH . 'js/html5/css/personalization.css';
	    $cssOptionPart = array();
	    $personalliseXmlData = simplexml_load_file($appUrlXmlFile);
	    foreach ($personalliseXmlData as $personalliseXmlOption) {
		$OptionCssText = $personalliseXmlOption->css;
		$OptionCssText = implode("\r\n", explode('|', $OptionCssText));
		$OptionCerrentColor = $personalliseXmlOption->current_value;
		$cssOptionPart[] = str_replace("--COLOR--", $OptionCerrentColor, $OptionCssText);
	    }
	    file_put_contents($appCssFile, implode($cssOptionPart, "\r\n"));
	}
	$this->session->set_flashdata('msg', 'Success: Personalize css updated');
	redirect(BASE_ADMIN_URL . 'settings/personalizer/');
    }

    public function help_data($lang_id = '') {
	if (isset($lang_id) && $lang_id != '') {
	    $locale = $lang;
	} else {
	    $lang_id = '1';
	}
	$data['title'] = 'Help Data';
	$data['lang_id'] = $lang_id;
	$data['help'] = $this->Designnbuy_settings_model->getHelpData($lang_id);
	//print "<pre>"; print_r($data); exit;
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$this->layout_admin->view('admin/settings/help_data', $data);
    }

    public function save_help_data($lang_id = '') {
	$param = $this->input->post();
	$this->Designnbuy_settings_model->saveHelpdata($param, $lang_id);
	$this->session->set_flashdata('msg', 'Success: Help Data saved');
	redirect(BASE_ADMIN_URL . 'settings/help_data/' . $lang_id);
    }
    
    

    public function ckEditorFileUpload() {
	$filename = str_replace(' ', '_', $_FILES['upload']['name']);
	$url = $_SERVER['DOCUMENT_ROOT'] . '/designtool/help/images/' . $filename;
	if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
	    $message = "No file uploaded.";
	} else if ($_FILES['upload']['size'] == 0) {
	    $message = "The file is of zero length.";
	} else if (($_FILES['upload']['type'] != 'image/pjpeg') AND ($_FILES['upload']['type'] != 'image/jpeg') AND ($_FILES['uploa']['type'] != 'image/png')) {
	    $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
	} else if (!is_uploaded_file($_FILES['upload']['tmp_name'])) {
	    $message = "You may be attempting to hack our server. We're on to you expect a knock on the door sometime soon.";
	} else {
	    $message = "";
	    $move = @move_uploaded_file($_FILES['upload']['tmp_name'], $url);
	    if (!$move) {
		$message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
	    }
	   $imageurl = get_base_url().'designnbuy/designtool/help/images/' . $filename;
	}
	$funcNum = $_GET['CKEditorFuncNum'];
	echo "<script type = 'text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$imageurl', '$message');</script>";
    }

}