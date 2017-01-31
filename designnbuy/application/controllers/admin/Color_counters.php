<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Color_counters extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * color_counter listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Color Counters';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'color_counters/index?keyword=' . $searchparam . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_color_counters_model->record_count($searchparam);
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

	$data['color_counters'] = $this->Designnbuy_color_counters_model->getColorCountersList($searchparam, $config["per_page"], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/color_counters/list', $data);
    }

    /**
     * Show when you add any color counter.
     */
    public function addcolorcounter() {
	$data['title'] = 'Add Color Counter';
	$this->layout_admin->view('admin/color_counters/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('color_counter', 'Color Counter', 'required|numeric');
	if ($this->form_validation->run() == FALSE) {
	    // if color_counter_id is available then show the edit page else show add page
	    if ($param['color_counter_id'] != '') {
		$this->editcolorcounter($param['color_counter_id']);
	    }
	    else
		$this->addcolorcounter();
	}
	else {
	    $result = $this->check_duplicate_values($param['color_counter'], $param['color_counter_id']);
	    if ($result > 0) {
		$this->session->set_flashdata('error', 'Duplicate Entry...');
		if ($param['color_counter_id'] != '') {
		    redirect(BASE_ADMIN_URL . 'color_counters/editcolorcounter/' . $param['color_counter_id']);
		} else {
		    redirect(BASE_ADMIN_URL . 'color_counters/addcolorcounter/');
		}
	    } else {		
		$color_counter_id = $this->Designnbuy_color_counters_model->updateColorCounterRow($param);
		$this->session->set_flashdata('msg', 'Entry Updated Successfully');
		redirect(BASE_ADMIN_URL.'color_counters');
	    }
	}
    }

    public function check_duplicate_values($color_counter, $color_counter_id) {
	if ($color_counter_id != '') {
	    $result = $this->Designnbuy_color_counters_model->getColorCounterByNameOnEdit($color_counter, $color_counter_id);
	} else {
	    $result = $this->Designnbuy_color_counters_model->getColorCounterByName($color_counter);
	}
	return $result;
    }

    /*
     * Modify page
     */

    public function editcolorcounter($color_counter_id) {
	$data['title'] = 'Edit Color Counter';
	$data['color_counter_row'] = $this->Designnbuy_color_counters_model->getColorCounterRow($color_counter_id);
	$this->layout_admin->view('admin/color_counters/edit', $data);
    }

    /**
     * Delete color counter
     */
    public function deletecolorcounter($color_counter_id) {
	$result = $this->Designnbuy_color_counters_model->deleteColorCounterRow($color_counter_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function importcsv() {
	$data['title'] = "Import Color Counter Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/color_counters/importcsv', $data);
    }

    /*
     *  Import Color Counter data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Color Counters Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('color_counter_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/color_counters/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
		if ($totalcolumn == '1') {
		    if ($this->csvimport->get_array($file_path)) {
			$csv_array = $this->csvimport->get_array($file_path);
			foreach ($csv_array as $row) {
			    $row = array_values($row);
			    $result = $this->Designnbuy_color_counters_model->getColorCounterByvalue($row['0']); //get color counter values
			    if (!empty($result)) { // check color counter value exists or not
				$data['error'] = "Color Counter Value: " . $result['color_counter'] . " already exists";
				break;
			    } else {
				$insert_data = array(
				    'color_counter' => $row[0]
				);
				$this->Designnbuy_color_counters_model->insert_color_counter_csv($insert_data);
			    }
			}
			if (!empty($data['error'])) {
			    $this->layout_admin->view('admin/color_counters/importcsv', $data);
			} else {
			    $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
			    redirect(BASE_ADMIN_URL.'color_counters');
			}
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/color_counters/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/color_counters/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/color_counters/importcsv', $data);
	    }
	}
    }

}