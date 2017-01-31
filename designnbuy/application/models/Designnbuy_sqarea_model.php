<?php
class Designnbuy_sqarea_model extends CI_Model {

   /**
     * Constructor of the Square Area model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Square Area list with pagination
     */

    function getSquareAreasList($searchparam = NULL, $limit = NULL, $start = NULL) {	
	$this->db->like('designnbuy_sqarea.square_area', $searchparam);
	if($limit !== NULL && $start !== NULL){
	    $this->db->limit($limit,$start);
	}
	$this->db->group_by('designnbuy_sqarea.sqarea_id');
	$query = $this->db->get("designnbuy_sqarea");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested square_area_id $square_area_id
     * @return square area single row result set
     */
    function getSquareAreaRow($square_area_id) {
	$query = $this->db->get_where('designnbuy_sqarea', array('sqarea_id' => $square_area_id));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateSquareAreaRow($param) {
	$data = array(
	    'square_area' => $param['square_area']
	);

	if ($param['sqarea_id'] != '') {
	    $this->db->where('sqarea_id', $param['sqarea_id']);
	    $this->db->update('designnbuy_sqarea', $data);
	} else {
	    $this->db->insert('designnbuy_sqarea', $data);
	    $square_area_id = $this->db->insert_id();
	}
    }
    /**
     * 
     * @param Requested square_area_id $square_area_id
     */
    function deleteSquareAreaRow($square_area_id) {	
	$this->db->delete('designnbuy_printing_methods_qaprice', array('sqarea_id' => $square_area_id));
	$this->db->delete('designnbuy_sqarea', array('sqarea_id' => $square_area_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Square Area Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->like('designnbuy_sqarea.square_area', $searchparam);
	$this->db->group_by('designnbuy_sqarea.sqarea_id');
	$query = $this->db->get("designnbuy_sqarea");
	return $query->num_rows(); 
    }  
    /*
     *  check duplicate entry for square area on edit.
     */
    public function getSquareAreaByNameOnEdit($square_area,$sqarea_id) {
	$this->db->where('square_area',$square_area);
	$this->db->where('sqarea_id != ',$sqarea_id);
	$query = $this->db->get('designnbuy_sqarea');
	return $query->num_rows(); 	
    }
    
    /*
     *  check duplicate entry for square area on add.
     */
    public function getSquareAreaByName($square_area) {
	$this->db->where('square_area',$square_area);
	$query = $this->db->get('designnbuy_sqarea');
	return $query->num_rows();
    }
    
    /*
     * square area table csv data import entries
     */
    function insert_sqarea_csv($data) {
	$this->db->insert('designnbuy_sqarea',$data);
    }
    
    /*
     * square area rows based on square_area value
     */
    function getSquareAreaByvalue($square_area) {
	$query = $this->db->get_where('designnbuy_sqarea',array('square_area' => $square_area));
	return $query->row_array();
    }
    
}