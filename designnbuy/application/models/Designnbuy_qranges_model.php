<?php
class Designnbuy_qranges_model extends CI_Model {

   /**
     * Constructor of the Quantity Range model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Quantity Range list with pagination
     */

    function getQuantityRangesList($searchparam = NULL, $limit = NULL, $start = NULL) {	
	$this->db->like('designnbuy_qranges.quantity_range_from', $searchparam);
	$this->db->or_like('designnbuy_qranges.quantity_range_to', $searchparam);
	if($limit !== NULL && $start !== NULL){
	    $this->db->limit($limit,$start);
	}
	$this->db->group_by('designnbuy_qranges.qrange_id');
	$query = $this->db->get("designnbuy_qranges");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested qrange_id qrange_id
     * @return quantity range single row result set
     */
    function getQuantityRangeRow($qrange_id) {
	$query = $this->db->get_where('designnbuy_qranges', array('qrange_id' => $qrange_id));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateQuantityRangeRow($param) {
	$data = array(
	    'quantity_range_from' => $param['quantity_range_from'],
	    'quantity_range_to' => $param['quantity_range_to']
	);

	if ($param['qrange_id'] != '') {
	    $this->db->where('qrange_id', $param['qrange_id']);
	    $this->db->update('designnbuy_qranges', $data);
	} else {
	    $this->db->insert('designnbuy_qranges', $data);
	}
    }
    /**
     * 
     * @param Requested qrange_id $qrange_id
     */
    function deleteQuantityRangeRow($qrange_id) {	
	$this->db->delete('designnbuy_printing_methods_qcprice', array('quantity_range_id ' => $qrange_id));
	$this->db->delete('designnbuy_printing_methods_qaprice', array('quantity_range_id' => $qrange_id));
	$this->db->delete('designnbuy_printing_methods_fixedprice', array('quantity_range_id' => $qrange_id));
	$this->db->delete('designnbuy_qranges', array('qrange_id' => $qrange_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Quantity Range Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->like('designnbuy_qranges.quantity_range_from', $searchparam);
	$this->db->or_like('designnbuy_qranges.quantity_range_to', $searchparam);
	$this->db->group_by('designnbuy_qranges.qrange_id');
	$query = $this->db->get('designnbuy_qranges');
	return $query->num_rows(); 
    }  
    
    /*
     *  check duplicate entry for quantity range on edit.
     */
    public function getQuantityRangeByNameOnEdit($quantity_range_from,$quantity_range_to,$qrange_id) {
	$this->db->where('quantity_range_from',$quantity_range_from);
	$this->db->where('quantity_range_to',$quantity_range_to);
	$this->db->where('qrange_id != ',$qrange_id);
	$query = $this->db->get('designnbuy_qranges');
	return $query->num_rows(); 	
    }
    
    /*
     *  check duplicate entry for quantity range on add.
     */
    public function getQuantityRangeByName($quantity_range_from,$quantity_range_to) {
	$this->db->where('quantity_range_from',$quantity_range_from);
	$this->db->where('quantity_range_to',$quantity_range_to);
	$query = $this->db->get('designnbuy_qranges');
	return $query->num_rows();
    }
    
    /*
     * Quantity Range table csv data import entries
     */
    function insert_qranges_csv($data) {
	$this->db->insert('designnbuy_qranges',$data);
    }
    
    /*
     * Quantity Range rows based on  value
     */
    function getQuantityRangeByvalue($quantity_range_from,$quantity_range_to) {
	$this->db->where('quantity_range_from',$quantity_range_from);
	$this->db->where('quantity_range_to',$quantity_range_to);
	$query = $this->db->get('designnbuy_qranges');
	return $query->row_array();
    }
    
}