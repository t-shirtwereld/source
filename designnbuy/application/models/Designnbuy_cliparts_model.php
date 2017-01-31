<?php

class Designnbuy_cliparts_model extends CI_Model {

    /**
     * Constructor of the Clipart model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Clipart list with pagination
     */

    function getClipartList($searchparam = NULL, $clipart_category_id = NULL, $limit = NULL, $start = NULL) {
	$this->db->distinct();
	$this->db->select('designnbuy_cliparts.*,designnbuy_cliparts_lang.name,designnbuy_clipart_categories_lang.name AS category_name');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories_lang.clipart_category_id = designnbuy_cliparts.clipart_category_id');
	$this->db->join('designnbuy_cliparts_lang', 'designnbuy_cliparts.clipart_id = designnbuy_cliparts_lang.clipart_id');
	$this->db->where('designnbuy_cliparts_lang.language_id', '1');
	$this->db->like('designnbuy_cliparts_lang.name', $searchparam);
	if (isset($clipart_category_id) && $clipart_category_id != '') {
	    $this->db->where('designnbuy_cliparts.clipart_category_id', $clipart_category_id);
	}
	if ($limit !== NULL && $start !== NULL) {
	    $this->db->limit($limit, $start);
	}
	$this->db->group_by('designnbuy_cliparts.clipart_id');
	$this->db->order_by('designnbuy_cliparts.clipart_id', 'ASC');
	$query = $this->db->get("designnbuy_cliparts");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested clipart_id $clipart_id
     * @return clipart single row result set
     */
    function getClipartRow($clipart_id) {
	$query = $this->db->get_where('designnbuy_cliparts', array('clipart_id' => $clipart_id));
	return $query->row_array();
    }

    /** Get clipart names for single category
     * @param Requested clipart_id $clipart_id
     * @return clipart array result set
     */
    function getClipartNamesList($clipart_id) {
	$query = $this->db->get_where('designnbuy_cliparts_lang', array('clipart_id' => $clipart_id));
	foreach ($query->result_array() as $result) {
	    $clipart_names[$result['language_id']] = array(
		'name' => $result['name']
	    );
	}
	return $clipart_names;
    }

    /**
     * Insert/update records
     * @param type $param
     */
     function updateClipartRow($param) {
// print '<pre>';
// print_r($param);
// exit;
       if (isset($param['clipart_price']) && $param['clipart_price'] != '') {
			$param['clipart_price'] = $param['clipart_price'];
		} else {
			$param['clipart_price'] = 0.00;
		} 

	$data = array(
	    'clipart_category_id' => $param['clipart_category_id'],
	    'is_clipart_design' => $param['is_clipart_design'],
	    'clipart_price' => $param['clipart_price'],
	    'status' => $param['status']
	);
// print '<pre>';
// print_r($data);
// exit;
	if ($param['clipart_id'] != '') {
	    $this->db->where('clipart_id', $param['clipart_id']);
	    $this->db->update('designnbuy_cliparts', $data);
	    $this->db->delete('designnbuy_cliparts_lang', array('clipart_id' => $param['clipart_id']));
	    foreach ($param['clipart_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'clipart_id' => $param['clipart_id'],
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_cliparts_lang', $langdata);
	    }
	    return $param['clipart_id'];
	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_cliparts');
	    $position = $position_query->row_array();
	    if (isset($position['position']) && $position['position'] != '') {
			$data['position'] = $position['position'];
	    } else {
			$data['position'] = 1;
	    }
		// if (isset($position['clipart_price']) && $position['clipart_price'] != '') {
		// 	$data['clipart_price'] = $position['clipart_price'];
		// } else {
		// 	$data['clipart_price'] = 0.00;
		// }
	    $this->db->insert('designnbuy_cliparts', $data);
	    $clipart_id = $this->db->insert_id();

	    foreach ($param['clipart_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'clipart_id' => $clipart_id,
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_cliparts_lang', $langdata);
	    }
	    return $clipart_id;
	}
    }

    /*
     * Get Clipart Category List 
     */

    function getCategoryList() {
	//$this->db->where('designnbuy_clipart_categories.parent_category_id', '0');
	$this->db->where('designnbuy_clipart_categories_lang.language_id', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$result = $this->db->get('designnbuy_clipart_categories')->result_array();
	$categories = array();
	foreach ($result as $res) {
	    $categories[] = array(
		'clipart_category_id' => $res['clipart_category_id'],
		//'parent_category_id' => $res['parent_category_id'],
		'name' => $res['name'],
		'status' => $res['status']
		//'child' => $this->getSubCategoryList($res['clipart_category_id'])
	    );
	}
	return $categories;
    }

    function getSubCategoryList($parent_category_id) {
	$this->db->where('designnbuy_clipart_categories.parent_category_id', $parent_category_id);
	$this->db->where('designnbuy_clipart_categories_lang.language_id', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	return $this->db->get('designnbuy_clipart_categories')->result_array();
    }

    function updateClipartImage($clipart_id, $clipart_image_name) {
	$data = array('clipart_image' => $clipart_image_name);
	$this->db->where('clipart_id', $clipart_id);
	$this->db->update('designnbuy_cliparts', $data);
    }

    /**
     * 
     * @param Requested clipart_id $clipart_id
     */
    function deleteClipartRow($clipart_id) {
	$row = $this->getClipartRow($clipart_id);
	$file = TOOL_IMG_PATH . '/cliparts/' . $row['clipart_image'];
	if (!empty($row['clipart_image']) && file_exists($file)) {
	    unlink($file);
	}
	$this->db->delete('designnbuy_cliparts', array('clipart_id' => $clipart_id));
	$this->db->delete('designnbuy_cliparts_lang', array('clipart_id' => $clipart_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Clipart Count 
     */
    function record_count($searchparam = NULL, $clipart_category_id = NULL) {
	$this->db->distinct();
	$this->db->select('designnbuy_cliparts.*,designnbuy_cliparts_lang.name,designnbuy_clipart_categories_lang.name AS category_name');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories_lang.clipart_category_id = designnbuy_cliparts.clipart_category_id');
	$this->db->join('designnbuy_cliparts_lang', 'designnbuy_cliparts.clipart_id = designnbuy_cliparts_lang.clipart_id');
	$this->db->where('designnbuy_cliparts_lang.language_id', '1');
	$this->db->like('designnbuy_cliparts_lang.name', $searchparam);
	if (isset($clipart_category_id) && $clipart_category_id != '') {
	    $this->db->where('designnbuy_cliparts.clipart_category_id', $clipart_category_id);
	}
	$this->db->group_by('designnbuy_cliparts.clipart_id');
	$query = $this->db->get("designnbuy_cliparts");
	return $query->num_rows();
    }

    /*
     * Clipart table csv data import entries
     */

    function insert_clipart_csv($data) {
	$this->db->insert('designnbuy_cliparts', $data);
	return $this->db->insert_id();
    }

    /*
     * Clipart language table csv data import entries
     */

    function insert_clipart_lang_csv($data) {
	$this->db->insert('designnbuy_cliparts_lang', $data);
    }

    /*
     *  Update position of rows
     */

    function updateSortableRow($data) {
	foreach ($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('clipart_id', $key);
	    $this->db->update('designnbuy_cliparts', $result);
	}
    }

    function getProductId($clipart_id) {
	$this->db->select('product_id');
	$query = $this->db->get_where('designnbuy_cliparts', array('clipart_id' => $clipart_id));
	$row = $query->row_array();
	if ($row['product_id'] != 0) {
	    $product_id = $row['product_id'];
	} else {
	    $this->db->select('product_id');
	    $product_query = $this->db->get_where('designnbuy_product', array('status' => '1'));
	    $product_row = $product_query->row_array();
	    $product_id = $product_row['product_id'];
	}
	return $product_id;
    }

    function saveClipartData($id, $data) {
	$this->db->where('clipart_id', $id);
	$this->db->update('designnbuy_cliparts', $data);
    }
    
    function getConfigureFeature() {
	return $this->db->get('designnbuy_configure_features')->row_array();
    }

}