<?php

class Designnbuy_printablecolor_categories_model extends CI_Model {

    /**
     * Constructor of the Printablecolor category model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Printablecolor category list with pagination - getting category name as parent cateogry name -> child category name with using if statement and two joins 
     */

    function getCategoryList($searchparam = NULL, $limit = NULL, $start = NULL) {
	$this->db->like('designnbuy_printablecolor_categories.category_name', $searchparam);
	if($limit !== NULL && $start !== NULL){
	    $this->db->limit($limit,$start);
	}
	$this->db->order_by('designnbuy_printablecolor_categories.position','ASC');
	$query = $this->db->get("designnbuy_printablecolor_categories");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested printablecolor_category_id $printablecolor_category_id
     * @return category single row result set
     */
    function getCategoryRow($printablecolor_category_id) {
	$query = $this->db->get_where('designnbuy_printablecolor_categories', array('printablecolor_category_id' => $printablecolor_category_id));
	return $query->row_array();
    }


    /**
     * Insert/update records
     * @param type $param
     */
    function updateCategoryRow($param) {
	$data = array(
	    'category_name' => $param['name'],
	    'status' => $param['status']
	);

	if ($param['printablecolor_category_id'] != '') {
	    $this->db->where('printablecolor_category_id', $param['printablecolor_category_id']);
	    $this->db->update('designnbuy_printablecolor_categories', $data);

	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_printablecolor_categories');
	    $position = $position_query->row_array();
	    if(isset($position['position']) && $position['position'] != ''){		 
		 $data['position'] = $position['position'];
	    } else {
		 $data['position'] = 1;
	    }
	    $this->db->insert('designnbuy_printablecolor_categories', $data);
	}
    }

    /**
     * 
     * @param Requested printablecolor_category_id $printablecolor_category_id
     */
    function deleteCategoryRow($printablecolor_category_id) {
	$query = $this->db->get_where('designnbuy_printablecolors', array('printablecolor_category_id' => $printablecolor_category_id));
	$result = $query->result_array();
	foreach($result as $res) {
	    $this->db->delete('designnbuy_printablecolors', array('printablecolor_id' => $res['printablecolor_id']));
	    $this->db->delete('printcommerce_printablecolors_lang', array('printablecolor_id' => $res['printablecolor_id']));
	}
	$this->db->delete('designnbuy_printablecolor_categories', array('printablecolor_category_id' => $printablecolor_category_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Category Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->like('designnbuy_printablecolor_categories.category_name', $searchparam);
	$query = $this->db->get("designnbuy_printablecolor_categories");
	return $query->num_rows(); 
    }
    
    /*
     * Get printable color category row based on name
     */
    function getPrintableColorCategoryByName($category_name = NULL) {
	$query = $this->db->get_where('designnbuy_printablecolor_categories',array('category_name' => $category_name));
	return $query->row_array();
    }
    
    /*
     * Printable Color Category table csv data import entries
     */
    function insert_cateogry_csv($data) {
	$this->db->insert('designnbuy_printablecolor_categories',$data);
    }
    /*
     *  Update position of rows
     */
    function updateSortableRow($data) {
	foreach($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('printablecolor_category_id', $key);
	    $this->db->update('designnbuy_printablecolor_categories', $result);
	}
    }

}