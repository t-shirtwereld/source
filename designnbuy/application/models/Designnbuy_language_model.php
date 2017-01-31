<?php

class Designnbuy_language_model extends CI_Model {

    /**
     * Constructor of the language model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get language list
     */

    function getLanguageList() {
	$query = $this->db->get('designnbuy_language');
	return $query->result_array();
    }

    /**
     * 
     * @param Requested language_id $language_id
     * @return language single row result set
     */
    function getLanguageRow($language_id) {
	$query = $this->db->get_where('designnbuy_language', array('language_id' => $language_id));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateLanguageRow($param) {
	$data = array(
	    'name' => $param['name'],
	    'iso_code' => $param['iso_code'],
	    'language_code' => $param['language_code'],
	    'image' => $param['language_code'].'.png',
	    'is_rtl' => $param['is_rtl'],
	    'connector' => $param['connector'],
	    'status' => $param['status']
	);

	if ($param['language_id'] != '') {
	    $this->db->where('language_id', $param['language_id']);
	    $this->db->update('designnbuy_language', $data);
	} else {
	    $this->db->select('MAX(short_order) + 1 AS short_order');
	    $position_query = $this->db->get('designnbuy_language');
	    $position = $position_query->row_array();
	    if(isset($position['short_order']) && $position['short_order'] != ''){		 
		 $data['short_order'] = $position['short_order'];
	    } else {
		 $data['short_order'] = 1;
	    }
	    $this->db->insert('designnbuy_language', $data);
	}
    }
    
    public function deleteLanguageRow($language_id) {	
	$this->db->delete('designnbuy_cliparts_lang',array('language_id'=>$language_id));
	$this->db->delete('designnbuy_clipart_categories_lang',array('language_id'=>$language_id));
	$this->db->delete('designnbuy_printablecolors_lang',array('language_id'=>$language_id));
	$this->db->delete('designnbuy_printing_methods_lang',array('language_id'=>$language_id));
	$this->db->delete('designnbuy_language',array('language_id'=>$language_id));
	return $this->db->affected_rows();
    }

}