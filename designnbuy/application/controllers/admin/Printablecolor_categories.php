<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Printablecolor_categories extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * printablecolor categories listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Printablecolor Categories';
	
	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;
	
	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'printablecolor_categories/index?keyword='.$searchparam.'&limit=' .$limit;
	$config['total_rows'] = $this->Designnbuy_printablecolor_categories_model->record_count($searchparam);
	$data['total_rows'] = $config['total_rows'];
	if(isset($limit) && $limit != '') {
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
	
	$data['categories'] = $this->Designnbuy_printablecolor_categories_model->getCategoryList($searchparam,$config["per_page"], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/printablecolor_categories/list', $data);
	
    }

    /**
     * Show when you add any printablecolor category.
     */
    public function addcategory() {
	$data['title'] = 'Add Printablecolor Category';
	$this->layout_admin->view('admin/printablecolor_categories/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('name', 'Category Name', 'required');

	if ($this->form_validation->run() == FALSE) {
	    // if printablecolor_category_id is available then show the edit page else show add page
	    if ($param['printablecolor_category_id'] != '') {
		$this->editcategory($param['printablecolor_category_id']);
	    }
	    else
		$this->addcategory();
	}
	else {
	    $this->Designnbuy_printablecolor_categories_model->updateCategoryRow($param);
	    if ($param['printablecolor_category_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have modified Color category');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have added new Color category');
	    }
	    redirect(BASE_ADMIN_URL.'printablecolor_categories');
	}
    }

    /*
     * Modify page
     */

    public function editcategory($printablecolor_category_id) {
	$data['title'] = 'Edit Printablecolor Category';
	$data['category_row'] = $this->Designnbuy_printablecolor_categories_model->getCategoryRow($printablecolor_category_id);
	$this->layout_admin->view('admin/printablecolor_categories/edit', $data);
    }

    /**
     * Delete category
     */
    public function deletecategory($printablecolor_category_id) {
	$result = $this->Designnbuy_printablecolor_categories_model->deleteCategoryRow($printablecolor_category_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }
    
    public function importcsv() {
	$data['title'] = "Import Printable Color cateogries Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
    }

    /*
     *  Import Printable Color cateogries data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Printable Color cateogries Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('category_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
			if ($totalcolumn == '3') {
				if ($this->csvimport->get_array($file_path)) {
					$csv_array = $this->csvimport->get_array($file_path);
					foreach ($csv_array as $row) {
						$row = array_values($row);
						$result = $this->Designnbuy_printablecolor_categories_model->getPrintableColorCategoryByName($row['0']); //get Printable Color cateogry name
						if (!empty($result)) { // check Printable Color cateogry name exists or not
							$data['error'] = "Printable Color Cateogries Name: " . $result['category_name'] . " already exists";
							break;
						} else {
							$fontCssName = str_replace(' ', '_', $font_name) . '.css';
							$insert_data = array(
									'category_name' => $row[0],
									'status' => $row[1],
									'position' => $row[2]
							);
							$this->Designnbuy_printablecolor_categories_model->insert_cateogry_csv($insert_data);
						}
					}
					if (!empty($data['error'])) {
						$this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
					} else {
						$this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
						redirect(BASE_ADMIN_URL.'printablecolor_categories');
					}
				} else {
					$data['error'] = "Error occured...!";
					$this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
				}
			} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/printablecolor_categories/importcsv', $data);
	    }
	}
    }
    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_printablecolor_categories_model->updateSortableRow($param);
    }


}
