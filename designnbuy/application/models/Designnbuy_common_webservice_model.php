<?php

class Designnbuy_common_webservice_model extends CI_Model {

    /**
     * Constructor of the Clipart category model
     */
    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    function getGeneralConfigData($siteBaseUrl = '') {
	$this->db->select('base_unit,output_format,pdf_output_type,image_resolution,watermark_status,watermark_logo');
	$result = $this->db->get('designnbuy_general_settings')->row_array();
	$generalconfig = array();
	$generalconfig = array(
	    'base_unit' => $result['base_unit'],
	    'output_format' => $result['output_format'],
	    'pdf_output_type' => $result['pdf_output_type'],
	    'image_resolution' => $result['image_resolution'],
	    'watermark_status' => $result['watermark_status'],
	    'watermark_logo' => $result['watermark_logo'],
	    'watermark_logo_path' => $siteBaseUrl.'designnbuy/assets/images/logo/' . $result['watermark_logo']
	);
	return $generalconfig;
    }

    function getSocialMediaData() {
	$this->db->select('facebook_app_id,flicker_api_key,instagram_client_id');
	$result = $this->db->get('designnbuy_general_settings')->row_array();
	$socialmedia = array();
	$socialmedia = array(
	    'facebook_app_id' => $result['facebook_app_id'],
	    'flicker_api_key' => $result['flicker_api_key'],
	    'instagram_client_id' => $result['instagram_client_id']
	);
	return $socialmedia;
    }

    function getGloablSideLabels($language_id) {
	$query = $this->db->get_where('designnbuy_global_sidelabels', array('general_settings_id' => '1', 'language_id' => $language_id));
	$result = $query->row_array();
	$sideLables = array();
	$sideLables = array(
	    '0' => $result['side_1_label'],
	    '1' => $result['side_2_label'],
	    '2' => $result['side_3_label'],
	    '3' => $result['side_4_label'],
	    '4' => $result['side_5_label'],
	    '5' => $result['side_6_label'],
	    '6' => $result['side_7_label'],
	    '7' => $result['side_8_label']
	);
	return $sideLables;
    }

    function getConfigureFeatureData() {
	$this->db->select('standard_text,cliparts,image_upload,social_media_sharing,qr_code,text_effects,free_hand_shapes,name_number,social_media_image_upload,advance_image_upload,preload_template,show_clipart,show_designidea');
	$result = $this->db->get('designnbuy_configure_features')->row_array();
	$config_feature = array();
	$config_feature = array(
	    '0' => $result['standard_text'],
	    '1' => $result['cliparts'],
	    '2' => $result['image_upload'],
	    '3' => $result['social_media_sharing'],
	    '4' => $result['qr_code'],
	    '5' => $result['text_effects'],
	    '6' => $result['social_media_image_upload'],
	    '7' => $result['advance_image_upload'],
	    '8' => $result['free_hand_shapes'],
	    '9' => $result['preload_template'],
	    '10' => $result['name_number'],
	    '11' => $result['show_clipart'],
	    '12' => $result['show_designidea']
	);
	return $config_feature;
    }

    function getLanguageList() {
	$this->db->where('status', '1');
	$result = $this->db->get('designnbuy_language')->result_array();
	$languages = array();
	foreach ($result as $res) {
	    $languages[] = array(
		'language_id' => $res['language_id'],
		'name' => $res['name'],
		'iso_code' => $res['iso_code'],
		'language_code' => $res['language_code'],
		'image' => $res['image'],
		'image_path' => get_base_url('designnbuy/assets/flags/logo/' . $res['image']),
		'is_rtl' => $res['is_rtl'],
		'short_order' => $res['short_order'],
		'connector' => $res['connector'],
		'status' => $res['status']
	    );
	}
	return $languages;
    }

    function getClipartCategoryList($language_id = 1) {
	//$this->db->where('designnbuy_clipart_categories.parent_category_id', '0');
	$this->db->where('designnbuy_clipart_categories_lang.language_id', $language_id);
	$this->db->where('designnbuy_clipart_categories.status', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$this->db->order_by('designnbuy_clipart_categories.position', 'asc'); 
	$result = $this->db->get('designnbuy_clipart_categories')->result_array();
	$categories = array();
	
	foreach ($result as $res) {
	    $this->db->where('clipart_category_id', $res['clipart_category_id']);
	    $this->db->where('status', '1');
	    $this->db->where('designnbuy_cliparts.is_clipart_design', '0');
	    $query = $this->db->get('designnbuy_cliparts');
	    if ($query->num_rows() > 0) {
		$categories[$res['clipart_category_id']] = array(
		    'id' => $res['clipart_category_id'],
		    //'parent_category_id' => $res['parent_category_id'],
		    'name' => $res['name'],
		    'position' => $res['position'],
		    'clipart_count' => $query->num_rows(),
		    //'status' => $res['status'],
		    //'children' => $this->getClipartSubCategoryList($res['clipart_category_id'], $language_id)
		    'children' => array()
		);
	    }
	}
	return array_values($categories);
    }
    
    function getDesignideaCategoryList($language_id = 1) {
	//$this->db->where('designnbuy_clipart_categories.parent_category_id', '0');
	$this->db->where('designnbuy_clipart_categories_lang.language_id', $language_id);
	$this->db->where('designnbuy_clipart_categories.status', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$this->db->order_by('designnbuy_clipart_categories.position', 'asc');
	$result = $this->db->get('designnbuy_clipart_categories')->result_array();
	$categories = array();
	foreach ($result as $res) {
	    $this->db->where('clipart_category_id', $res['clipart_category_id']);
	    $this->db->where('status', '1');
	    $this->db->where('designnbuy_cliparts.is_clipart_design', '1');
	    $query = $this->db->get('designnbuy_cliparts');
	    if ($query->num_rows() > 0) {
		$categories[$res['clipart_category_id']] = array(
		    'id' => $res['clipart_category_id'],
		    //'parent_category_id' => $res['parent_category_id'],
		    'name' => $res['name'],
		    'position' => $res['position'],
		    'clipart_count' => $query->num_rows(),
		    //'status' => $res['status'],
		    //'children' => $this->getClipartSubCategoryList($res['clipart_category_id'], $language_id)
		    'children' => array()
		);
	    }
	}
	return array_values($categories);
    } 

   /* function getClipartSubCategoryList($parent_category_id = 0, $language_id = 1) {
	$this->db->where('designnbuy_clipart_categories.parent_category_id', $parent_category_id);
	$this->db->where('designnbuy_clipart_categories_lang.language_id', $language_id);
	$this->db->where('designnbuy_clipart_categories.status', '1');
	$this->db->join('designnbuy_clipart_categories_lang', 'designnbuy_clipart_categories.clipart_category_id = designnbuy_clipart_categories_lang.clipart_category_id');
	$result = $this->db->get('designnbuy_clipart_categories')->result_array();
	$subcategories = array();
	foreach ($result as $res) {
	       $this->db->where('clipart_category_id', $res['clipart_category_id']);
	      $this->db->where('status', '1');
	      $query = $this->db->get('designnbuy_cliparts');
	      if ($query->num_rows() > 0) { 
	    $subcategories[$res['clipart_category_id']] = array(
		'id' => $res['clipart_category_id'],
		//'parent_category_id' => $res['parent_category_id'],
		'name' => $res['name'],
		'position' => $res['position'],
		'children' => $this->getClipartSubCategoryList($res['clipart_category_id'], $language_id)
		    //'status' => $res['status']
	    );
	    //  }
	}
	return $subcategories;
    } */

    function getClipartList($clipart_category_id, $language_id = 1, $siteBaseUrl) {
	//$this->db->where_in('designnbuy_cliparts.clipart_category_id', explode(",", $clipart_category_id));
	$this->db->where('designnbuy_cliparts.clipart_category_id', $clipart_category_id);
	$this->db->where('designnbuy_cliparts_lang.language_id', $language_id);
	$this->db->where('designnbuy_cliparts.is_clipart_design', '0');
	$this->db->where('designnbuy_cliparts.status', '1');
	$this->db->join('designnbuy_cliparts_lang', 'designnbuy_cliparts.clipart_id = designnbuy_cliparts_lang.clipart_id');
	$this->db->order_by('designnbuy_cliparts.position', 'asc');
	$result = $this->db->get('designnbuy_cliparts')->result_array();
	$cliparts = array();
	$i = 0;
	foreach ($result as $res) {
	    $cliparts[$i] = array(
		'clipart_id' => $res['clipart_id'],
		'clipart_category_id' => $res['clipart_category_id'],
		'clipart_image' => $res['clipart_image'],
		'clipart_svg' => $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_image'],
		'clipart_price' => $res['clipart_price'],
		'name' => $res['name'],
		'position' => $res['position'],
		'status' => $res['status'],
		'type' => 'clipart'
	    );
	    if (isset($res['clipart_png']) && $res['clipart_png'] != '') {
		$cliparts[$i]['image_path'] = $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_png'];
	    } else {
		$cliparts[$i]['image_path'] = $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_image'];
	    }
	    $i++;
	}

	return array_values($cliparts);
    }
    
    function getDesignideaList($clipart_category_id, $language_id = 1, $siteBaseUrl) {
	//$this->db->where_in('designnbuy_cliparts.clipart_category_id', explode(",", $clipart_category_id));
	$this->db->where('designnbuy_cliparts.clipart_category_id', $clipart_category_id);
	$this->db->where('designnbuy_cliparts_lang.language_id', $language_id);
	$this->db->where('designnbuy_cliparts.is_clipart_design', '1');
	$this->db->where('designnbuy_cliparts.status', '1');
	$this->db->join('designnbuy_cliparts_lang', 'designnbuy_cliparts.clipart_id = designnbuy_cliparts_lang.clipart_id');
	$this->db->order_by('designnbuy_cliparts.position', 'asc');
	$result = $this->db->get('designnbuy_cliparts')->result_array();
	$designideas = array();
	$i = 0;
	foreach ($result as $res) {
	    $designideas[$i] = array(
		//'clipart_id' => $res['clipart_id'],
		'clipart_category_id' => $res['clipart_category_id'],
		'clipart_image' => $res['clipart_image'],
		'clipart_svg' => $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_image'],
		'clipart_price' => $res['clipart_price'],
		'name' => $res['name'],
		'position' => $res['position'],
		'status' => $res['status'],
		'type' => 'designidea'
	    );
	    if (isset($res['clipart_png']) && $res['clipart_png'] != '') {
		$designideas[$i]['image_path'] = $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_png'];
	    } else {
		$designideas[$i]['image_path'] = $siteBaseUrl . 'designnbuy/assets/images/cliparts/' . $res['clipart_image'];
	    }
	    $i++;
	}

	return array_values($designideas);
    }
    
    function getClipartPrice($clipart_ids) {	
	$clipart_price = 0;
	$cliparts = explode(',',$clipart_ids);
	foreach($cliparts as $clipart_id) {
	    $this->db->select('clipart_price');
	    $row = $this->db->get_where('designnbuy_cliparts',array('clipart_id' => $clipart_id))->row_array();
	    $clipart_price = $clipart_price + $row['clipart_price'];
	}
	return $clipart_price;
    }

    function getFontList() {
	$this->db->where('designnbuy_fonts.status', '1');
	$result = $this->db->get('designnbuy_fonts')->result_array();
	$fonts = array();
	foreach ($result as $res) {
	    $fonts[$res['font_name']] = array(
		'font_id' => $res['font_id'],
		'cssFile' => $res['font_css'],
		'jsFile' => $res['font_js'],
		'loadType' => 'custom',
		'loaded' => 'false'
	    );
	}

	return $fonts;
    }

    function getColorCategoryList() {
	$this->db->where('designnbuy_printablecolor_categories.status', '1');
	$result = $this->db->get('designnbuy_printablecolor_categories')->result_array();
	$colorcategories = array();
	foreach ($result as $res) {
	    $colorcategories[] = array(
		'printablecolor_category_id' => $res['printablecolor_category_id'],
		'category_name' => $res['category_name'],
		'position' => $res['position'],
		'status' => $res['status']
	    );
	}
	return $colorcategories;
    }

    function getPrintableColorList($color_category_id, $language_id = 1) {
	$this->db->where('designnbuy_printablecolors.printablecolor_category_id', $color_category_id);
	$this->db->where('designnbuy_printablecolors_lang.language_id', $language_id);
	$this->db->where('designnbuy_printablecolors.status', '1');
	$this->db->join('designnbuy_printablecolors_lang', 'designnbuy_printablecolors.printablecolor_id = designnbuy_printablecolors_lang.printablecolor_id');
	$result = $this->db->get('designnbuy_printablecolors')->result_array();
	$printablecolors = array();
	foreach ($result as $res) {
	    $printablecolors[] = array(
		'printablecolor_id' => $res['printablecolor_id'],
		'printablecolor_category_id' => $res['printablecolor_category_id'],
		'name' => $res['name'],
		'color_code' => $res['color_code'],
		'c' => $res['c'],
		'm' => $res['m'],
		'y' => $res['y'],
		'k' => $res['k'],
		'position' => $res['position'],
		'status' => $res['status']
	    );
	}
	return $printablecolors;
    }

    function getColorCountersList() {
	$result = $this->db->get('designnbuy_color_counters')->result_array();
	$colorcounters = array();
	foreach ($result as $res) {
	    $colorcounters[] = array(
		'color_counter_id' => $res['color_counter_id'],
		'color_counter' => $res['color_counter']
	    );
	}
	return $colorcounters;
    }

    function getArtworkSizesList() {
	$result = $this->db->get('designnbuy_sqarea')->result_array();
	$colorcounters = array();
	foreach ($result as $res) {
	    $colorcounters[] = array(
		'sqarea_id' => $res['sqarea_id'],
		'square_area' => $res['square_area']
	    );
	}
	return $colorcounters;
    }

    function getQuantityRangesList() {
	$result = $this->db->get('designnbuy_qranges')->result_array();
	$colorcounters = array();
	foreach ($result as $res) {
	    $colorcounters[] = array(
		'qrange_id' => $res['qrange_id'],
		'quantity_range_from' => $res['quantity_range_from'],
		'quantity_range_to' => $res['quantity_range_to']
	    );
	}
	return $colorcounters;
    }

    function getPrintingMethodList($product_id, $language_id = '1') {
	$ids = $this->db->get_where('designnbuy_printing_methods_product', array('product_id' => $product_id))->result_array();
	$printingmethods = array();
	if (!empty($ids)) {
	    foreach ($ids as $id) {
		$this->db->where('designnbuy_printing_methods.printing_method_id', $id['printing_method_id']);
		$this->db->where('designnbuy_printing_methods.status', '1');
		$this->db->where('designnbuy_printing_methods_lang.language_id', $language_id);
		$this->db->join('designnbuy_printing_methods_lang', 'designnbuy_printing_methods.printing_method_id = designnbuy_printing_methods_lang.printing_method_id');
		$result = $this->db->get('designnbuy_printing_methods')->result_array();
		foreach ($result as $res) {
		    $printingmethods[] = array(
			'printing_method_id' => $res['printing_method_id'],
			'name' => $res['name'],
			'pricing_logic' => $res['pricing_logic'],
			'printable_color_type' => $res['printable_color_type'],
			'min_qty' => $res['min_qty'],
			'max_qty' => $res['max_qty'],
			'name_price' => $res['name_price'],
			'number_price' => $res['number_price'],
			'is_image_upload' => $res['is_image_upload'],
			'image_price' => $res['image_price'],
			'artwork_setup_price_type' => $res['artwork_setup_price_type'],
			'artwork_setup_price' => $res['artwork_setup_price'],
			'is_alert' => $res['is_alert'],
			'alert_message' => $res['alert_message'],
			'status' => $res['status']
		    );
		}

		$this->db->join('designnbuy_printablecolor_categories', 'designnbuy_printablecolor_categories.printablecolor_category_id = designnbuy_printing_methods_printablecolor_categories.printablecolor_category_id');
		$pcquery = $this->db->get_where('designnbuy_printing_methods_printablecolor_categories', array('printing_method_id' => $id['printing_method_id']));
		$printablecolors = $pcquery->result_array();
		$printing_method_info['printablecolors'] = array();
		if (!empty($printablecolors)) {
		    foreach ($printablecolors as $pc) {
			$printingmethods['printablecolors'][] = array(
			    'printablecolor_category_id' => $pc['printablecolor_category_id'],
			    'category_name' => $pc['category_name']
			);
		    }
		}
	    }
	}
	return $printingmethods;
    }

    function getLanguageIdBasedOnConnector($connector) {
      
	$this->db->select('language_id');
	$query = $this->db->get_where('designnbuy_language', array('connector' => $connector));
	$result = $query->row_array();
	return $result['language_id'];
    }
    
    function getLanguageIsoCode($language_id) {
	$this->db->select('iso_code');
	$query = $this->db->get_where('designnbuy_language', array('language_id' => $language_id));
	$result = $query->row_array();
	return $result['iso_code'];
    }

    function getOptionAttr($optionattr) {
	$query = $this->db->get_where('designnbuy_product_advance_configuration', array('name' => $optionattr));
	$result = $query->row_array();
	return json_decode($result['value']);
    }

   //ADDED BY SOMIN
    function getProduct3dData($product_id, $language_id) {
		$res = $this->db->get_where('designnbuy_threed', array('product_id' => $product_id))->row_array();
		return $res;
   }

   function getProduct3dDataconfig($product_id, $language_id) { 
   	$res = $this->db->get_where('designnbuy_threed_configarea', array('product_id' => $product_id))->row_array();
    return $res;
   }
   //ADDED BY SOMIN

    function getProductConfigurationData($product_id, $language_id) {
   
	$sidelabels = array();
	$res = $this->db->get_where('designnbuy_product_settings', array('product_id' => $product_id))->row_array();

	$result = array();
	if ($res['global_side_label'] == '0') {
	    $this->db->where('language_id', $language_id);
	    $this->db->where('product_id', $product_id);
	    $sidelabels = $this->db->get('designnbuy_product_sidelabels')->row_array();
	} else {
	
	    $sidelabels = $this->db->get_where('designnbuy_global_sidelabels', array('language_id' => $language_id))->row_array();
	}
	$this->db->where('designnbuy_product_settings.product_id', $product_id);
	$this->db->join('designnbuy_product_tool_images', 'designnbuy_product_tool_images.product_id = designnbuy_product_settings.product_id');
	$this->db->join('designnbuy_product_configarea', 'designnbuy_product_configarea.product_id = designnbuy_product_settings.product_id');
	$query = $this->db->get('designnbuy_product_settings');
	$result = $query->row_array();

	if($res['base_unit'] != '') {
	    $result['base_unit'] = $res['base_unit'];
	} else {
	    $gns = $this->db->get_where('designnbuy_general_settings')->row_array();
	    $result['base_unit'] = $gns['base_unit'];
	}
	$result = array_merge($result, (array)$sidelabels);

	return $result;
    }

    function getPrintingMethodsFromProductId($product_id, $language_id = '1') {
	$this->db->where('product_id', $product_id);
	$query = $this->db->get('designnbuy_printing_methods_product');
	$pms = $query->result_array();

	$printingMethods = array();
	if (!empty($pms)) {
	    $i = 0;
	    foreach ($pms as $pm) {
		$this->db->where('designnbuy_printing_methods.printing_method_id', $pm['printing_method_id']);
		$this->db->where('designnbuy_printing_methods.status', '1');
		$this->db->where('designnbuy_printing_methods_lang.language_id', $language_id);
		$this->db->join('designnbuy_printing_methods_lang', 'designnbuy_printing_methods.printing_method_id = designnbuy_printing_methods_lang.printing_method_id');
		$this->db->group_by('designnbuy_printing_methods.printing_method_id');
		$this->db->order_by('designnbuy_printing_methods.position', 'ASC');
		$query1 = $this->db->get('designnbuy_printing_methods');
		$result = $query1->row_array();

		if (!empty($result)) {
		    $printingMethods[$i]['name'] = $result['name'];
		    $printingMethods[$i]['printingMethodId'] = $result['printing_method_id'];
		    $printingMethods[$i]['pricingLogic'] = $result['pricing_logic'];
		    $printingMethods[$i]['printableColorType'] = $result['printable_color_type'];
		    $printingMethods[$i]['artworkSetupPriceType'] = $result['artwork_setup_price_type'];
		    if (isset($result) && !empty($result)) {
			if ($result['printable_color_type'] == '2') {

			    $colorcategoryquery = $this->db->get_where('designnbuy_printing_methods_printablecolor_categories', array('printing_method_id' => $pm['printing_method_id']));
			    $colorcatresult = $colorcategoryquery->result_array();
			    $printcolors = array();
			    foreach ($colorcatresult as $cat) {
				$this->db->select('designnbuy_printablecolors.printablecolor_id,designnbuy_printablecolors.color_code,designnbuy_printablecolors_lang.name');
				$this->db->where('designnbuy_printablecolors_lang.language_id', '1');
				$this->db->where('designnbuy_printablecolor_categories.printablecolor_category_id', $cat['printablecolor_category_id']);
				$this->db->where('designnbuy_printablecolors.status', '1');
				$this->db->where('designnbuy_printablecolor_categories.status', '1');
				$this->db->join('designnbuy_printablecolors', 'designnbuy_printablecolors.printablecolor_category_id = designnbuy_printablecolor_categories.printablecolor_category_id');
				$this->db->join('designnbuy_printablecolors_lang', 'designnbuy_printablecolors.printablecolor_id = designnbuy_printablecolors_lang.printablecolor_id');
				$colorquery = $this->db->get('designnbuy_printablecolor_categories');
				$printcolors_merge = $colorquery->result_array();
				$printcolors = array_merge($printcolors, $printcolors_merge);
			    }
			    $printableColors = array();
			    $j = 0;
			    foreach ($printcolors as $pc) {
				$printableColors[$j]['id'] = $pc['printablecolor_id'];
				$printableColors[$j]['name'] = $pc['name'];
				$printableColors[$j]['colorCode'] = strtoupper(str_replace('#', '', $pc['color_code']));
				$j++;
			    }
			    $printingMethods[$i]['printableColors'] = $printableColors;
			}
		    }
		    $printingMethods[$i]['minQty'] = $result['min_qty'];
		    $printingMethods[$i]['maxQty'] = $result['max_qty'];
		    $printingMethods[$i]['name_price'] = $result['name_price'];
		    $printingMethods[$i]['number_price'] = $result['number_price'];
		    $printingMethods[$i]['isImageUpload'] = $result['is_image_upload'];
		    $printingMethods[$i]['isAlert'] = $result['is_alert'];
		    $printingMethods[$i]['alertMessage'] = $result['alert_message'];
		}
		$i++;
	    }
	}
	return array_values($printingMethods);
    }

    function saveUserImage($data, $imageId) {
    	if($data['user_id'] == '') { $data['user_id'] = 0; }
		//if($data['user_id'] != '') {
			if ($imageId !== '') {
				$this->db->where('userimage_id', $imageId);
				$this->db->update('designnbuy_userimages', $data);
				return $imageId;
			} else {
				$this->db->insert('designnbuy_userimages', $data);
				return $this->db->insert_id();
			}
		//}
    }

    function saveUserImageHd($data, $imageId) {
	if ($imageId !== '') {
	    $this->db->where('userimage_hd_id', $imageId);
	    $this->db->update('designnbuy_userimages_hd', $data);
	    return $imageId;
	} else {
	    $this->db->insert('designnbuy_userimages_hd', $data);
	    return $this->db->insert_id();
	}
    }

    function saveUserImageHdSingle($data, $imageId) {
	$rows = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_id' => $imageId))->result_array();
	$newimageext = pathinfo($data['image_hd'], PATHINFO_EXTENSION);
	foreach ($rows as $row) {
	    $hd = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $row['userimage_hd_id']))->row_array();
	    $oldimageext = pathinfo($hd['image_hd'], PATHINFO_EXTENSION);
	    if ($oldimageext == $newimageext) {
		$imageexistdid = $hd['userimage_hd_id'];
		break;
	    }
	}
	if (isset($imageexistdid) && $imageexistdid != '') {
	    $row = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $imageexistdid))->row_array();
	    $imagepath = TOOL_IMG_PATH . '/uploadedImage/';
	    if ($row) {
		if (file_exists($imagepath . $row['image_hd'])) {
		    unlink($imagepath . $row['image_hd']);
		}
		$imgdata = array(
		    'image_hd' => $data['image_hd']
		);
		$this->db->where('userimage_hd_id', $imageexistdid);
		$this->db->update('designnbuy_userimages_hd', $imgdata);
		return $imageexistdid;
	    } else {
		return 0;
	    }
	} else {
	    $this->db->insert('designnbuy_userimages_hd', $data);
	    $userimage_hd_id = $this->db->insert_id();
	    $imgdata = array(
		'userimage_id' => $imageId,
		'userimage_hd_id' => $userimage_hd_id
	    );
	    $this->db->insert('designnbuy_userimages_hd_image', $imgdata);
	    return $userimage_hd_id;
	}
    }

    function saveUserImageHdImage($imageId, $imageHdId) {
	$data = array(
	    'userimage_id' => $imageId,
	    'userimage_hd_id' => $imageHdId
	);
	$this->db->insert('designnbuy_userimages_hd_image', $data);
    }

    function addCartDesign($data) {
	$this->db->insert('designnbuy_cart_designs', $data);
	return $this->db->insert_id();
    }

    function getCustomerInsertUploadImages($customer_id) {
	$query = $this->db->get_where('designnbuy_userimages', array('user_id' => $customer_id));
	return $query->result_array();
    }
    
    function checkImageHd($image_id) {
	$query = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_id' => $image_id));
	return $query->result_array();
    }

    function deleteUserImage($userimage_id) {
	$query = $this->db->get_where('designnbuy_userimages', array('userimage_id' => $userimage_id));
	$row = $query->row_array();
	$imagepath = TOOL_IMG_PATH . '/uploadedImage/';
	if ($row) {
	    if (file_exists($imagepath . $row['image'])) {
		unlink($imagepath . $row['image']);
	    }
	    $this->db->delete('designnbuy_userimages', array('userimage_id' => $userimage_id));
	    return $this->db->affected_rows();
	} else {
	    return 0;
	}
    }

    /*
     * My designs 
     */

    function addMyDesign($data) {
	$this->db->insert('designnbuy_my_designs', $data);
	return $this->db->insert_id();
    }

    function getMydesigns($customer_id) {
	$this->db->select('designnbuy_my_designs.*,designnbuy_product.product_name');
	$this->db->where('customer_id', $customer_id);
	$this->db->join('designnbuy_product', 'designnbuy_product.product_id = designnbuy_my_designs.product_id');
	$query = $this->db->get('designnbuy_my_designs');
	return $query->result_array();
    }

    function getMyDesignByDesignId($design_id) {
	$query = $this->db->get_where('designnbuy_my_designs', array('my_design_id' => $design_id));
	return $query->row_array();
    }

    function updateMyDesign($design_id, $data) {
	$row = $this->getMyDesignByDesignId($design_id);
	if (!empty($row)) {
	    $dir = TOOL_IMG_PATH . '/saveimg/' . $row['designed_id'] . '/';
	    $this->rrmdir($dir);
	    $this->db->where('my_design_id', $design_id);
	    $this->db->update('designnbuy_my_designs', $data);
	} else {
	    $this->db->insert('designnbuy_my_designs', $data);
	}
    }

    function deleteMydesign($design_id) {
	$row = $this->getMyDesignByDesignId($design_id);
	$dir = TOOL_IMG_PATH . '/saveimg/' . $row['designed_id'] . '/';
	$this->rrmdir($dir);
	$this->db->delete('designnbuy_my_designs', array('my_design_id' => $design_id));
	return $this->db->affected_rows();
    }

    function rrmdir($dir) {
	if (is_dir($dir)) {
	    $objects = scandir($dir);
	    foreach ($objects as $object) {
		if ($object != "." && $object != "..") {
		    if (filetype($dir . "/" . $object) == "dir")
			$this->rrmdir($dir . "/" . $object); else
			unlink($dir . "/" . $object);
		}
	    }
	    reset($objects);
	    rmdir($dir);
	}
    }

    /*
     *  Pretemplate
     */

    function getPretemplateByProductId($product_id) {
	$query = $this->db->get_where('designnbuy_product_pretemplate', array('product_id' => $product_id));
	return $query->row_array();
    }

    function getPretemplate($pretemplate_id) {
	$query = $this->db->get_where('designnbuy_product_pretemplate', array('pretemplate_id' => $pretemplate_id));
	return $query->row_array();
    }

    function addPretemplate($data) {
	$this->db->insert('designnbuy_product_pretemplate', $data);
	return $this->db->insert_id();
    }

    function updatePretemplate($pretemplate_id, $data) {
	$row = $this->getPretemplate($pretemplate_id);
	if (!empty($row)) {
	    $dir = TOOL_IMG_PATH . '/pretemplate/' . $row['designed_id'] . '/';
	    $this->rrmdir($dir);
	    $this->db->where('pretemplate_id', $pretemplate_id);
	    $this->db->update('designnbuy_product_pretemplate', $data);
	} else {
	    $this->db->insert('designnbuy_product_pretemplate', $data);
	}
    }

    /*
     *  Get ORder Design Details
     */

    public function getOrderDetailsbyOrderId($order_id) {
	$query = $this->db->get_where('designnbuy_order_design_relation', array('order_id' => $order_id));
	$results = $query->result_array();

	foreach ($results as $result) {
	    $query = $this->db->get_where('designnbuy_order_designs', array('order_design_id' => $result['order_design_id']));
	    $data[] = $query->row_array();
	}
	return $data;
    }

    public function getOrderDetailsById($order_design_id) {
	$this->db->where('designnbuy_order_designs.order_design_id', $order_design_id);
	$this->db->join('designnbuy_order_design_relation', 'designnbuy_order_designs.order_design_id = designnbuy_order_design_relation.order_design_id');
	$query = $this->db->get('designnbuy_order_designs');
	return $query->row_array();
    }

    public function attachHdImageToOrderOutput($imagename) {
	$this->db->like('image', $imagename);
	$query = $this->db->get('designnbuy_userimages');
	$result = $query->result_array();
	$hdimages = array();
	foreach ($result as $res) {

	    $rel_result = array();
	    $rel_query = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_id' => $res['userimage_id']));
	    $rel_result = $rel_query->result_array();
	    if (!empty($rel_result)) {
		foreach ($rel_result as $rr) {
		    $hd_query = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $rr['userimage_hd_id']));
		    $hd_res = $hd_query->row_array();
		    $hdimages[] = array(
			'image_hd' => $hd_res['image_hd']
		    );
		}
	    }
	}
	return $hdimages;
    }

    /**
     *  My Media
     */
    public function getMyMedia($customer_id) {
	$query = $this->db->get_where('designnbuy_userimages', array('user_id' => $customer_id));
	$result = $query->result_array();
	$userimages = array();
	foreach ($result as $res) {
	    $userimages[$res['userimage_id']] = array(
		'userimage_id' => $res['userimage_id'],
		'user_id' => $res['user_id'],
		'image' => $res['image']
	    );

	    $rel_result = array();
	    $rel_query = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_id' => $res['userimage_id']));
	    $rel_result = $rel_query->result_array();
	    if (!empty($rel_result)) {
		foreach ($rel_result as $rr) {
		    $hd_query = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $rr['userimage_hd_id']));
		    $hd_res = $hd_query->row_array();
		    $userimages[$res['userimage_id']]['image_hd'][] = array(
			'id' => $hd_res['userimage_hd_id'],
			'image' => $hd_res['image_hd']
		    );
		}
	    }
	}
	return $userimages;
    }

    function deleteMyMedia($media_id) {
	$query = $this->db->get_where('designnbuy_userimages', array('userimage_id' => $media_id));
	$row = $query->row_array();
	$imagepath = TOOL_IMG_PATH . '/uploadedImage/';
	if ($row) {
	    $rel_result = array();
	    $rel_query = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_id' => $row['userimage_id']));
	    $rel_result = $rel_query->result_array();
	    //  print "<pre>"; print_r($rel_result); exit;

	    if (!empty($rel_result)) {
		foreach ($rel_result as $rr) {

		    $total_hd_query = $this->db->get_where('designnbuy_userimages_hd_image', array('userimage_hd_id' => $rr['userimage_hd_id']));
		    $total_hd_res = $total_hd_query->num_rows();
		     
		    if ($total_hd_res == '1') {
			$hd_query = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $rr['userimage_hd_id']));
			$hd_res = $hd_query->row_array();
			if ($hd_res) {
			    if (file_exists($imagepath . $hd_res['image_hd'])) {
				unlink($imagepath . $hd_res['image_hd']);
			    }
			    $this->db->delete('designnbuy_userimages_hd_image', array('userimage_id' => $row['userimage_id']));
			    $this->db->delete('designnbuy_userimages_hd', array('userimage_hd_id' => $rr['userimage_hd_id']));
			}
		    }
		}
	    }

	    if (file_exists($imagepath . $row['image'])) {
		unlink($imagepath . $row['image']);
	    }
	    $this->db->delete('designnbuy_userimages', array('userimage_id' => $media_id));
	    return $this->db->affected_rows();
	} else {
	    return 0;
	}
    }

    function deleteHdMedia($hd_media_id) {
	$query = $this->db->get_where('designnbuy_userimages_hd', array('userimage_hd_id' => $hd_media_id));
	$row = $query->row_array();
	$imagepath = TOOL_IMG_PATH . '/uploadedImage/';
	if ($row) {
	    if (file_exists($imagepath . $row['image_hd'])) {
		unlink($imagepath . $row['image_hd']);
	    }
	    $this->db->delete('designnbuy_userimages_hd', array('userimage_hd_id' => $hd_media_id));
	    $this->db->delete('designnbuy_userimages_hd_image', array('userimage_hd_id' => $hd_media_id));
	    return $this->db->affected_rows();
	} else {
	    return 0;
	}
    }

    /*     * ****** Message Board ****** */

    public function getOrderMessageBoardThread($order_id) {
	$thread_id = '';
	if ((int) $order_id > 0) {
	    $row = array();

	    $row = $this->db->get_where('designnbuy_order_message_thread', array('order_id' => $order_id))->row_array();
	    if ($row) {
		$thread_id = $row['thread_id'];
	    } else {

		$data['order_id'] = $order_id;
		$this->db->insert('designnbuy_order_message_thread', $data);
		$thread_id = $this->db->insert_id();

		$adminemails = $this->db->get('designnbuy_message_notification_admin')->result_array();
		foreach ($adminemails as $email) {
		    $emaildata = array(
			'thread_id' => $thread_id,
			'email' => $email['email'],
			'user_type' => $email['user_type']
		    );
		    $this->db->insert('designnbuy_message_notifications', $emaildata);
		}
	    }
	}
	return $thread_id;
    }

    public function getNotificationEmails($thread_id) {
	$query = $this->db->get_where('designnbuy_message_notifications', array('thread_id' => $thread_id));
	return $query->result_array();
    }

    public function checkNotificationEmail($data) {
	$query = $this->db->get_where('designnbuy_message_notifications', array('thread_id' => $data['thread_id'], 'email' => trim($data['email'])));
	return $query->num_rows();
    }

    public function addNotificationEmail($data) {
	$this->db->insert('designnbuy_message_notifications', $data);
	return $this->db->insert_id();
    }

    public function deleteNotificationEmail($notification_id) {
	$query = $this->db->delete('designnbuy_message_notifications', array('notification_id' => $notification_id));
	return $this->db->affected_rows();
    }

    public function addComment($param) {
	$data = array(
	    'thread_id' => $param['thread_id'],
	    'user_id' => $param['user_id'],
	    'comment' => trim($param['comment']),
	    'created' => date('Y-m-d H:i:s')
	);
	$this->db->insert('designnbuy_message_comments', $data);
	$comment_id = $this->db->insert_id();
	$row = array();
	$row = $this->db->get_where('designnbuy_message_comments', array('comment_id' => $comment_id))->row_array();
	return $row;
    }

    public function addCommentFile($data) {
	$this->db->insert('designnbuy_message_comment_files', $data);
	return $this->db->insert_id();
    }

    public function getComments($thread_id) {
	$comments = array();
	$result = $this->db->get_where('designnbuy_message_comments', array('thread_id' => $thread_id))->result_array();
	foreach ($result as $res) {
	    $comments[] = array(
		'comment_id' => $res['comment_id'],
		'user_id' => $res['user_id'],
		'comment' => $res['comment'],
		'created' => $res['created'],
		'files' => $this->getCommentFiles($res['comment_id'])
	    );
	}
	return $comments;
    }

    public function getComment($thread_id, $comment_id) {
	$comment = array();
	$result = $this->db->get_where('designnbuy_message_comments', array('thread_id' => $thread_id, 'comment_id' => $comment_id))->row_array();
	$comment = array(
	    'comment_id' => $result['comment_id'],
	    'user_id' => $result['user_id'],
	    'comment' => $result['comment'],
	    'created' => $result['created'],
	    'files' => $this->getCommentFiles($result['comment_id'])
	);
	return $comment;
    }

    public function getCommentFiles($comment_id) {
	$commentfiles = array();
	$result = $this->db->get_where('designnbuy_message_comment_files', array('comment_id' => $comment_id))->result_array();

	foreach ($result as $res) {
	    $commentfiles[] = array(
		'file_id' => $res['file_id'],
		'file_name' => $res['file_name'],
		'real_file_name' => $res['real_file_name']
	    );
	}
	return $commentfiles;
    }

    public function getLanguageData($language_id) {
	$this->db->where('language_id', $language_id);
	$result = $this->db->get('designnbuy_language')->row_array();
	$labels = array();
	$labelsdata = array();
	$xml = ASSETS_PATH . 'parent_multilanguage/' . $result['iso_code'] . '.xml';
	$xmldata = simplexml_load_file($xml);
	foreach ($xmldata as $_key => $_data) {
	    if ($_data->pc_type == 'elementLabel') {
		$labels['elementLabel'][$_key] = (string) $_data->pc_text;
	    }
	    if ($_data->pc_type == 'rollOver') {
		$labels['rollOver'][$_key] = (string) $_data->pc_text;
	    }
	    if ($_data->pc_type == 'qrCodePanel') {
		$labels['qrCodePanel'][$_key] = (string) $_data->pc_text;
	    }
	    if ($_data->pc_type == 'notification') {
		$labels['notification'][$_key] = (string) $_data->pc_text;
	    }
	    if ($_data->pc_type == 'others') {
		$labels['others'][$_key] = (string) $_data->pc_text;
	    }
	    $labelsdata[$_key] = (string) $_data->pc_text;
	}

	$childxml = ASSETS_PATH . 'child_multilanguage/' . $result['iso_code'] . '.xml';
	$childxmldata = simplexml_load_file($childxml);

	foreach ($childxmldata as $_key => $_data) {
	    if (array_key_exists($_key, $labelsdata)) {
		if ($_data->pc_type == 'elementLabel') {
		    $labels['elementLabel'][$_key] = (string) $_data->pc_text;
		}
		if ($_data->pc_type == 'rollOver') {
		    $labels['rollOver'][$_key] = (string) $_data->pc_text;
		}
		if ($_data->pc_type == 'qrCodePanel') {
		    $labels['qrCodePanel'][$_key] = (string) $_data->pc_text;
		}
		if ($_data->pc_type == 'notification') {
		    $labels['notification'][$_key] = (string) $_data->pc_text;
		}
	    }
	}
	return $labels;
    }
    
    public function getProductMaskImages($product_id) {
	$this->db->select('side1_mask,side2_mask,side3_mask,side4_mask,side5_mask,side6_mask,side7_mask,side8_mask');
	return $this->db->get_where('designnbuy_product_tool_images',array('product_id' => $product_id))->row_array();
    }
    
    public function checkImageExistInEditDesign($designimageids) {
	$this->db->where_in('userimage_id',$designimageids);
	$query = $this->db->get('designnbuy_userimages');
	return $query->num_rows();
    }
    
    /***** Cart data  *********/
    
    function getDesignDataByCartDesignId($cart_design_id) {
	$query = $this->db->get_where('designnbuy_cart_designs', array('cart_design_id' => $cart_design_id));
	return $query->row_array();
    }
    
    // Function Added by Ashok
    function getMulticolorProductimagesFormOptionId($id,$colorOptionId) {
        if($id && $colorOptionId){
        
        $multicolor_product_images = $this->db->get_where('designnbuy_product_tool_multicolor_images', array('product_id' => $id,'color_id' =>$colorOptionId));
        $multicolor_product_images = $multicolor_product_images->result_array();
        
        return $multicolor_product_images;
     }
    }

    // Function Added by somin
    function getMulticolorProductimagesMask($id) {
        $multicolor_product_images = $this->db->get_where('designnbuy_product_tool_multicolor_images', array('product_id' => $id));
        $multicolor_product_images = $multicolor_product_images->result_array();
        return $multicolor_product_images;
   
    }

   function getProductsettingdata($id) {
	$productsetting = $this->db->get_where('designnbuy_product_settings', array('product_id' => $id));
	$productsettingdata = $productsetting->row_array();
	return $productsettingdata;
    }

}