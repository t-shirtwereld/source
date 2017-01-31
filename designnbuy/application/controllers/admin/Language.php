<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Language extends PC_Controller {

    public function __construct() {
	parent::__construct();
    }

    /**
     * language listing page with pagination and search
     */
    public function index() {
	$data['title'] = 'Languages';
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$this->layout_admin->view('admin/language/list', $data);
    }

    /**
     * Show when you add any language.
     */
    public function addlanguage() {
	$data['title'] = 'Add Language';
	$this->layout_admin->view('admin/language/add', $data);
    }

    /**
     * Submit page after insert/edit
     */
    public function submitdata() {
	// fetch all varialbe into one variable
	$param = $this->input->post();

	// Load form validation and validate it using server side
	$this->load->library('form_validation');
	$this->form_validation->set_rules('name', 'Language Name', 'required');
	$this->form_validation->set_rules('iso_code', 'ISO Code', 'required');
	$this->form_validation->set_rules('language_code', 'Language Code', 'required');
	if ($this->form_validation->run() == FALSE) {
	    // if sqarea_id is available then show the edit page else show add page
	    if ($param['language_id'] != '') {
		$this->editlanguage($param['language_id']);
	    }
	    else
		$this->addlanguage();
	}
	else {
	    $this->Designnbuy_language_model->updateLanguageRow($param);
	    if ($param['printing_method_id'] != '') {
		$this->session->set_flashdata('msg', 'Success: You have added new Language');
	    } else {
		$this->session->set_flashdata('msg', 'Success: You have modified language');
	    }
	    redirect(BASE_ADMIN_URL.'language');
	}
    }

    /*
     * Modify page
     */

    public function editlanguage($language_id) {
	$data['title'] = 'Edit Language';
	$data['language_row'] = $this->Designnbuy_language_model->getLanguageRow($language_id);
	$this->layout_admin->view('admin/language/edit', $data);
    }

    /**
     * Delete language
     */
    public function deletelanguage($language_id) {
	$result = $this->Designnbuy_language_model->deleteLanguageRow($language_id);
	if($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

}