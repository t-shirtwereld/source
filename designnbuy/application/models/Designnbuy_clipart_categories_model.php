<?php

class Designnbuy_clipart_categories_model extends CI_Model {

    /**
     * Constructor of the Clipart category model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Clipart category list with pagination - getting category name as parent cateogry name -> child category name with using if statement and two joins 
     */

    function getCategoryList($searchparam = NULL, $limit = NULL, $start = NULL) {
	$sql = "SELECT cp.*,cd1.name FROM designnbuy_clipart_categories cp LEFT JOIN designnbuy_clipart_categories_lang cd1 ON (cp.clipart_category_id = cd1.clipart_category_id) WHERE cd1.language_id = '1' AND cd1.name LIKE '%".$searchparam."%' GROUP BY cp.clipart_category_id ORDER BY position ASC, name ";
	
	if($limit !== NULL && $start !== NULL){
	    $sql .= "LIMIT $start,$limit";
	}
	
	$query = $this->db->query($sql);
	return $query->result_array();
    }

    /* get parent category list 
     * @param parent_category_id = 0 
     * @return array of parent categories
     */

   /* function getParentCategoryList() {
	$this->db->where('designnbuy_clipart_categories_lang.language_id', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$query = $this->db->get_where('designnbuy_clipart_categories', array('designnbuy_clipart_categories.parent_category_id' => '0'));
	return $query->result_array();
    } */

    /**
     * 
     * @param Requested clipart_category_id $clipart_category_id
     * @return category single row result set
     */
    function getCategoryRow($clipart_category_id) {
	$query = $this->db->get_where('designnbuy_clipart_categories', array('clipart_category_id' => $clipart_category_id));
	return $query->row_array();
    }

    /** Get category names for single category
     * @param Requested clipart_category_id $clipart_category_id
     * @return category array result set
     */
    function getCategoryNamesList($clipart_category_id) {
	$query = $this->db->get_where('designnbuy_clipart_categories_lang', array('clipart_category_id' => $clipart_category_id));
	foreach ($query->result_array() as $result) {
	    $category_names[$result['language_id']] = array(
		'name' => $result['name']
	    );
	}
	return $category_names;
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateCategoryRow($param) {
	$data = array(
	 //   'parent_category_id' => $param['parent_category_id'],
	    'status' => $param['status']
	);

	if ($param['clipart_category_id'] != '') {
	    $this->db->where('clipart_category_id', $param['clipart_category_id']);
	    $this->db->update('designnbuy_clipart_categories', $data);
	    $this->db->delete('designnbuy_clipart_categories_lang', array('clipart_category_id' => $param['clipart_category_id']));
	    foreach ($param['category_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'clipart_category_id' => $param['clipart_category_id'],
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_clipart_categories_lang', $langdata);
	    }
	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_clipart_categories');
	    $position = $position_query->row_array();
	    if(isset($position['position']) && $position['position'] != ''){		 
		 $data['position'] = $position['position'];
	    } else {
		 $data['position'] = 1;
	    }
	    $this->db->insert('designnbuy_clipart_categories', $data);
	    $clipart_category_id = $this->db->insert_id();
	    foreach ($param['category_name'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'clipart_category_id' => $clipart_category_id,
		    'language_id' => $language_id,
		    'name' => $value['name']
		);
		$this->db->insert('designnbuy_clipart_categories_lang', $langdata);
	    }
	}
    }

    /**
     * 
     * @param Requested clipart_category_id $clipart_category_id
     */
    function deleteCategoryRow($clipart_category_id) {
	$this->db->select('clipart_category_id');
	$this->db->where('clipart_category_id',$clipart_category_id);
	//$this->db->or_where('parent_category_id',$clipart_category_id);
	$query = $this->db->get('designnbuy_clipart_categories');
	$result = $query->result_array();
	
	foreach($result as $res) {    
	    $this->db->select('clipart_id,clipart_image');
	    $clipart_query = $this->db->get_where('designnbuy_cliparts',array('clipart_category_id' => $res['clipart_category_id']));
	    $clipart_result = $clipart_query->result_array();	    
	    foreach($clipart_result as $cres) {
		
		$file = TOOL_IMG_PATH.'/cliparts/'.$cres['clipart_image'];		
		if(!empty($cres['clipart_image']) && file_exists($file)) {
		    unlink($file);
		}
		$this->db->delete('designnbuy_cliparts', array('clipart_id' => $cres['clipart_id']));
		$this->db->delete('designnbuy_cliparts_lang', array('clipart_id' => $cres['clipart_id']));
	    }	
	    $this->db->delete('designnbuy_clipart_categories', array('clipart_category_id' => $res['clipart_category_id']));
	    $this->db->delete('designnbuy_clipart_categories_lang', array('clipart_category_id' => $res['clipart_category_id']));
	    return $this->db->affected_rows();
	}
	
    }

    /**
     *  Total Category Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->where('designnbuy_clipart_categories_lang.language_id','1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$this->db->like('designnbuy_clipart_categories_lang.name', $searchparam);
	$this->db->group_by('designnbuy_clipart_categories.clipart_category_id');
	$query = $this->db->get("designnbuy_clipart_categories");
	return $query->num_rows(); 
    }
    
    /*
     * Clipart Category table csv data import entries
     */
    function insert_cateogry_csv($data) {
		$this->db->insert('designnbuy_clipart_categories',$data);
		return $this->db->insert_id();
    }
    
    /*
     * Clipart Category language table csv data import entries
     */
    function insert_cateogry_lang_csv($data) {
	$this->db->insert('designnbuy_clipart_categories_lang',$data);
    }
    
    /*
     *  Update position of rows
     */
    function updateSortableRow($data) {
	foreach($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('clipart_category_id', $key);
	    $this->db->update('designnbuy_clipart_categories', $result);
	}
    }

}