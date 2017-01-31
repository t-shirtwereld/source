<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Qranges extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * qranges listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Quantity Ranges';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'qranges/index?keyword=' . $searchparam . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_qranges_model->record_count($searchparam);
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

	$data['qranges'] = $this->Designnbuy_qranges_model->getQuantityRangesList($searchparam, $config["per_page"], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/qranges/list', $data);
    }

    /**
     * Show when you add any quantity range.
     */
    public function addqrange() {
	$data['title'] = 'Add Quantity Range';
	$this->layout_admin->view('admin/qranges/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('quantity_range_from', 'Quantity Range From', 'required|numeric');
	$this->form_validation->set_rules('quantity_range_to', 'Quantity Range To', 'required|numeric');
	if ($this->form_validation->run() == FALSE) {
	    // if qrange_id is available then show the edit page else show add page
	    if ($param['qrange_id'] != '') {
		$this->editqrange($param['qrange_id']);
	    }
	    else
		$this->addqrange();
	}
	else {
	    if ($param['quantity_range_to'] > $param['quantity_range_from']) {
		$result = $this->check_duplicate_values($param['quantity_range_from'], $param['quantity_range_to'], $param['qrange_id']);
		if ($result > 0) {
		    $this->session->set_flashdata('error', 'Duplicate Entry...');
		    if ($param['qrange_id'] != '') {
			redirect(BASE_ADMIN_URL . 'qranges/editqrange/' . $param['qrange_id']);
		    } else {
			redirect(BASE_ADMIN_URL . 'qranges/addqrange/');
		    }
		} else {
		    $this->Designnbuy_qranges_model->updateQuantityRangeRow($param);
		    if ($param['qrange_id'] != '') {
			$this->session->set_flashdata('msg', 'Success: You have modified Quantity Ranges');
		    } else {
			$this->session->set_flashdata('msg', 'Success: You have added new Quantity Ranges');
		    }
		    redirect(BASE_ADMIN_URL . 'qranges');
		}
	    } else {
		$this->session->set_flashdata('error', 'Quantity Range To must be greater than Quantity Range From...');
		if ($param['qrange_id'] != '') {
		    redirect(BASE_ADMIN_URL . 'qranges/editqrange/' . $param['qrange_id']);
		} else {
		    redirect(BASE_ADMIN_URL . 'qranges/addqrange/');
		}
	    }
	}
    }

    public function check_duplicate_values($quantity_range_from, $quantity_range_to, $qrange_id) {
	if ($qrange_id != '') {
	    $result = $this->Designnbuy_qranges_model->getQuantityRangeByNameOnEdit($quantity_range_from, $quantity_range_to, $qrange_id);
	} else {
	    $result = $this->Designnbuy_qranges_model->getQuantityRangeByName($quantity_range_from, $quantity_range_to);
	}
	return $result;
    }

    /*
     * Modify page
     */

    public function editqrange($qrange_id) {
	$data['title'] = 'Edit Quantity Range';
	$data['qrange_row'] = $this->Designnbuy_qranges_model->getQuantityRangeRow($qrange_id);
	$this->layout_admin->view('admin/qranges/edit', $data);
    }

    /**
     * Delete color counter
     */
    public function deleteqrange($qrange_id) {
	$result = $this->Designnbuy_qranges_model->deleteQuantityRangeRow($qrange_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function importcsv() {
	$data['title'] = "Import Quantity Range Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/qranges/importcsv', $data);
    }

    /*
     *  Import Quantity Range data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Quantity Ranges Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('qranges_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/qranges/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
		if ($totalcolumn == '2') {
		    if ($this->csvimport->get_array($file_path)) {
			$csv_array = $this->csvimport->get_array($file_path);
			foreach ($csv_array as $row) {
			    $row = array_values($row);
			    $result = $this->Designnbuy_qranges_model->getQuantityRangeByvalue($row['0'], $row[1]); //get Quantity Range values
			    if (!empty($result)) { // check Quantity Range value exists or not
				$data['error'] = "Quantity Range Value: " . $result['quantity_range_from'] . " - " . $result['quantity_range_to'] . " already exists";
				break;
			    } else {
				$insert_data = array(
				    'quantity_range_from' => $row[0],
				    'quantity_range_to' => $row[1]
				);
				$this->Designnbuy_qranges_model->insert_qranges_csv($insert_data);
			    }
			}
			if (!empty($data['error'])) {
			    $this->layout_admin->view('admin/qranges/importcsv', $data);
			} else {
			    $this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			    redirect(BASE_ADMIN_URL . 'qranges');
			}
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/qranges/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/qranges/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/qranges/importcsv', $data);
	    }
	}
    }

}