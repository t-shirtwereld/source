<?php

class Designnbuy_printablecolors_model extends CI_Model {

    /**
     * Constructor of the Printablecolor model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Printablecolor list with pagination
     */

    function getPrintablecolorList($searchparam = '', $category_id = NULL, $limit = NULL, $start = NULL) {
	$this->db->select('designnbuy_printablecolors.*,designnbuy_printablecolors_lang.language_id,designnbuy_printablecolors_lang.name,designnbuy_printablecolor_categories.category_name,designnbuy_printablecolor_categories.printablecolor_category_id');
	$this->db->where('designnbuy_printablecolors_lang.language_id', '1');
	if(isset($category_id) && $category_id != '') {
	   $this->db->where('designnbuy_printablecolors.printablecolor_category_id',$category_id); 
	}
	$this->db->join('designnbuy_printablecolors_lang', 'designnbuy_printablecolors.printablecolor_id = designnbuy_printablecolors_lang.printablecolor_id');
	$this->db->join('designnbuy_printablecolor_categories', 'designnbuy_printablecolors.printablecolor_category_id = designnbuy_printablecolor_categories.printablecolor_category_id');
	if(isset($searchparam) && $searchparam != '') {
	$this->db->like('designnbuy_printablecolors_lang.name', $searchparam); 
	$this->db->or_like('designnbuy_printablecolors.color_code', $searchparam);
	$this->db->or_like('CONCAT_WS("",designnbuy_printablecolors.c,designnbuy_printablecolors.m,designnbuy_printablecolors.y,designnbuy_printablecolors.k)',preg_replace('/\s+/', '', $searchparam));
	}
	if ($limit !== NULL && $start !== NULL) {
	    $this->db->limit($limit, $start);
	}
	$this->db->group_by('designnbuy_printablecolors.printablecolor_id');
	$this->db->order_by('designnbuy_printablecolors.position','ASC');
	$query = $this->db->get("designnbuy_printablecolors");
	//echo $this->db->last_query(); exit;
	return $query->result_array();
    }

    /**
     * 
     * @param Requested printablecolor_id $printablecolor_id
     * @return printablecolor single row result set
     */
    function getPrintablecolorRow($printablecolor_id) {
	$query = $this->db->get_where('designnbuy_printablecolors', array('printablecolor_id' => $printablecolor_id));
	return $query->row_array();
    }

    /** Get printablecolor names for single category
     * @param Requested printablecolor_id $printablecolor_id
     * @return printablecolor array result set
     */
    function getPrintablecolorNamesList($printablecolor_id) {
	$query = $this->db->get_where('designnbuy_printablecolors_lang', array('printablecolor_id' => $printablecolor_id));
	foreach ($query->result_array() as $result) {
	    $printablecolor_names[$result['language_id']] = array(
		'language_id' => $result['language_id'],
		'name' => $result['name']
	    );
	}
	return $printablecolor_names;
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updatePrintablecolorRow($param) {
	$data = array(
	    'printablecolor_category_id' => $param['printablecolor_category_id'],
	    'color_code' => $param['color_code'],
	    'c' => 0,
	    'm' => 0,
	    'y' => 0,
	    'k' => 0,
	    'status' => $param['status']
	);

	if ($param['printablecolor_id'] != '') {
	    $this->db->where('printablecolor_id', $param['printablecolor_id']);
	    $this->db->update('designnbuy_printablecolors', $data);
	    $this->db->delete('designnbuy_printablecolors_lang', array('printablecolor_id' => $param['printablecolor_id']));
	    foreach ($param['printablecolor_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'printablecolor_id' => $param['printablecolor_id'],
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_printablecolors_lang', $langdata);
	    }
	    return $param['printablecolor_id'];
	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_printablecolors');
	    $position = $position_query->row_array();
	    if(isset($position['position']) && $position['position'] != ''){		 
		 $data['position'] = $position['position'];
	    } else {
		 $data['position'] = 1;
	    }
		$data['c'] = 0;
		$data['m'] = 0;
		$data['y'] = 0;
		$data['k'] = 0;
	    $this->db->insert('designnbuy_printablecolors', $data);
	    $printablecolor_id = $this->db->insert_id();
	    foreach ($param['printablecolor_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'printablecolor_id' => $printablecolor_id,
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_printablecolors_lang', $langdata);
	    }
	    return $printablecolor_id;
	}
    }

    /*
     * Get Printablecolor Category List 
     */

    function getCategoryList() {
	$query = $this->db->get('designnbuy_printablecolor_categories');
	return $query->result_array();
    }

    /**
     * 
     * @param Requested printablecolor_id $printablecolor_id
     */
    function deletePrintablecolorRow($printablecolor_id) {
	$this->db->delete('designnbuy_printablecolors', array('printablecolor_id' => $printablecolor_id));
	$this->db->delete('designnbuy_printablecolors_lang', array('printablecolor_id' => $printablecolor_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Printablecolor Count 
     */
    function record_count($searchparam = NULL,$category_id = NULL) {	
	$this->db->where('designnbuy_printablecolors_lang.language_id', '1');
	if(isset($category_id) && $category_id != '') {
	   $this->db->where('designnbuy_printablecolors.printablecolor_category_id',$category_id); 
	}
	$this->db->join('designnbuy_printablecolors_lang', 'designnbuy_printablecolors.printablecolor_id = designnbuy_printablecolors_lang.printablecolor_id');
	$this->db->join('designnbuy_printablecolor_categories', 'designnbuy_printablecolors.printablecolor_category_id = designnbuy_printablecolor_categories.printablecolor_category_id');
	if(isset($searchparam) && $searchparam != '') {
	$this->db->like('designnbuy_printablecolors_lang.name', $searchparam); 
	$this->db->or_like('designnbuy_printablecolors.color_code', $searchparam);
	$this->db->or_like('CONCAT_WS("",designnbuy_printablecolors.c,designnbuy_printablecolors.m,designnbuy_printablecolors.y,designnbuy_printablecolors.k)',preg_replace('/\s+/', '', $searchparam));
	}
	$this->db->group_by('designnbuy_printablecolors.printablecolor_id');
	$query = $this->db->get("designnbuy_printablecolors");
	return $query->num_rows();
    }
    
    /*
     * Printable Color table csv data import entries
     */
    function insert_printable_color_csv($data) {
	$this->db->insert('designnbuy_printablecolors',$data);
	return $this->db->insert_id();
    }
    
    /*
     * Printable Color language table csv data import entries
     */
    function insert_printable_color_lang_csv($data) {
	$this->db->insert('designnbuy_printablecolors_lang',$data);
    }
    /*
     *  Update position of rows
     */
    function updateSortableRow($data) {
	foreach($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('printablecolor_id', $key);
	    $this->db->update('designnbuy_printablecolors', $result);
	}
    }

}