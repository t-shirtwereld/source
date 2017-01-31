<?php
class Designnbuy_fonts_model extends CI_Model {

    /**
     * Constructor of the Fonts model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Fonts list with pagination
     */

    function getFontsList($searchparam = NULL, $limit = NULL, $start = NULL) {	
	$this->db->like('designnbuy_fonts.font_name', $searchparam);
	if($limit !== NULL && $start !== NULL){
	    $this->db->limit($limit,$start);
	}
	$this->db->order_by('designnbuy_fonts.position','ASC');
	$query = $this->db->get("designnbuy_fonts");
	return $query->result_array();
    }

    /**
     * 
     * @param Requested font_id $font_id
     * @return clipart single row result set
     */
    function getFontRow($font_id) {
	$query = $this->db->get_where('designnbuy_fonts', array('font_id' => $font_id));
	return $query->row_array();
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updateFontRow($param) {
	$data = array(
	    'font_name' => $param['font_name'],
	    'status' => $param['status']
	);

	if ($param['font_id'] != '') {
	    $this->db->where('font_id', $param['font_id']);
	    $this->db->update('designnbuy_fonts', $data);
	    return $param['font_id'];
	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_fonts');
	    $position = $position_query->row_array();
	    if(isset($position['position']) && $position['position'] != ''){		 
		 $data['position'] = $position['position'];
	    } else {
		 $data['position'] = 1;
	    }
	    $this->db->insert('designnbuy_fonts', $data);
	    $font_id = $this->db->insert_id();
	    return $font_id;
	}
	
    }
    /*
     * Update Fonts image 
     * @param Requested font_id $font_id,clipart_image $clipart_image_name
     */
     function updateFontImage($font_id,$font_file_name,$font_css_name) {
	 $data = array(
	     'font_file' => $font_file_name,
	     'font_css' => $font_css_name.'.css'
	     );
	 $this->db->where('font_id',$font_id);
	 $this->db->update('designnbuy_fonts', $data);
     }
     
     function updateFontJs($font_id,$font_js_name) {
	 $data = array(
	     'font_js' => $font_js_name
	     );
	 $this->db->where('font_id',$font_id);
	 $this->db->update('designnbuy_fonts', $data);
     }
    /**
     * 
     * @param Requested font_id $font_id
     */
    function deleteFontRow($font_id) {
	$row = $this->getFontRow($font_id);
	$file = TOOL_IMG_PATH.'/fonts/'.$row['font_file'];
	$cssfile = TOOL_IMG_PATH.'/fonts/'.$row['font_css'];
	if(!empty($row['font_file']) && file_exists($file)) {	    
	    unlink($file);
	}
	if(!empty($row['font_css']) && file_exists($cssfile)) {	    
	    unlink($cssfile);
	}
	$this->db->delete('designnbuy_fonts', array('font_id' => $font_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Fonts Count 
     */
    function record_count($searchparam = NULL) {
	$this->db->like('designnbuy_fonts.font_name', $searchparam);
	$this->db->group_by('designnbuy_fonts.font_id');
	$query = $this->db->get('designnbuy_fonts');
	return $query->num_rows(); 
    }   
    
    /*
     * Get font row based on font name
     */
    function getFontByName($font_name) {
	$query = $this->db->get_where('designnbuy_fonts',array('font_name' => $font_name));
	return $query->row_array();
    }
    /*
     * Font table csv data import entries
     */
    function insert_font_csv($data) {
	$this->db->insert('designnbuy_fonts',$data);
    }
    /*
     *  Update position of rows
     */
    function updateSortableRow($data) {
	foreach($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('font_id', $key);
	    $this->db->update('designnbuy_fonts', $result);
	}
    }
           
}