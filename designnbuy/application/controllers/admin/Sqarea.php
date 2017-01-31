<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sqarea extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * sqarea listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Square Areas';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'sqarea/index?keyword=' . $searchparam . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_sqarea_model->record_count($searchparam);
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

	$data['sqareas'] = $this->Designnbuy_sqarea_model->getSquareAreasList($searchparam, $config["per_page"], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/sqarea/list', $data);
    }

    /**
     * Show when you add any color counter.
     */
    public function addsqarea() {
	$data['title'] = 'Add Square Area';
	$this->layout_admin->view('admin/sqarea/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('square_area', 'Square Area', 'required|numeric');
	if ($this->form_validation->run() == FALSE) {
	    // if sqarea_id is available then show the edit page else show add page
	    if ($param['sqarea_id'] != '') {
		$this->editsqarea($param['sqarea_id']);
	    }
	    else
		$this->addsqarea();
	}
	else {
	    $result = $this->check_duplicate_values($param['square_area'], $param['sqarea_id']);
	    if ($result > 0) {
		$this->session->set_flashdata('error', 'Duplicate Entry...');
		if ($param['sqarea_id'] != '') {
		    redirect(BASE_ADMIN_URL . 'sqarea/editsqarea/' . $param['sqarea_id']);
		} else {
		    redirect(BASE_ADMIN_URL . 'sqarea/addsqarea/');
		}
	    } else {
		$sqarea_id = $this->Designnbuy_sqarea_model->updateSquareAreaRow($param);
		if ($param['sqarea_id'] != '') {
		    $this->session->set_flashdata('msg', 'Success: You have modified Artwork Area size');
		} else {
		    $this->session->set_flashdata('msg', 'Success: You have added new Artwork Area size');
		}
		redirect(BASE_ADMIN_URL . 'sqarea');
	    }
	}
    }

    public function check_duplicate_values($square_area, $sqarea_id) {
	if ($sqarea_id != '') {
	    $result = $this->Designnbuy_sqarea_model->getSquareAreaByNameOnEdit($square_area, $sqarea_id);
	} else {
	    $result = $this->Designnbuy_sqarea_model->getSquareAreaByName($square_area);
	}
	return $result;
    }

    /*
     * Modify page
     */

    public function editsqarea($sqarea_id) {
	$data['title'] = 'Edit Square Area';
	$data['sqarea_row'] = $this->Designnbuy_sqarea_model->getSquareAreaRow($sqarea_id);
	$this->layout_admin->view('admin/sqarea/edit', $data);
    }

    /**
     * Delete color counter
     */
    public function deletesqarea($sqarea_id) {
	$result = $this->Designnbuy_sqarea_model->deleteSquareAreaRow($sqarea_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function importcsv() {
	$data['title'] = "Import Square Area Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/sqarea/importcsv', $data);
    }

    /*
     *  Import Square Area data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Square Areas Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('sqarea_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/sqarea/importcsv', $data);
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
			    $result = $this->Designnbuy_sqarea_model->getSquareAreaByvalue($row['0']); //get color counter values
			    if (!empty($result)) { // check color counter value exists or not
				$data['error'] = "Square Area Value: " . $result['square_area'] . " already exists";
				break;
			    } else {
				$insert_data = array(
				    'square_area' => $row[0]
				);
				$this->Designnbuy_sqarea_model->insert_sqarea_csv($insert_data);
			    }
			}
			if (!empty($data['error'])) {
			    $this->layout_admin->view('admin/sqarea/importcsv', $data);
			} else {
			    $this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			    redirect(BASE_ADMIN_URL . 'sqarea');
			}
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/sqarea/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/sqarea/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/sqarea/importcsv', $data);
	    }
	}
    }

}