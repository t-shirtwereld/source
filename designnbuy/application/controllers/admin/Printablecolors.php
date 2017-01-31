<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Printablecolors extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * printablecolor listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Printablecolors';
	
	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$category_id = trim($this->input->get('category_id'));
	$data['category_id'] = $category_id;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;
	
	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'printablecolors/index?keyword='.$searchparam.'&category_id='.$category_id.'&limit=' .$limit;
	$config['total_rows'] = $this->Designnbuy_printablecolors_model->record_count($searchparam, $category_id);
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

	$data['printablecolors'] = $this->Designnbuy_printablecolors_model->getPrintablecolorList($searchparam, $category_id, $config["per_page"], $page);
	$data['categories'] = $this->Designnbuy_printablecolor_categories_model->getCategoryList();
	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/printablecolors/list', $data);
    }

    /**
     * Show when you add any printablecolor.
     */
    public function addprintablecolor() {
	$data['title'] = 'Add Printablecolor';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['categories'] = $this->Designnbuy_printablecolors_model->getCategoryList();
	$this->layout_admin->view('admin/printablecolors/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('printablecolor_name[]', 'Printablecolor Name', 'required');

	if ($this->form_validation->run() == FALSE) {
	    // if printablecolor_id is available then show the edit page else show add page
	    if ($param['printablecolor_id'] != '') {
		$this->editprintablecolor($param['printablecolor_id']);
	    }
	    else
		$this->addprintablecolor();
	}
	else {
	    $this->Designnbuy_printablecolors_model->updatePrintablecolorRow($param);
	    if ($param['printablecolor_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have modified Printable Color');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have added new Printable Color');
	    }
	    redirect(BASE_ADMIN_URL.'printablecolors');
	}
    }

    /*
     * Modify page
     */

    public function editprintablecolor($printablecolor_id) {
	$data['title'] = 'Edit Printablecolor';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['printablecolor_row'] = $this->Designnbuy_printablecolors_model->getPrintablecolorRow($printablecolor_id);
	$data['printablecolor_names'] = $this->Designnbuy_printablecolors_model->getPrintablecolorNamesList($printablecolor_id);
	$data['categories'] = $this->Designnbuy_printablecolors_model->getCategoryList();
	$this->layout_admin->view('admin/printablecolors/edit', $data);
    }

    /**
     * Delete category
     */
    public function deleteprintablecolor($printablecolor_id) {
	$result = $this->Designnbuy_printablecolors_model->deletePrintablecolorRow($printablecolor_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }
    
    public function importcsv() {
	$data['title'] = "Import Printable Color Data";
	delete_files(TOOL_IMG_PATH . '/importcsv/');
	$this->layout_admin->view('admin/printablecolors/importcsv', $data);
    }

    /*
     *  Import Category data using csv import
     */

    function upload_importcsv() {
	$this->load->library('csvimport');
	$data['error'] = '';    //initialize image upload error array to empty
	$data['title'] = "Import Printable Color Data";

	$config['upload_path'] = TOOL_IMG_PATH . '/importcsv/';
	$config['allowed_types'] = 'csv';
	$config['max_size'] = '1000';

	$this->load->library('upload', $config);

	// If upload failed, display error
	if (!$this->upload->do_upload('printablecolor_csv')) {
	    $data['error'] = $this->upload->display_errors();
	    $this->layout_admin->view('admin/printablecolors/importcsv', $data);
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
					$result1 = array_slice($row, 0, 8);
					$result2 = array_slice($row, 8);
					$count = count($result2);
					$result_row = array_values($result1); // split array in to two array result_row = Printable Color table data
					$result_lang = array_values($result2); // $result_lang = Printable Color language data

					$insert_data = array(
							'printablecolor_category_id' => $result_row[0],
							'color_code' => $result_row[1],
							'c' => $result_row[2],
							'm' => $result_row[3],
							'y' => $result_row[4],
							'k' => $result_row[5],
							'status' => $result_row[6],
							'position' => $result_row[7]
					);
					$printablecolor_id = $this->Designnbuy_printablecolors_model->insert_printable_color_csv($insert_data);
					$counter = 0;

					for ($i = 0; $i < $count / 2; $i++) { // run for loop two get exact two data for language table
						$lang_data = array(
								'printablecolor_id' => $printablecolor_id,
								'language_id' => $result_lang[$counter],
								'name' => $result_lang[$counter + 1]
						);

						$counter = $counter + 2;
						$this->Designnbuy_printablecolors_model->insert_printable_color_lang_csv($lang_data);
					}

				}
			$this->session->set_flashdata('msg', 'Csv Data Imported Succesfully');
			redirect(BASE_ADMIN_URL.'printablecolors');
		    } else {
			$data['error'] = "Error occured...!";
			$this->layout_admin->view('admin/printablecolors/importcsv', $data);
		    }
		} else {
		    $data['error'] = "CSV File format doesn't match. please check import file...!";
		    $this->layout_admin->view('admin/printablecolors/importcsv', $data);
		}
	    } else {
		$data['error'] = "Empty CSV File...!";
		$this->layout_admin->view('admin/printablecolors/importcsv', $data);
	    }
	}
    }
    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_printablecolors_model->updateSortableRow($param);
    }

}