<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cliparts extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->pc_rootURL = get_base_url();
	$this->plateform_solution = plateform_solutions(PLATEFORM);
	$this->load->library('curl');
	$this->load->library("pagination");
	$this->load->model('Designnbuy_common_webservice_model');
    }

    /**
     * clipart listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Cliparts';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$clipart_category_id = trim($this->input->get('clipart_category_id'));
	$data['clipart_category_id'] = $clipart_category_id;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'cliparts/index?keyword=' . $searchparam . '&clipart_category_id=' . $clipart_category_id . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_cliparts_model->record_count($searchparam, $clipart_category_id);
	$data['total_rows'] = $config['total_rows'];
	if (isset($limit) && $limit != '') {
	    $config['per_page'] = $limit;
	} else {
	    $config['per_page'] = 25;
	}
	$config['page_query_string'] = TRUE;
	$num_links = $config['total_rows'] / $config['per_page'];
	$config['num_links'] = round($num_links);
	$config['cur_tag_open'] = '&nbsp;<a class="current">';
	$config['cur_tag_close'] = '</a>';
	$config['next_link'] = '>>';
	$config['prev_link'] = '<<';

	$this->pagination->initialize($config);
	$page = (isset($_GET['per_page'])) ? $_GET['per_page'] : 0;

	$data['cliparts'] = $this->Designnbuy_cliparts_model->getClipartList($searchparam, $clipart_category_id, $config['per_page'], $page);
	$data['categories'] = $this->Designnbuy_cliparts_model->getCategoryList();
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/cliparts/list', $data);
    }

    /**
     * Show when you add any clipart.
     */
    public function addclipart() {
	$data['title'] = 'Add Clipart';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['categories'] = $this->Designnbuy_cliparts_model->getCategoryList();
	//$data['configure_feature'] = $this->Designnbuy_cliparts_model->getConfigureFeature();
	$this->layout_admin->view('admin/cliparts/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('clipart_name[]', 'Clipart Name', 'required');
	if ($param['is_clipart_design'] == '0' && $param['clipart_id'] == '' && empty($_FILES['file_upload']['name'])) {
	    $this->form_validation->set_rules('file_upload', 'Image', 'required|callback_image_type');
	    $this->form_validation->set_message('image_type', 'Please upload only SVG file');
	}
	if ($this->form_validation->run() == FALSE) {
	    // if clipart_id is available then show the edit page else show add page
	    if ($param['clipart_id'] != '') {
		$this->editclipart($param['clipart_id']);
	    }
	    else
		$this->addclipart();
	}
	else {
	    $clipart_id = $this->Designnbuy_cliparts_model->updateClipartRow($param);
	    if ($_FILES['file_upload']['tmp_name'] != '') {

		$this->upload_image('file_upload', $clipart_id);
	    }
	    
	    if ($param['clipart_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have modified Clipart');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have added new Clipart');
	    }
	    redirect(BASE_ADMIN_URL.'cliparts');
	}
    }

    /*
     * Modify page
     */

    public function editclipart($clipart_id) {
	$data['title'] = 'Edit Clipart';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['clipart_row'] = $this->Designnbuy_cliparts_model->getClipartRow($clipart_id);
	$data['clipart_names'] = $this->Designnbuy_cliparts_model->getClipartNamesList($clipart_id);
	$data['categories'] = $this->Designnbuy_cliparts_model->getCategoryList();
	//$data['configure_feature'] = $this->Designnbuy_cliparts_model->getConfigureFeature();
	$this->layout_admin->view('admin/cliparts/edit', $data);
    }

    /**
     * Delete category
     */
    public function deleteclipart($clipart_id) {
	$result = $this->Designnbuy_cliparts_model->deleteClipartRow($clipart_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    /**
     * Upload Clipart Image
     */
    public function upload_image($image_field_name = "", $clipart_id = "") {


	$CI = &get_instance();
	// set the image configuration parametter
	$config['upload_path'] = TOOL_IMG_PATH . '/cliparts/';
	$config['allowed_types'] = 'svg|SVG';

	$file = $_FILES['file_upload']['name'];
	$config['file_name'] = substr(md5(rand()), 0, 8) . '-' . basename($file);

	$this->load->library('upload', $config);

	// get the CI instence

	if ($CI->upload->do_upload($image_field_name)) {

	    $data = $CI->upload->data();
	    $this->Designnbuy_cliparts_model->updateClipartImage($clipart_id, $data['orig_name']);
	} else {
	    $error = array('error' => $this->upload->display_errors());
	    return $error;
	}
    }

    public function image_type($image = '') {
	if (isset($_FILES['file_upload']['name']) && $_FILES['file_upload']['name'] != '') {
	    $file = $_FILES['project_image']['name'];
	    $extension = pathinfo($file, PATHINFO_EXTENSION);

	    if ($extension != '') {
		$ext = array('svg', 'SVG');
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

    public function importcsv() {
	$data['title'] = "Import Clipart Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/cliparts/importcsv', $data);
    }

    /*
     *  Import Clipart data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Clipart Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('clipart_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/cliparts/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
		if ($totalcolumn >= '7') {
		    if ($this->csvimport->get_array($file_path)) {
			$csv_array = $this->csvimport->get_array($file_path);
			foreach ($csv_array as $row) {
			    $result1 = array_slice($row, 0, 7);
			    $result2 = array_slice($row, 7);
			    $count = count($result2);
			    $result_row = array_values($result1); // split array in to two array result_row = clipart table data
			    $result_lang = array_values($result2); // $result_lang = clipart language data

			    $insert_data = array(
				'clipart_category_id' => $result_row[0],
				'clipart_image' => $result_row[1],
				'clipart_png' => $result_row[2],
				'product_id' => $result_row[3],
				'is_clipart_design' => $result_row[4],
				'position' => $result_row[5],
				'status' => $result_row[6]
			    );
			    $clipart_id = $this->Designnbuy_cliparts_model->insert_clipart_csv($insert_data);
			    $counter = 0;
			    for ($i = 0; $i < $count / 2; $i++) { // run for loop two get exact two data for language table
				$lang_data = array(
				    'clipart_id' => $clipart_id,
				    'language_id' => $result_lang[$counter],
				    'name' => $result_lang[$counter + 1]
				);

				$counter = $counter + 2;
				$this->Designnbuy_cliparts_model->insert_clipart_lang_csv($lang_data);
			    }
			}
			$this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			redirect(BASE_ADMIN_URL.'cliparts');
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/cliparts/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/cliparts/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/cliparts/importcsv', $data);
	    }
	}
    }
    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_cliparts_model->updateSortableRow($param);
    }
    
     public function customize($clipart_id) {
	$product_id = $this->Designnbuy_cliparts_model->getProductId($clipart_id);

	$url = $this->pc_rootURL . $this->plateform_solution['product_path'].'&pid=' . $product_id;
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
	$param['clipart_id'] = $clipart_id;
	//$param['preTemplate'] = '1';
	$param['language_id'] = '1';
	$param['user'] = 'admin';
	$param['siteBaseUrl'] = get_base_url();
	$data['postData'] = json_encode($param);
	$data['languagedata'] = json_encode($this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']));
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
	$this->load->view('pcstudio/tool_admin', $data);
    }
    
    public function saveclipart() {
	
	if ($this->input->post('template_id')) {
	    $id = $this->input->post('template_id');
	} else {
	    $id = 0;
	}
	if ($this->input->post('product_id')) {
	    $product_id = $this->input->post('product_id');
	} else {
	    $product_id = 0;
	}
	if ($this->input->post('save_str')) {
	    $ret_string = html_entity_decode($this->input->post('save_str'));
	} else {
	    $ret_string = "undefined";
	}

	$outputPath = TOOL_IMG_PATH . '/cliparts/temp-' . $id . '/';
	if (!is_dir($outputPath)) {
	    mkdir($outputPath, 0777);
	}
	$imageDir = TOOL_IMG_PATH . '/cliparts/clipart_' . $id . '.svg';
	file_put_contents($imageDir, $ret_string);


	$svgFileName = $this->removePaths($ret_string, $id);
	$pngFileName = $this->generatePNG($outputPath . $svgFileName, $id);
	$data['clipart_png'] = 'clipart_' . $id . '.png';
	$data['clipart_image'] = 'clipart_' . $id . '.svg';
	$data['product_id'] = $product_id;

	$design_id = $this->Designnbuy_cliparts_model->saveClipartData($id, $data);
	$this->removeTempImageDir($outputPath);
	echo html_entity_decode(BASE_ADMIN_URL . 'cliparts/editclipart/' . $id);
	exit;
    }

    public function removePaths($svgContent, $id) {
	if ($svgContent != '') {
	    $mediaPath = TOOL_IMG_PATH . '/';
	    $outputPath = TOOL_IMG_PATH . '/cliparts/temp-' . $id . '/';
	    $doc = new DOMDocument();
	    //$dom->preserveWhiteSpace = False;
	    $doc->loadXML($svgContent);

	    $svg = 'gallery_' . $id . '.svg';
	    foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element) {
		foreach ($element->getElementsByTagName("*") as $tags) {
		    if ($tags->localName == 'image' && $tags->getAttribute('xlink:href') != '') {
			$imageUrl = $tags->getAttribute('xlink:href');
			// $name = pathinfo($imageUrl, PATHINFO_FILENAME);
			$name = pathinfo($imageUrl, PATHINFO_BASENAME);
			$tags->setAttribute('xlink:href', $name);
			copy($imageUrl, $outputPath . $name);

		    }
		}
	    }
	    $doc->save($outputPath . $svg);
	    return $svg;
	}
    }

    public function generatePNG($svg, $id) {
	if (file_exists($svg)) {

	    $outputImagePath = TOOL_IMG_PATH . '/cliparts/';
	    $imageUrl = TOOL_IMG_PATH . '/cliparts/temp-' . $id . '/';
	    $svgName = pathinfo($svg, PATHINFO_FILENAME);

	    $pngFileName = 'clipart_' . $id . '.png';
	    $param = array('filename' => $svg);
	    $this->load->library('Inkscape', $param);

	    // $inkscape = new Inkscape($svg);

	    $this->inkscape->exportAreaSnap();
	    //$inkscape->exportAreaSnap();
	    //better pixel art
	    $this->inkscape->exportTextToPath();
	    //$inkscape->exportTextToPath();
	    // $inkscape->setSize($width=792, $height=408);
	    // $inkscape->setDpi(96);
	    try {
		$ok = $this->inkscape->export('png', $outputImagePath . $pngFileName);
	    } catch (Exception $exc) {
		echo $exc->getMessage();
		echo $exc->getTraceAsString();
	    }

	    return $imageUrl . $pngFileName;
	}
    }

    public function removeTempImageDir($dir) {
	if (is_dir($dir)) {
	    $objects = scandir($dir);
	    foreach ($objects as $object) {
		if ($object != "." && $object != "..") {
		    if (filetype($dir . "/" . $object) == "dir")
			rrmdir($dir . "/" . $object); else
			unlink($dir . "/" . $object);
		}
	    }
	    reset($objects);
	    rmdir($dir);
	}
    }

}