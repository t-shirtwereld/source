<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clipart_categories extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * clipart categories listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Clipart Categories';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'clipart_categories/index?keyword=' . $searchparam . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_clipart_categories_model->record_count($searchparam);
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

	$data['categories'] = $this->Designnbuy_clipart_categories_model->getCategoryList($searchparam, $config['per_page'], $page);
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/clipart_categories/list', $data);
    }

    /**
     * Show when you add any clipart category.
     */
    public function addcategory() {
	$data['title'] = 'Add Clipart Category';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	//$data['parentCategories'] = $this->Designnbuy_clipart_categories_model->getParentCategoryList();
	$this->layout_admin->view('admin/clipart_categories/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('category_name[]', 'Category Name', 'required');

	if ($this->form_validation->run() == FALSE) {
	    // if clipart_category_id is available then show the edit page else show add page
	    if ($param['clipart_category_id'] != '') {
		$this->editcategory($param['clipart_category_id']);
	    }
	    else
		$this->addcategory();
	}
	else {
	    $this->Designnbuy_clipart_categories_model->updateCategoryRow($param);
	    if ($param['clipart_category_id'] != '') {	
		$this->session->set_flashdata('msg', 'Success: You have modified Clipart category');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have added new Clipart category');
	    }
	    redirect(BASE_ADMIN_URL.'clipart_categories');
	}
    }

    /*
     * Modify page
     */

    public function editcategory($clipart_category_id) {
	$data['title'] = 'Edit Clipart Category';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	//$data['parentCategories'] = $this->Designnbuy_clipart_categories_model->getParentCategoryList();
	$data['category_row'] = $this->Designnbuy_clipart_categories_model->getCategoryRow($clipart_category_id);
	$data['category_names'] = $this->Designnbuy_clipart_categories_model->getCategoryNamesList($clipart_category_id);
	$this->layout_admin->view('admin/clipart_categories/edit', $data);
    }

    /**
     * Delete category
     */
    public function deletecategory($clipart_category_id) {
	$result = $this->Designnbuy_clipart_categories_model->deleteCategoryRow($clipart_category_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function importcsv() {
	$data['title'] = "Import Category Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/clipart_categories/importcsv', $data);
    }

    /*
     *  Import Category data using csv import
     */

    function upload_importcsv() {

	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Category Data";
	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('category_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/clipart_categories/importcsv', $data);
	} else {
	    $file_data = $this->upload->data();
	    $file_path = TOOL_IMG_PATH . '/importcsv/' . $file_data['file_name'];
	    if ($file_data['file_size'] > 0) {
		$handle = fopen($file_path, "r");
		$totalcolumn = count(fgetcsv($handle, 1000, ','));
		if ($totalcolumn >= '3') {
		    if ($this->csvimport->get_array($file_path)) {
			$csv_array = $this->csvimport->get_array($file_path);
			foreach ($csv_array as $row) {
			    $category_row = $this->Designnbuy_clipart_categories_model->getCategoryRow($row['clipart_category_id']); // get category id

					$result1 = array_slice($row, 0, 2);
					$result2 = array_slice($row, 2);
					$count = count($result2);
					$result_row = array_values($result1); // split array in to two array result_row = clipart category table data
					$result_lang = array_values($result2); // $result_lang = clipart category language data
					$insert_data = array(
						//'clipart_category_id' => $result_row[0],
					  //  'parent_category_id' => $result_row[1],
						'position' => $result_row[0],
						'status' => $result_row[1]
					);

					$clipartCategoryId = $this->Designnbuy_clipart_categories_model->insert_cateogry_csv($insert_data);

					$counter = 0;
					for ($i = 0; $i < $count / 2; $i++) { // run for loop two get exact two data for language table
						$lang_data = array(
						'clipart_category_id' => $clipartCategoryId,
						'language_id' => $result_lang[$counter],
						'name' => $result_lang[$counter + 1]
						);
						$counter = $counter + 2;
						$this->Designnbuy_clipart_categories_model->insert_cateogry_lang_csv($lang_data);
					}

			}
			if (!empty($data['error'])) {
			    $this->layout_admin->view('admin/clipart_categories/importcsv', $data);
			} else {
			    $this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			    redirect(BASE_ADMIN_URL.'clipart_categories');
			}
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/clipart_categories/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/clipart_categories/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/clipart_categories/importcsv', $data);
	    }
	}
    }
    
    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_clipart_categories_model->updateSortableRow($param);
    }

}

