<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Printingmethods extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//Load pagination library
	$this->load->library("pagination");
    }

    /**
     * printingmethod listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Printingmethods';

	$searchparam = trim($this->input->get('keyword'));
	$data['keyword'] = $searchparam;
	$limit = trim($this->input->get('limit'));
	$data['limit'] = $limit;
	$pricing_logic = trim($this->input->get('pricing_logic'));
	$data['pricing_logic'] = $pricing_logic;
	$printable_color_type = trim($this->input->get('printable_color_type'));
	$data['printable_color_type'] = $printable_color_type;

	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'printingmethods/index?keyword=' . $searchparam . '&pricing_logic=' . $pricing_logic . '&printable_color_type=' . $printable_color_type . '&limit=' . $limit;
	$config['total_rows'] = $this->Designnbuy_printing_methods_model->record_count($searchparam, $pricing_logic, $printable_color_type);
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

	$data['printingmethods'] = $this->Designnbuy_printing_methods_model->getPrintingmethodsList($searchparam, $pricing_logic, $printable_color_type, $config["per_page"], $page);

	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$this->layout_admin->view('admin/printingmethods/list', $data);
    }

    /**
     * Show when you add any printingmethod.
     */
    public function addprintingmethod() {
	$data['title'] = 'Add Printingmethod';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['color_categories'] = $this->Designnbuy_printing_methods_model->getPrintableColorCategories();
	$this->layout_admin->view('admin/printingmethods/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();
	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('min_qty', 'Min Quantity', 'required');
	if ($param['printing_method_id'] != '') {
	    $this->form_validation->set_rules('printing_method_id', 'Pricing data', 'callback_findDuplicateData');
	}
	if ($this->form_validation->run() == FALSE) {
	    // if printing_method_id is available then show the edit page else show add page
	    if ($param['printing_method_id'] != '') {
		$this->editprintingmethod($param['printing_method_id']);
	    }
	    else
		$this->addprintingmethod();
	}
	else {
	    $this->Designnbuy_printing_methods_model->updatePrintingmethodRow($param);
	    if ($param['printing_method_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have added new Printing Method');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have modified Printing Method');
	    }
	    redirect(BASE_ADMIN_URL . 'printingmethods');
	}
    }

    public function findDuplicateData() {
	$totaldata = $this->input->post();
	$data = array();
	if (!empty($totaldata['fixedPrice'])) {
	    foreach ($totaldata['fixedPrice'] as $d) {
		$data[] = array(
		    'quantity_range_id' => $d['quantity_range_id']
		);
	    }
	} else if (!empty($totaldata['qaPrice'])) {
	    foreach ($totaldata['qaPrice'] as $d) {
		$data[] = array(
		    'quantity_range_id' => $d['quantity_range_id'],
		    'sqarea_id' => $d['sqarea_id']
		);
	    }
	} else if (!empty($totaldata['qcPrice'])) {
	    foreach ($totaldata['qcPrice'] as $d) {
		$data[] = array(
		    'quantity_range_id' => $d['quantity_range_id'],
		    'color_counter_id' => $d['color_counter_id']
		);
	    }
	}
	$results = array();
	$duplicates = array();
	$reverseData = array_reverse($data, true);
	foreach ($reverseData as $key => $item) {
	    if (in_array($item, $results)) {
		$duplicates[$key] = $item;
	    }

	    $results[] = $item;
	}
	if (!empty($duplicates)) {
	    $this->form_validation->set_message('findDuplicateData', 'Duplicate Pricing Data...!');
	    return false;
	} else {
	    return true;
	}
    }

    /*
     * Modify page
     */

    public function editprintingmethod($printing_method_id) {
	$data['title'] = 'Edit Printingmethod';
	$data['printingmethod_row'] = $this->Designnbuy_printing_methods_model->getPrintingmethodRow($printing_method_id);
	$data['printingmethod_names'] = $this->Designnbuy_printing_methods_model->getPrintingmethodNamesList($printing_method_id);
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['color_categories'] = $this->Designnbuy_printing_methods_model->getPrintableColorCategories();
	$data['qranges'] = $this->Designnbuy_printing_methods_model->getQuantityRange();
	$data['colorcounters'] = $this->Designnbuy_printing_methods_model->getColorCounter();
	$data['sqareas'] = $this->Designnbuy_printing_methods_model->getSquareArea();
	$this->layout_admin->view('admin/printingmethods/edit', $data);
    }

    /**
     * Delete category
     */
    public function deleteprintingmethod($printing_method_id) {
	$result = $this->Designnbuy_printing_methods_model->deletePrintingmethodRow($printing_method_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function updateSortableRow() {
	$param = $this->input->post();
	$this->Designnbuy_printing_methods_model->updateSortableRow($param);
    }

}