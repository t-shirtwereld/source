<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fonts extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * font listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Fonts';
	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'fonts/index?keyword=' . $searchparam . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_fonts_model->record_count($searchparam);
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

	$data['fonts'] = $this->Designnbuy_fonts_model->getFontsList($searchparam, $config['per_page'], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/fonts/list', $data);
    }

    /**
     * Show when you add any font.
     */
    public function addfont() {
	$data['title'] = 'Add Font';
	$this->layout_admin->view('admin/fonts/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();
	
	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('font_name', 'Font Name', 'required');
	if ($param['font_id'] == '' && empty($_FILES['file_upload']['name']) && empty($_FILES['js_upload']['name'])) {
	    $this->form_validation->set_rules('file_upload', 'Image', 'required|callback_image_type');
	    $this->form_validation->set_message('image_type', 'Please upload only WOFF file');
	    $this->form_validation->set_rules('js_upload', 'Js', 'required|callback_js_type');
	    $this->form_validation->set_message('js_type', 'Please upload only JS file');	    
	}
	if ($this->form_validation->run() == FALSE) {
	    // if font_id is available then show the edit page else show add page
	    if ($param['font_id'] != '') {
		$this->editfont($param['font_id']);
	    }
	    else
		$this->addfont();
	}
	else {
	    $font_id = $this->Designnbuy_fonts_model->updateFontRow($param);
	    if ($_FILES['js_upload']['tmp_name'] != '') {
		
		$this->upload_js('js_upload', $font_id);
	    }
	    if ($_FILES['file_upload']['tmp_name'] != '') {

		$this->upload_image('file_upload', $font_id, $param['font_name']);
	    }
	    if ($param['font_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have modified Font');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have added new Font');
	    }	    
	    redirect(BASE_ADMIN_URL.'fonts');
	}
    }

    /*
     * Modify page
     */

    public function editfont($font_id) {
	$data['title'] = 'Edit Font';
	$data['font_row'] = $this->Designnbuy_fonts_model->getFontRow($font_id);
	$this->layout_admin->view('admin/fonts/edit', $data);
    }

    /**
     * Delete category
     */
    public function deletefont($font_id) {
	$result = $this->Designnbuy_fonts_model->deleteFontRow($font_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    /**
     * Upload Font Image
     */
    public function upload_image($image_field_name = "", $font_id = "", $font_name = "") {
	// set the image configuration parametter
	$config['upload_path'] = TOOL_IMG_PATH . '/fonts/';
	$config['allowed_types'] = 'woff|WOFF';
	$file = $_FILES['file_upload']['name'];
	$config['file_name'] = basename($file);

	$this->load->library('upload');
$this->upload->initialize($config);
	// get the CI instence
	if ($this->upload->do_upload($image_field_name)) {

	    $data = $this->upload->data();
	    $file_ext = pathinfo($data['orig_name'], PATHINFO_EXTENSION);
	    if ($file_ext == 'ttf')
		$font_type = "format('truetype')";
	    else if ($file_ext == 'eot')
		$font_type = "";
	    else
		$font_type = "format('" . $file_ext . "')";

	    $content_css = "@font-face {
					font-family: '" . $font_name . "';
					src: url('" . $data['orig_name'] . "');
					src: url('" . $data['orig_name'] . "')" . $font_type . ";
					font-weight: normal;
					font-style: normal;
					}";
	    $fontCssName = str_replace(' ', '_', $font_name);
	    $file = TOOL_IMG_PATH . '/fonts/' . $fontCssName . '.css';
	    file_put_contents($file, $content_css);
	    $this->Designnbuy_fonts_model->updateFontImage($font_id, $data['orig_name'], $fontCssName);
	}
	else {
	    $error = array('error' => $this->upload->display_errors());
	    return $error;
	}
    }

    public function image_type($image = '') {
	if (isset($_FILES['file_upload']['name']) && $_FILES['file_upload']['name'] != '') {
	    $file = $_FILES['file_upload']['name'];
	    $extension = pathinfo($file, PATHINFO_EXTENSION);

	    if ($extension != '') {
		$ext = array('woff', 'WOFF');
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
    
    public function upload_js($image_field_name = "", $font_id = "") {
	// set the image configuration parametter
	$configjs['upload_path'] = TOOL_IMG_PATH . '/fonts/';
	$configjs['allowed_types'] = 'js';
	$file = $_FILES['js_upload']['name'];
	$configjs['file_name'] = basename($file);
	$this->load->library('upload');
	$this->upload->initialize($configjs);
	if ($this->upload->do_upload($image_field_name)) {
	    $data = $this->upload->data();	
	    $this->Designnbuy_fonts_model->updateFontJs($font_id, $data['orig_name']);
	}
	else {
	    $error = array('error' => $this->upload->display_errors());
	    return $error;
	}
    }

    public function js_type($image = '') {
	if (isset($_FILES['js_upload']['name']) && $_FILES['js_upload']['name'] != '') {
	    $file = $_FILES['js_upload']['name'];
	    $extension = pathinfo($file, PATHINFO_EXTENSION);

	    if ($extension != '') {
		$ext = array('js');
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
	$data['title'] = "Import Fonts Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/fonts/importcsv', $data);
    }

    /*
     *  Import Fonts data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Fonts Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('font_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/fonts/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
		if ($totalcolumn == '6') {
		    if ($this->csvimport->get_array($file_path)) {
			$csv_array = $this->csvimport->get_array($file_path);
			foreach ($csv_array as $row) {
			    $row = array_values($row);
			    $result = $this->Designnbuy_fonts_model->getFontByName($row['0']); //get font name
			    if (!empty($result)) { // check color counter value exists or not
				$data['error'] = "Font Name: " . $result['font_name'] . " already exists";
				break;
			    } else {
				//$fontCssName = str_replace(' ', '_', $row[0]) . '.css';
//				$jsFileName = str_replace(' ', '_', $row[0]) . '.js';
				$insert_data = array(
				    'font_name' => $row[0],
				    'font_file' => $row[1],
				    'font_js' => $row[2],
					'font_css' => $row[3],
				    'position' => $row[4],
				    'status' => $row[5]
				);
				$this->Designnbuy_fonts_model->insert_font_csv($insert_data);
			    }
			}
			if (!empty($data['error'])) {
			    $this->layout_admin->view('admin/fonts/importcsv', $data);
			} else {
			    $this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			    redirect(BASE_ADMIN_URL.'fonts');
			}
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/fonts/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/fonts/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/fonts/importcsv', $data);
	    }
	}
    }
    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_fonts_model->updateSortableRow($param);
    }

}