<?php

class Designnbuy_printing_methods_model extends CI_Model {

    /**
     * Constructor of the Printingmethods model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    /*
     * get Printingmethods list with pagination
     */

    function getPrintingmethodsList($searchparam = NULL, $pricing_logic = NULL, $printable_color_type = NULL, $limit = NULL, $start = NULL) {
	$this->db->where('designnbuy_printing_methods_lang.language_id', '1');
	$this->db->join('designnbuy_printing_methods_lang', 'designnbuy_printing_methods.printing_method_id = designnbuy_printing_methods_lang.printing_method_id');
	if (isset($searchparam) && $searchparam != '') {
	    $this->db->like('designnbuy_printing_methods_lang.name', $searchparam);
	}
	if (isset($pricing_logic) && $pricing_logic != '') {
	    $this->db->where('designnbuy_printing_methods.pricing_logic', $pricing_logic);
	}
	if (isset($printable_color_type) && $printable_color_type != '') {
	    $this->db->where('designnbuy_printing_methods.printable_color_type', $printable_color_type);
	}

	if ($limit !== NULL && $start !== NULL) {
	    $this->db->limit($limit, $start);
	}
	$this->db->group_by('designnbuy_printing_methods.printing_method_id');
	$this->db->order_by('designnbuy_printing_methods.position', 'ASC');
	$query = $this->db->get("designnbuy_printing_methods");
	return $query->result_array();
    }

    /*
     * get Printable color category names for custom printable colro type 
     */

    public function getPrintableColorCategories() {
	$query = $this->db->get('designnbuy_printablecolor_categories');
	return $query->result_array();
    }

    /*
     *  get total Quantity Range
     */

    public function getQuantityRange() {
	$query = $this->db->get('designnbuy_qranges');
	return $query->result_array();
    }

    /*
     * get total Color Counter
     */

    public function getColorCounter() {
	$query = $this->db->get('designnbuy_color_counters');
	return $query->result_array();
    }

    /*
     * get total Square Area
     */

    public function getSquareArea() {
	$query = $this->db->get('designnbuy_sqarea');
	return $query->result_array();
    }

    /** Get clipart names for single category
     * @param Requested clipart_id $clipart_id
     * @return clipart array result set
     */
    function getPrintingmethodNamesList($printing_method_id) {
	$names = array();
	$query = $this->db->get_where('designnbuy_printing_methods_lang', array('printing_method_id' => $printing_method_id));
	foreach ($query->result_array() as $result) {
	    $names[$result['language_id']] = array(
		'name' => $result['name'],
		'alert_message' => $result['alert_message']
	    );
	}
	return $names;
    }

    /**
     * 
     * @param Requested printing_method_id $printing_method_id
     * @return printing method single row result set
     */
    function getPrintingmethodRow($printing_method_id) {
	$query = $this->db->get_where('designnbuy_printing_methods', array('printing_method_id' => $printing_method_id));
	$result = $query->row_array();

	$printing_method_info = array();
	$printing_method_info = array(
	    'printing_method_id' => $result['printing_method_id'],
	    'pricing_logic' => $result['pricing_logic'],
	    'printable_color_type' => $result['printable_color_type'],
	    'min_qty' => $result['min_qty'],
	    'max_qty' => $result['max_qty'],
	    'name_price' => $result['name_price'],
	    'number_price' => $result['number_price'],
	    'is_image_upload' => $result['is_image_upload'],
	    'image_price' => $result['image_price'],
	    'artwork_setup_price_type' => $result['artwork_setup_price_type'],
	    'artwork_setup_price' => $result['artwork_setup_price'],
	    'is_alert' => $result['is_alert'],
	    'status' => $result['status']
	);

	$this->db->join('designnbuy_printablecolor_categories', 'designnbuy_printablecolor_categories.printablecolor_category_id = designnbuy_printing_methods_printablecolor_categories.printablecolor_category_id');
	$pcquery = $this->db->get_where('designnbuy_printing_methods_printablecolor_categories', array('printing_method_id' => $printing_method_id));
	$printablecolors = $pcquery->result_array();
	$printing_method_info['printablecolors'] = array();

	if (!empty($printablecolors)) {
	    foreach ($printablecolors as $pc) {
		$printing_method_info['printablecolors'][] = array(
		    'printablecolor_category_id' => $pc['printablecolor_category_id'],
		    'category_name' => $pc['category_name']
		);
	    }
	}

	$qcquery = $this->db->get_where('designnbuy_printing_methods_qcprice', array('printing_method_id' => $printing_method_id));
	$qcprice = $qcquery->result_array();
	$printing_method_info['qcPrice'] = array();
	if (!empty($qcprice)) {
	    foreach ($qcprice as $qc) {
		$printing_method_info['qcPrice'][] = array(
		    'quantity_range_id' => $qc['quantity_range_id'],
		    'color_counter_id' => $qc['color_counter_id'],
		    'first_side_price' => $qc['first_side_price'],
		    'second_side_price' => $qc['second_side_price'],
		    'third_side_price' => $qc['third_side_price'],
		    'fourth_side_price' => $qc['fourth_side_price'],
		    'fifth_side_price' => $qc['fifth_side_price'],
		    'sixth_side_price' => $qc['sixth_side_price'],
		    'seventh_side_price' => $qc['seventh_side_price'],
		    'eighth_side_price' => $qc['eighth_side_price']
		);
	    }
	}


	$qaquery = $this->db->get_where('designnbuy_printing_methods_qaprice', array('printing_method_id' => $printing_method_id));
	$qaprice = $qaquery->result_array();
	$printing_method_info['qaPrice'] = array();
	if (!empty($qaprice)) {
	    foreach ($qaprice as $qa) {
		$printing_method_info['qaPrice'][] = array(
		    'quantity_range_id' => $qa['quantity_range_id'],
		    'sqarea_id' => $qa['sqarea_id'],
		    'first_side_price' => $qa['first_side_price'],
		    'second_side_price' => $qa['second_side_price'],
		    'third_side_price' => $qa['third_side_price'],
		    'fourth_side_price' => $qa['fourth_side_price'],
		    'fifth_side_price' => $qa['fifth_side_price'],
		    'sixth_side_price' => $qa['sixth_side_price'],
		    'seventh_side_price' => $qa['seventh_side_price'],
		    'eighth_side_price' => $qa['eighth_side_price']
		);
	    }
	}

	$fpquery = $this->db->get_where('designnbuy_printing_methods_fixedprice', array('printing_method_id' => $printing_method_id));
	$fixedprice = $fpquery->result_array();
	$printing_method_info['fixedPrice'] = array();
	if (!empty($fixedprice)) {
	    foreach ($fixedprice as $fp) {
		$printing_method_info['fixedPrice'][] = array(
		    'quantity_range_id' => $fp['quantity_range_id'],
		    'first_side_price' => $fp['first_side_price'],
		    'second_side_price' => $fp['second_side_price'],
		    'third_side_price' => $fp['third_side_price'],
		    'fourth_side_price' => $fp['fourth_side_price'],
		    'fifth_side_price' => $fp['fifth_side_price'],
		    'sixth_side_price' => $fp['sixth_side_price'],
		    'seventh_side_price' => $fp['seventh_side_price'],
		    'eighth_side_price' => $fp['eighth_side_price']
		);
	    }
	}

	/* $pmquery = $this->db->get_where('printcommerce_printing_methods_product', array('printing_method_id' => $printing_method_id));
	  $products = $pmquery->result_array();
	  $printing_method_info['pm_product'] = array();
	  if (!empty($products)) {
	  foreach ($products as $pm) {
	  $printing_method_info['pm_product'][] = array(
	  'product_id' => $pm['product_id']
	  );
	  }
	  } */
	return $printing_method_info;
    }

    /**
     * Insert/update records
     * @param type $param
     */
    function updatePrintingmethodRow($param) {
	$data = array(
	    'pricing_logic' => $param['pricing_logic'],
	    'printable_color_type' => $param['printable_color_type'],
	    'min_qty' => $param['min_qty'],
	    'max_qty' => $param['max_qty'],
	    'name_price' => $param['name_price'],
	    'number_price' => $param['number_price'],
	    'is_image_upload' => $param['is_image_upload'],
	    'image_price' => $param['image_price'],
	    'artwork_setup_price_type' => $param['artwork_setup_price_type'],
	    'artwork_setup_price' => $param['artwork_setup_price'],
	    'is_alert' => $param['is_alert'],
	    'status' => $param['status']
	);
	if($param['image_price'] == ''){
		$data['image_price'] = 0.00;
	}
	if($param['name_price'] == ''){
		$data['name_price'] = 0.00;
	}
	if($param['number_price'] == ''){
		$data['number_price'] = 0.00;
	}
	if ($param['printing_method_id'] != '') {
	    $this->db->where('printing_method_id', $param['printing_method_id']);
	    $this->db->update('designnbuy_printing_methods', $data);

	    $this->db->delete('designnbuy_printing_methods_lang', array('printing_method_id' => $param['printing_method_id']));
	    foreach ($param['printingmethod_description'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'printing_method_id' => $param['printing_method_id'],
		    'language_id' => $language_id,
		    'name' => $value['name'],
		    'alert_message' => $value['alert_message']
		);
		$this->db->insert('designnbuy_printing_methods_lang', $langdata);
	    }

	    $this->db->delete('designnbuy_printing_methods_printablecolor_categories', array('printing_method_id' => $param['printing_method_id']));
	    if (!empty($param['printablecolorCategories'])) {
		foreach ($param['printablecolorCategories'] as $pc) {
		    $colorcatdata = array();
		    $colorcatdata = array(
			'printing_method_id' => $param['printing_method_id'],
			'printablecolor_category_id' => $pc
		    );
		    $this->db->insert('designnbuy_printing_methods_printablecolor_categories', $colorcatdata);
		}
	    }

	    if (!empty($param['qcPrice'])) {
		$this->db->delete('designnbuy_printing_methods_qcprice', array('printing_method_id' => $param['printing_method_id']));

		foreach ($param['qcPrice'] as $qc) {
		    $qcpricedata = array();
		    $qcpricedata = array(
			'printing_method_id' => $param['printing_method_id'],
			'quantity_range_id' => $qc['quantity_range_id'],
			'color_counter_id' => $qc['color_counter_id'],
			'first_side_price' => $qc['first_side_price'],
			'second_side_price' => $qc['second_side_price'],
			'third_side_price' => $qc['third_side_price'],
			'fourth_side_price' => $qc['fourth_side_price'],
			'fifth_side_price' => $qc['fifth_side_price'],
			'sixth_side_price' => $qc['sixth_side_price'],
			'seventh_side_price' => $qc['seventh_side_price'],
			'eighth_side_price' => $qc['eighth_side_price']
		    );
		    $this->db->insert('designnbuy_printing_methods_qcprice', $qcpricedata);
		}
	    }

	    if (!empty($param['qaPrice'])) {
		$this->db->delete('designnbuy_printing_methods_qaprice', array('printing_method_id' => $param['printing_method_id']));

		foreach ($param['qaPrice'] as $qa) {
		    $qapricedata = array();
		    $qapricedata = array(
			'printing_method_id' => $param['printing_method_id'],
			'quantity_range_id' => $qa['quantity_range_id'],
			'sqarea_id' => $qa['sqarea_id'],
			'first_side_price' => $qa['first_side_price'],
			'second_side_price' => $qa['second_side_price'],
			'third_side_price' => $qa['third_side_price'],
			'fourth_side_price' => $qa['fourth_side_price'],
			'fifth_side_price' => $qa['fifth_side_price'],
			'sixth_side_price' => $qa['sixth_side_price'],
			'seventh_side_price' => $qa['seventh_side_price'],
			'eighth_side_price' => $qa['eighth_side_price']
		    );
		    $this->db->insert('designnbuy_printing_methods_qaprice', $qapricedata);
		}
	    }

	    if (!empty($param['fixedPrice'])) {

		$this->db->delete('designnbuy_printing_methods_fixedprice', array('printing_method_id' => $param['printing_method_id']));
		foreach ($param['fixedPrice'] as $fp) {
		    $fixedpricedata = array();
		    $fixedpricedata = array(
			'printing_method_id' => $param['printing_method_id'],
			'quantity_range_id' => $fp['quantity_range_id'],
			'first_side_price' => $fp['first_side_price'],
			'second_side_price' => $fp['second_side_price'],
			'third_side_price' => $fp['third_side_price'],
			'fourth_side_price' => $fp['fourth_side_price'],
			'fifth_side_price' => $fp['fifth_side_price'],
			'sixth_side_price' => $fp['sixth_side_price'],
			'seventh_side_price' => $fp['seventh_side_price'],
			'eighth_side_price' => $fp['eighth_side_price']
		    );
		    $this->db->insert('designnbuy_printing_methods_fixedprice', $fixedpricedata);
		}
	    }

	    /* $this->db->delete('printcommerce_printing_methods_product', array('printing_method_id' => $param['printing_method_id']));
	      foreach ($param['pm_product'] as $product_id) {
	      $productdata = array();
	      $productdata = array(
	      'printing_method_id' => $param['printing_method_id'],
	      'product_id' => $fp['product_id']
	      );
	      $this->db->insert('printcommerce_printing_methods_product', $productdata);
	      } */
	} else {
	    $this->db->select('MAX(position) + 1 AS position');
	    $position_query = $this->db->get('designnbuy_printing_methods');
	    $position = $position_query->row_array();
	    if (isset($position['position']) && $position['position'] != '') {
		$data['position'] = $position['position'];
	    } else {
		$data['position'] = 1;
	    }
		if($data['image_price'] == ''){
			$data['image_price'] = 0.00;
		}
		if($data['name_price'] == ''){
			$data['name_price'] = 0.00;
		}
		if($data['number_price'] == ''){
			$data['number_price'] = 0.00;
		}
	    $this->db->insert('designnbuy_printing_methods', $data);
	    $printing_method_id = $this->db->insert_id();
	    foreach ($param['printingmethod_description'] as $language_id => $value) {
		$langdata = array();
		$langdata = array(
		    'printing_method_id' => $printing_method_id,
		    'language_id' => $language_id,
		    'name' => $value['name'],
		    'alert_message' => $value['alert_message']
		);
		$this->db->insert('designnbuy_printing_methods_lang', $langdata);
	    }
	    if (!empty($param['printablecolorCategories'])) {
		foreach ($param['printablecolorCategories'] as $pc) {
		    $colorcatdata = array();
		    $colorcatdata = array(
			'printing_method_id' => $printing_method_id,
			'printablecolor_category_id' => $pc
		    );
		    $this->db->insert('designnbuy_printing_methods_printablecolor_categories', $colorcatdata);
		}
	    }
	}
    }

    /**
     * 
     * @param Requested printing_method_id $printing_method_id
     */
    function deletePrintingmethodRow($printing_method_id) {
	$this->db->delete('designnbuy_printing_methods', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_lang', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_fixedprice', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_qaprice', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_qcprice', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_product', array('printing_method_id' => $printing_method_id));
	$this->db->delete('designnbuy_printing_methods_printablecolor_categories', array('printing_method_id' => $printing_method_id));
	return $this->db->affected_rows();
    }

    /**
     *  Total Printingmethods Count 
     */
    function record_count($searchparam = NULL, $pricing_logic = NULL, $printable_color_type = NULL) {
	$this->db->where('designnbuy_printing_methods_lang.language_id', '1');
	$this->db->join('designnbuy_printing_methods_lang', 'designnbuy_printing_methods.printing_method_id = designnbuy_printing_methods_lang.printing_method_id');
	if (isset($searchparam) && $searchparam != '') {
	    $this->db->like('designnbuy_printing_methods_lang.name', $searchparam);
	}
	if (isset($pricing_logic) && $pricing_logic != '') {
	    $this->db->where('designnbuy_printing_methods.pricing_logic', $pricing_logic);
	}
	if (isset($printable_color_type) && $printable_color_type != '') {
	    $this->db->where('designnbuy_printing_methods.printable_color_type', $printable_color_type);
	}
	$this->db->group_by('designnbuy_printing_methods.printing_method_id');
	$query = $this->db->get("designnbuy_printing_methods");
	return $query->num_rows();
    }

    /*
     *  Update position of rows
     */

    function updateSortableRow($data) {
	foreach ($data as $key => $value) {
	    $result = array(
		'position' => $value
	    );
	    $this->db->where('printing_method_id', $key);
	    $this->db->update('designnbuy_printing_methods', $result);
	}
    }

}