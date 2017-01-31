<?php

class Designnbuy_settings_model extends CI_Model {

    /**
     * Constructor of the Square Area model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /**
     * @return genreal setting single row result set
     */
    function getGeneralSettingsRow() {
	$query = $this->db->get_where('designnbuy_general_settings', array('id' => '1'));
	return $query->row_array();
    }

    function getGlobalSideLabels() {
	$query = $this->db->get_where('designnbuy_global_sidelabels', array('general_settings_id' => '1'));
	foreach ($query->result_array() as $result) {
	    $sideLables[$result['language_id']] = array(
		'side_1_label' => $result['side_1_label'],
		'side_2_label' => $result['side_2_label'],
		'side_3_label' => $result['side_3_label'],
		'side_4_label' => $result['side_4_label'],
		'side_5_label' => $result['side_5_label'],
		'side_6_label' => $result['side_6_label'],
		'side_7_label' => $result['side_7_label'],
		'side_8_label' => $result['side_8_label']
	    );
	}
	return $sideLables;
    }
    function getAdminNotificationEmails() {
	$query = $this->db->get('designnbuy_message_notification_admin');
	return $query->result_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateGeneralSettings($param) {
	if (!empty($param['email'])) {
	    $this->db->delete('designnbuy_message_notification_admin', array('admin_notification_id != ' => ''));
	    foreach ($param['email'] as $email) {
		$emaildata = array(
		    'email' => $email,
		    'user_type' => 'admin'
		);
		$this->db->insert('designnbuy_message_notification_admin', $emaildata);
	    }
	}

	$data = array(
	    'base_unit' => $param['base_unit'],
	    'output_format' => $param['output_format'],
	    'pdf_output_type' => $param['pdf_output_type'],
	    'image_resolution' => $param['image_resolution'],
	    'watermark_status' => $param['watermark_status']
	    
	);

	$this->db->where('id', $param['id']);
	$this->db->update('designnbuy_general_settings', $data);
	$this->db->delete('designnbuy_global_sidelabels', array('general_settings_id' => $param['id']));
	foreach ($param['sidelabels'] as $language_id => $value) {
	    $langdata = array();
	    $langdata = array(
		'general_settings_id' => $param['id'],
		'language_id' => $language_id,
		'side_1_label' => $value['side_1_label'],
		'side_2_label' => $value['side_2_label'],
		'side_3_label' => $value['side_3_label'],
		'side_4_label' => $value['side_4_label'],
		'side_5_label' => $value['side_5_label'],
		'side_6_label' => $value['side_6_label'],
		'side_7_label' => $value['side_7_label'],
		'side_8_label' => $value['side_8_label']
	    );
	    $this->db->insert('designnbuy_global_sidelabels', $langdata);
	}
	return $param['id'];
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateSocialMediaSettings($param) {
	$data = array(
	    'facebook_app_id' => $param['facebook_app_id'],
	    'flicker_api_key' => $param['flicker_api_key'],
	    'instagram_client_id' => $param['instagram_client_id']
	);

	$this->db->where('id', $param['id']);
	$this->db->update('designnbuy_general_settings', $data);
    }

    /**
     * @return configure feature single row result set
     */
    function getConfigureFeature() {
	$query = $this->db->get_where('designnbuy_configure_features', array('id' => '1'));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateConfigureFeature($param) {
	$data = array();
	if($param['standard_text'] == '0') {
	    $data['standard_text'] = '0';
	    $data['text_effects'] = '0';
	} else {
	    $data['standard_text'] = $param['standard_text'];
	    $data['text_effects'] = $param['text_effects'];
	}
	
	if($param['cliparts'] == '0') {
	    $data['cliparts'] = '0';
	   // $data['editable_design_ideas'] = '0';
	    $data['show_clipart'] = '0';
	    $data['show_designidea'] = '0';
	    $data['free_hand_shapes'] = '0';
	} else {
	    $data['cliparts'] = $param['cliparts'];
	  //  $data['editable_design_ideas'] = $param['editable_design_ideas'];
	    $data['show_clipart'] = $param['show_clipart'];
	    $data['show_designidea'] = $param['show_designidea'];
	    $data['free_hand_shapes'] = $param['free_hand_shapes'];
	}
	
	if($param['image_upload'] == '0') {
	    $data['image_upload'] = '0';
	    $data['advance_image_upload'] = '0';
	    $data['social_media_image_upload'] = '0';
	    $data['qr_code'] = '0';
	} else {
	    $data['image_upload'] = $param['image_upload'];
	    $data['advance_image_upload'] = $param['advance_image_upload'];
	    $data['social_media_image_upload'] = $param['social_media_image_upload'];
	    $data['qr_code'] = $param['qr_code'];
	}
	
	$data['social_media_sharing'] = $param['social_media_sharing'];
	$data['preload_template'] = $param['preload_template'];
	$data['name_number'] = $param['name_number'];

	$this->db->where('id', $param['id']);
	$this->db->update('designnbuy_configure_features', $data);
    }

    /*
     * Update watermark logo 
     * @param Requested id $id,watermark_logo $watermark_logo
     */

    function updateWatermarkLogo($id, $watermark_logo) {
	$data = array('watermark_logo' => $watermark_logo);
	$this->db->where('id', $id);
	$this->db->update('designnbuy_general_settings', $data);
    }

    /**
     * @return Product Advance Configuration row result set
     */
    function getProductAdvanceConfiguration() {
	$query = $this->db->get_where('designnbuy_product_advance_configuration', array('id' => '1'));
	return $query->row_array();
    }

    /*
     * Update Product Advance Configuration
     * @param Requested id $id,option abbereviations $option_abbreviation
     */

    function updateProductAdvanceConfiguration($param) {
	$option_abbreviation = json_encode($param['option_abbreviation']);
	$data = array('value' => $option_abbreviation);
	$this->db->where('id', $param['id']);
	$this->db->where('name', $param['name']);
	$this->db->update('designnbuy_product_advance_configuration', $data);
    }
    
    function getHelpData($lang_id) {
	return $this->db->get_where('designnbuy_help_data',array('language_id' => $lang_id))->row_array();
    }
    
    function saveHelpdata($param,$lang_id) {
	$data = array('help_data_text' => htmlspecialchars($param['help_data_text']));
	$this->db->where('help_data_id',$param['help_data_id']);
	$this->db->where('language_id',$lang_id);
	$this->db->update('designnbuy_help_data', $data);
    }

}