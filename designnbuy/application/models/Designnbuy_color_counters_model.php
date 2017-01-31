<?php
class Designnbuy_color_counters_model extends CI_Model {

    /**
     * Constructor of the Color Counter model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Color Counter list with pagination
     */

    function getColorCountersList($searchparam = NULL, $limit = NULL, $start = NULL) {	
	$this->db->like('designnbuy_color_counters.color_counter', $searchparam);
	if($limit !== NULL && $start !== NULL){
	    $this->db->limit($limit,$start);
	}
	$this->db->group_by('designnbuy_color_counters.color_counter_id');
	$query = $this->db->get("designnbuy_color_counters");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested color_counter_id $color_counter_id
     * @return clipart single row result set
     */
    function getColorCounterRow($color_counter_id) {
	$query = $this->db->get_where('designnbuy_color_counters', array('color_counter_id' => $color_counter_id));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateColorCounterRow($param) {
	$data = array(
	    'color_counter' => $param['color_counter']
	);

	if ($param['color_counter_id'] != '') {
	    $this->db->where('color_counter_id', $param['color_counter_id']);
	    $this->db->update('designnbuy_color_counters', $data);
	} else {
	    $this->db->insert('designnbuy_color_counters', $data);
	    $color_counter_id = $this->db->insert_id();
	}
    }
    /**
     * 
     * @param Requested color_counter_id $color_counter_id
     */
    function deleteColorCounterRow($color_counter_id) {
	$this->db->delete('designnbuy_printing_methods_qcprice', array('color_counter_id' => $color_counter_id));
	$this->db->delete('designnbuy_color_counters', array('color_counter_id' => $color_counter_id));
	return $this->db->affected_rows();	
    }

    /**
     *  Total Color Counter Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->like('designnbuy_color_counters.color_counter', $searchparam);
	$this->db->group_by('designnbuy_color_counters.color_counter_id');
	$query = $this->db->get("designnbuy_color_counters");
	return $query->num_rows(); 
    } 
    
    /*
     *  check duplicate entry for square area on edit.
     */
    public function getColorCounterByNameOnEdit($color_counter,$color_counter_id) {
	$this->db->where('color_counter',$color_counter);
	$this->db->where('color_counter_id != ',$color_counter_id);
	$query = $this->db->get('designnbuy_color_counters');
	return $query->num_rows(); 	
    }
    
    /*
     *  check duplicate entry for square area on add.
     */
    public function getColorCounterByName($color_counter) {
	$this->db->where('color_counter',$color_counter);
	$query = $this->db->get('designnbuy_color_counters');
	return $query->num_rows();
    }
    
    /*
     * Color Counter table csv data import entries
     */
    function insert_color_counter_csv($data) {
	$this->db->insert('designnbuy_color_counters',$data);
    }
    
    /*
     * Color counter  rows based on color counter value
     */
    function getColorCounterByvalue($color_counter) {
	$query = $this->db->get_where('designnbuy_color_counters',array('color_counter' => $color_counter));
	return $query->row_array();
    }
    
}