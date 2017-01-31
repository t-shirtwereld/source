<?php

class Designnbuy_products_model extends CI_Model {

    /**
     * Constructor of the user model
     */
    
    
    /***** Opencart Views *****/
    /* (1) $drop = "DROP VIEW IF EXISTS designnbuy_product";
      $querydata = $this->db->query($drop);

      $sql = "CREATE VIEW designnbuy_product AS SELECT p.product_id,p.model,pd.name as product_name,p.status,p2d.is_customizable,GROUP_CONCAT(DISTINCT(p2s.store_id) SEPARATOR ",") as store_id,p.image,GROUP_CONCAT(DISTINCT(ocd.name) SEPARATOR ",") as category_name FROM oc_product p LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN oc_product_to_category p2c ON p.product_id = p2c.product_id LEFT JOIN oc_category_description ocd ON p2c.category_id = ocd.category_id LEFT JOIN oc_product_to_store p2s ON p.product_id = p2s.product_id LEFT JOIN oc_designnbuy_designtool p2d ON p.product_id = p2d.product_id WHERE p2d.is_customizable = 1 GROUP BY p.product_id";

      $querydata = $this->db->query($sql);

      (2) $catdrop = "DROP VIEW IF EXISTS designnbuy_product_categories";
      $this->db->query($catdrop);

      $catsql = "CREATE VIEW designnbuy_product_categories AS SELECT c.category_id AS category_id, cd.name AS category_name, c.parent_id, GROUP_CONCAT(DISTINCT(c2s.store_id) SEPARATOR ",") as store_id FROM oc_category c LEFT JOIN oc_category_description cd ON (c.category_id = cd.category_id) LEFT JOIN oc_category_to_store c2s ON (c.category_id = c2s.category_id) GROUP BY c.category_id";
      $this->db->query($catsql);

      (3) $storedrop = "DROP VIEW IF EXISTS designnbuy_stores";
      $this->db->query($storedrop);

      $storesql = "CREATE VIEW designnbuy_stores AS SELECT * FROM oc_store";
      $this->db->query($storesql); */


    /***** Magento Views *****/
    /* (1) $drop = "DROP VIEW IF EXISTS designnbuy_product";
      $querydata = $this->db->query($drop);

      $sql = "CREATE VIEW designnbuy_product AS SELECT `e`.entity_id as product_id,`e`.sku as model,IF(at_status.value_id > 0, at_status.value, at_status_default.value) AS `status`,
      IF(`at_is_customizable`.`value` IS NULL,1, at_is_customizable.value) AS `is_customizable`,
      IF(at_name.value_id > 0, at_name.value, at_name_default.value) AS `product_name`,
      IF(at_image.value_id > 0, at_image.value, at_image_default.value) AS `image`,
      IFNULL(GROUP_CONCAT(`catalog_category_entity_varchar`.`value` SEPARATOR ','), '') AS `category_name`

      FROM
      `catalog_product_entity` AS `e`
      LEFT JOIN
      `catalog_product_entity_int` AS `at_visibility`
      ON (`at_visibility`.`entity_id` = `e`.`entity_id`) AND
      (`at_visibility`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'visibility' AND et.entity_type_code = 'catalog_product')) AND
      (`at_visibility`.`store_id` = 0)
      INNER JOIN
      `catalog_product_entity_int` AS `at_status_default`
      ON (`at_status_default`.`entity_id` = `e`.`entity_id`) AND
      (`at_status_default`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'status' AND et.entity_type_code = 'catalog_product')) AND
      (`at_status_default`.`store_id` = 0)
      LEFT JOIN
      `catalog_product_entity_int` AS `at_status`
      ON (`at_status`.`entity_id` = `e`.`entity_id`) AND
      (`at_status`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'status' AND et.entity_type_code = 'catalog_product')) AND
      (`at_status`.`store_id` = 1)
      INNER JOIN
      `catalog_product_entity_varchar` AS `at_name_default`
      ON (`at_name_default`.`entity_id` = `e`.`entity_id`) AND
      (`at_name_default`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'name' AND et.entity_type_code = 'catalog_product')) AND
      (`at_name_default`.`store_id` = 0)
      LEFT JOIN
      `catalog_product_entity_varchar` AS `at_name`
      ON (`at_name`.`entity_id` = `e`.`entity_id`) AND
      (`at_name`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'name' AND et.entity_type_code = 'catalog_product')) AND
      (`at_name`.`store_id` = 1)
      LEFT JOIN
      `catalog_product_entity_text` AS `at_is_customizable`
      ON (`at_is_customizable`.`entity_id` = `e`.`entity_id`) AND
      (`at_is_customizable`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'is_customizable' AND et.entity_type_code = 'catalog_product')) AND
      (`at_is_customizable`.`store_id` = 0)
      LEFT JOIN
      `catalog_product_entity_int` AS `at_is_customizable_int`
      ON (`at_is_customizable_int`.`entity_id` = `e`.`entity_id`) AND
      (`at_is_customizable_int`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'is_customizable' AND et.entity_type_code = 'catalog_product')) AND
      (`at_is_customizable_int`.`store_id` = 0)
      LEFT JOIN `catalog_product_entity_varchar` AS `at_image_default`
      ON (`at_image_default`.`entity_id` = `e`.`entity_id`)  AND
      (`at_image_default`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'image' AND et.entity_type_code = 'catalog_product'))AND
      (`at_image_default`.`store_id` = 0)
      LEFT JOIN `catalog_product_entity_varchar` AS `at_image`
      ON (`at_image`.`entity_id` = `e`.`entity_id`)  AND
      (`at_image`.`attribute_id` = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'image' AND et.entity_type_code = 'catalog_product')) AND
      (`at_image`.`store_id` = 1)
      LEFT JOIN `catalog_category_product`
      ON (catalog_category_product.product_id=e.entity_id)
      LEFT JOIN `catalog_category_entity_varchar`
      ON (catalog_category_entity_varchar.entity_id=catalog_category_product.category_id)
      AND (`catalog_category_entity_varchar`.attribute_id = (SELECT attribute_id FROM `eav_attribute` ea LEFT JOIN `eav_entity_type` et ON ea.entity_type_id = et.entity_type_id  WHERE `ea`.`attribute_code` = 'name' AND et.entity_type_code = 'catalog_category'))
      WHERE ((at_is_customizable.value = '1' or at_is_customizable_int.value='1') and at_visibility.value = '4') GROUP BY `e`.`entity_id`";

      $querydata = $this->db->query($sql);

      (2) $catdrop = "DROP VIEW IF EXISTS designnbuy_product_categories";
      $this->db->query($catdrop);

      $catsql = "CREATE VIEW designnbuy_product_categories AS SELECT `e`.parent_id,`e`.entity_id as category_id,`cn`.value as category_name FROM `catalog_category_entity` AS `e`,`catalog_category_entity_varchar` AS `cn` WHERE (`e`.`entity_type_id` = '3') AND (`e`.`entity_id` = `cn`.`entity_id`) GROUP BY `e`.entity_id";
      $this->db->query($catsql);

      (3) /*$storedrop = "DROP VIEW IF EXISTS designnbuy_stores";
      $this->db->query($storedrop);
      websites,websites
      $sql = "CREATE VIEW designnbuy_stores AS SELECT `core_website`.website_id AS store_id, `core_website`.name AS name, if( `A`.value IS NULL , `C`.value, `A`.value ) AS `url` , if( `B`.value IS NULL , `D`.value, `B`.value ) AS `ssl`
      FROM `core_website`
      LEFT JOIN `core_config_data` AS `A` ON ( (
      `core_website`.website_id = `A`.scope_id
      )
      AND (
      `A`.path = 'web/unsecure/base_url'
      ) )
      LEFT JOIN `core_config_data` AS `B` ON ( (
      `core_website`.website_id = `B`.scope_id
      )
      AND (
      `B`.path = 'web/secure/base_url'
      ) )
      LEFT JOIN `core_config_data` AS `C` ON ( (
      '0' = `C`.scope_id
      )
      AND (
      `C`.path = 'web/secure/base_url'
      ) )
      LEFT JOIN `core_config_data` AS `D` ON ( (
      '0' = `D`.scope_id
      )
      AND (
      `D`.path = 'web/secure/base_url'
      ) )
      WHERE `core_website`.website_id != '0'";
      $this->db->query($storesql); */


    /***** Wordpress *****/
    /* (1) $drop = "DROP VIEW IF EXISTS designnbuy_product";
      $querydata = $this->db->query($drop);

      $sql = "CREATE VIEW `designnbuy_product` AS select `wp_posts`.`ID` AS `product_id`,`wp_posts`.`post_title` AS `product_name`,(case `wp_posts`.`post_status` when 'publish' then 1 when 'pending' then 0 when 'draft' then 0 end) AS `status`,`pm1`.`meta_value` AS `model`,`pm2`.`meta_value` AS `is_customizable`,(select `wp_posts`.`guid` from `wp_posts` where (`wp_posts`.`ID` = `pm3`.`meta_value`)) AS `image`,(select group_concat(`wp_terms`.`name` separator ', ') from ((`wp_terms` join `wp_term_taxonomy` on((`wp_terms`.`term_id` = `wp_term_taxonomy`.`term_id`))) join `wp_term_relationships` `wpr` on((`wpr`.`term_taxonomy_id` = `wp_term_taxonomy`.`term_taxonomy_id`))) where ((`wp_term_taxonomy`.`taxonomy` = 'product_cat') and (`wp_posts`.`ID` = `wpr`.`object_id`))) AS `category_name` from (((`wp_posts` left join `wp_postmeta` `pm1` on(((`wp_posts`.`ID` = `pm1`.`post_id`) and (`pm1`.`meta_key` = '_sku')))) left join `wp_postmeta` `pm2` on(((`wp_posts`.`ID` = `pm2`.`post_id`) and (`pm2`.`meta_key` = '_is_customizable')))) left join `wp_postmeta` `pm3` on(((`wp_posts`.`ID` = `pm3`.`post_id`) and (`pm3`.`meta_key` = '_thumbnail_id')))) where ((`wp_posts`.`post_type` = 'product') and (`pm2`.`meta_value` = '1') and (((`pm1`.`meta_key` = '_sku') and (`pm2`.`meta_value` = 'yes')) or (`pm3`.`meta_key` = '_thumbnail_id'))) group by `wp_posts`.`ID` order by `wp_posts`.`post_date` desc";

      $querydata = $this->db->query($sql);

      (2) $catdrop = "DROP VIEW IF EXISTS designnbuy_product_categories";
      $this->db->query($catdrop);

      $catsql = "CREATE VIEW designnbuy_product_categories AS SELECT tt.parent as parent_id,t.term_id as category_id,t.name as category_name FROM wp_term_taxonomy tt,wp_terms t WHERE tt.taxonomy = 'product_cat' AND tt.term_id = t.term_id";
      $this->db->query($catsql);
     */

    /***** Presta shop *****/

    /* (1) $drop = "DROP VIEW IF EXISTS designnbuy_product";
      $querydata = $this->db->query($drop);

      $sql = "CREATE VIEW `designnbuy_product` AS select concat('http://',ifnull(`conf`.`value`,'domain'),'/img/p/',substr(`i`.`id_image`,-(2),1),'/',substr(`i`.`id_image`,-(1),1),'/',`i`.`id_image`,'.jpg') AS `image`,`p`.`id_product` AS `product_id`,`p`.`active` AS `status`,`pl`.`name` AS `product_name`,group_concat(distinct `cl`.`name` separator ',') AS `category_name`,`p`.`id_shop_default` AS `store_id`,`p`.`reference` AS `model`,`ppd`.`is_customizable` AS `is_customizable` from ((((((((`ps_product` `p` left join `ps_product_lang` `pl` on((`p`.`id_product` = `pl`.`id_product`))) left join `ps_category_product` `cp` on((`p`.`id_product` = `cp`.`id_product`))) left join `ps_category_lang` `cl` on((`cp`.`id_category` = `cl`.`id_category`))) left join `ps_category` `c` on((`cp`.`id_category` = `c`.`id_category`))) left join `ps_designnbuy_designtool` `ppd` on((`p`.`id_product` = `ppd`.`product_id`))) left join `ps_image` `i` on(((`i`.`id_product` = `p`.`id_product`) and (`i`.`cover` = 1)))) left join `ps_configuration` `conf` on((`conf`.`name` = 'PS_SHOP_DOMAIN'))) left join `ps_product_shop` `ps` on((`p`.`id_product` = `ps`.`id_product`))) where ((`ps`.`active` = 1) and (`cl`.`id_lang` = 1) and (`ppd`.`is_customizable` = 1)) group by `p`.`id_product`";

      $querydata = $this->db->query($sql);

      (2) $catdrop = "DROP VIEW IF EXISTS designnbuy_product_categories";
      $this->db->query($catdrop);

      $catsql = " CREATE VIEW `designnbuy_product_categories` AS select `c`.`id_category` AS `category_id`,`c`.`id_parent` AS `parent_id`,`cl`.`name` AS `category_name`,`cs`.`id_shop` AS `store_id` from ((`ps_category` `c` left join `ps_category_lang` `cl` on((`cl`.`id_category` = `c`.`id_category`))) left join `ps_category_shop` `cs` on((`c`.`id_category` = `cs`.`id_category`))) where (`cl`.`id_lang` = '1') group by `c`.`id_category`";
      $this->db->query($catsql);

      (3) $storedrop = "DROP VIEW IF EXISTS designnbuy_stores";
      $this->db->query($storedrop);

      $storesql = "CREATE VIEW `designnbuy_stores` AS select `s`.`id_shop` AS `store_id`,`s`.`name` AS `name`,concat(`su`.`domain`,`su`.`physical_uri`) AS `url`,concat(`su`.`domain_ssl`,`su`.`physical_uri`) AS `ussl` from (`ps_shop_url` `su` left join `ps_shop` `s` on((`s`.`id_shop` = `su`.`id_shop`))) where ((`s`.`active` = 1) and (`s`.`deleted` = 0)) order by length(concat(`su`.`physical_uri`,`su`.`virtual_uri`))";
      $this->db->query($storesql); */

    function __construct() {
	parent::__construct();
	$this->db = $this->load->database('default', true);
    }

    function getProductList($searchparam = NULL, $categoryparam = NULL, $productstatus = NULL, $store_id = NULL, $limit, $start, $total) {

	if (isset($categoryparam) && $categoryparam != '') {
	    $where = "FIND_IN_SET('" . $categoryparam . "', TRIM(category_name)) > 0";
	    $this->db->where($where);
	}
	if (isset($store_id) && $store_id != '') {
	    $where = "FIND_IN_SET('" . $store_id . "', TRIM(store_id)) > 0";
	    $this->db->where($where);
	}
	if (isset($productstatus) && $productstatus != '') {
	    $this->db->where('status', $productstatus);
	}
	if (!empty($searchparam)) {
	    $this->db->like('product_name', $searchparam);
	    $this->db->or_like('model', $searchparam);
	}

	if ($limit !== NULL && $start !== NULL) {
	    $this->db->limit($limit, $start);
	}
	$query = $this->db->get('designnbuy_product');
	$results = $query->result_array();

	$products = array();
	$i = 0;
	foreach ($results as $res) {
	    $products[$i]['sku'] = $res['model'];
	    $products[$i]['name'] = $res['product_name'];
	    $products[$i]['shop'] = $res['store_id'];
	    $products[$i]['id'] = $res['product_id'];
	    if ($res['status'] != '1') {
		$products[$i]['status'] = 'Inactive';
	    } else {
		$products[$i]['status'] = 'Active';
	    }
	    if(PLATEFORM == 'opencart'){
		$products[$i]['image'] = get_base_url() . 'image/' . $res['image'];
	    } else if(PLATEFORM == 'magento') {
		$products[$i]['image'] = get_base_url() . 'media/catalog/product/' . $res['image'];
	    } else {
		$products[$i]['image'] = $res['image'];
	    }
	    
	    $products[$i]['category_name'] = $res['category_name'];
	    $products[$i]['customizable'] = $res['is_customizable'];
	    $i++;
	}
	return $products;
    }

    function record_count($searchparam, $categoryparam, $productstatus, $store_id) {
	if (isset($categoryparam) && $categoryparam != '') {
	    $where = "FIND_IN_SET('" . $categoryparam . "', TRIM(category_name)) > 0";
	    $this->db->where($where);
	}
	if (isset($store_id) && $store_id != '') {
	    $where = "FIND_IN_SET('" . $store_id . "', TRIM(store_id)) > 0";
	    $this->db->where($where);
	}
	if (isset($productstatus) && $productstatus != '') {
	    $this->db->where('status', $productstatus);
	}
	if (!empty($searchparam)) {
	    $this->db->like('product_name', $searchparam);
	    $this->db->or_like('model', $searchparam);
	}

	$query = $this->db->get('designnbuy_product');
	$results = $query->num_rows();
	return $results;
    }

    function getProductCategory() {
	$cat_dropdown = '<select name="category" id="category" onchange="this.form.submit();">';
	$cat_dropdown .= '<option value="">Select Category</option>';
	$cat_dropdown .= $this->buildCategory();
	$cat_dropdown .= '</select>';
	return $cat_dropdown;
    }

    function buildCategory($parent_id = 1, $sep = '') {
	$store_id = $this->input->get('store_id');
	//$sql = "SELECT * FROM designnbuy_product_categories WHERE parent_id = '" . $parent_id . "' ";
	$sql = "SELECT * FROM designnbuy_product_categories WHERE parent_id = '" . $parent_id . "' ";
	if (isset($store_id) && $store_id != '') {
	    $sql .= "AND FIND_IN_SET('" . $store_id . "', TRIM(store_id)) > 0";
	}
	$query = $this->db->query($sql);
	$results = $query->result_array();
	$categoryparam = $this->input->get('category');

	$category = '';
	if ($parent_id == 0) {
	    $sep = '';
	} else {
	    $sep .="&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	if (!empty($results)) {
	    $category = '';
	    foreach ($results as $result) {
		if ($categoryparam == $result['category_name']) {
		    $selected = 'selected';
		} else {
		    $selected = '';
		}
		$category .= '<option value="' . $result['category_name'] . '" ' . $selected . ' >' . $sep . $result['category_name'] . '</option>';

		$category .= $this->buildCategory($result['category_id'], $sep);
	    }
	    return $category;
	} else {
	    return '';
	}
    }

    function getStore() {
	$myVar = MULTISTORE;
	if ($myVar == true) {
	    $sql = "SELECT * FROM designnbuy_stores";
	    $query = $this->db->query($sql);
	    $result = $query->result_array();
	    $default[] = array(
		'store_id' => '0',
		'name' => 'Default'
	    );
	    $store = array();
	    if (!empty($result)) {
		if (PLATEFORM == 'opencart') {
		    $store = array_merge($default, $result);
		} else {
		    $store = $result;
		}
	    } else {
		$store = array();
	    }
	} else {
	    $store = array();
	}
	return $store;
    }

    function getStatus() {
	$productstatus = $this->input->get('status');
	if ($productstatus == '1') {
	    $selected = 'selected';
	} else {
	    $selected = '';
	}
	if ($productstatus == '0') {
	    $selected2 = 'selected';
	} else {
	    $selected2 = '';
	}
	if ($productstatus == '') {
	    $selected = '';
	}
	$status = '<select name="status" id="productstatus" onchange="this.form.submit();">';
	$status .= '<option value="">Status</option>';
	$status .= '<option value="1" ' . $selected . '>Active</option>';
	$status .= '<option value="0" ' . $selected2 . '>Inactive</option>';
	$status .= '</select>';
	return $status;
    }

    function getProductsettingdata($id) {
	$productsetting = $this->db->get_where('designnbuy_product_settings', array('product_id' => $id));
	$productsettingdata = $productsetting->row_array();
	return $productsettingdata;
    }
    //added by somin
     function getProduct3ddata($id) {
  $productsetting = $this->db->get_where('designnbuy_threed', array('product_id' => $id));
  $productsettingdata = $productsetting->row_array();
  return $productsettingdata;
    }
    //added by somin

    function getProductSideLabels($id) {
	$query = $this->db->get_where('designnbuy_product_sidelabels', array('product_id' => $id));
	foreach ($query->result_array() as $result) {
	    $sideLabel[$result['language_id']] = array(
		'side_1_label' => $result['side_1_label'],
		'side_2_label' => $result['side_2_label'],
		'side_3_label' => $result['side_3_label'],
		'side_4_label' => $result['side_4_label'],
		'side_5_label' => $result['side_5_label'],
		'side_6_label' => $result['side_6_label'],
		'side_7_label' => $result['side_7_label'],
		'side_8_label' => $result['side_8_label']
	    );
	}
	return $sideLabel;
    }

    function getPrintingmethods() {
	$this->db->where('designnbuy_printing_methods_lang.language_id', '1');
	$this->db->join('designnbuy_printing_methods_lang', 'designnbuy_printing_methods.printing_method_id = designnbuy_printing_methods_lang.printing_method_id');
	$this->db->group_by('designnbuy_printing_methods.printing_method_id');
	$query = $this->db->get("designnbuy_printing_methods");
	return $query->result_array();
    }

    function getseletedPrintingmethods($id) {
	$selectedprintingmethods = $this->db->get_where('designnbuy_printing_methods_product', array('product_id' => $id));
	return $selectedprintingmethods->result_array();
    }

    function getProductimages($id) {

	$productimages = $this->db->get_where('designnbuy_product_tool_images', array('product_id' => $id));
	$productimagesdata = $productimages->row_array();
	return $productimagesdata;
    }
    // Function Added by Ashok 
    function getMulticolorProductimages($id) {
	$multicolor_product_images = $this->db->get_where('designnbuy_product_tool_multicolor_images', array('product_id' => $id));
	$multicolor_product_images_data = $multicolor_product_images->result_array();
	return $multicolor_product_images_data;
    }
    
    // Function Added by Ashok
    function getMulticolorProductimagesForConfigarea($id,$productColor) {
    $multicolor_product_images_data_for_configarea = '';    
    if($id && count($productColor) > 0){
        
        $multicolor_product_images = $this->db->get_where('designnbuy_product_tool_multicolor_images', array('product_id' => $id,'color_id' =>key($productColor)));
        $multicolor_product_images_data_for_configarea = $multicolor_product_images->result_array();
    }
	return $multicolor_product_images_data_for_configarea;
    }
        
    function getCoordinate($product_id) {
	$this->db->where('designnbuy_product_configarea.product_id', $product_id);
	$this->db->where('designnbuy_product_sidelabels.language_id', '1');
	$this->db->join('designnbuy_product_tool_images', 'designnbuy_product_tool_images.product_id = designnbuy_product_configarea.product_id');
	$this->db->join('designnbuy_product_settings', 'designnbuy_product_settings.product_id = designnbuy_product_configarea.product_id');
	$this->db->join('designnbuy_product_sidelabels', 'designnbuy_product_sidelabels.product_id = designnbuy_product_configarea.product_id');
	$query = $this->db->get_where('designnbuy_product_configarea');
	$data = $query->row_array();
	if ($data['global_side_label'] != '0') {
	    $gloablSideLabels = $this->getGloablSideLabels();
	    $data['side_1_label'] = $gloablSideLabels['side_1_label'];
	    $data['side_2_label'] = $gloablSideLabels['side_2_label'];
	    $data['side_3_label'] = $gloablSideLabels['side_3_label'];
	    $data['side_4_label'] = $gloablSideLabels['side_4_label'];
	    $data['side_5_label'] = $gloablSideLabels['side_5_label'];
	    $data['side_6_label'] = $gloablSideLabels['side_6_label'];
	    $data['side_7_label'] = $gloablSideLabels['side_7_label'];
	    $data['side_8_label'] = $gloablSideLabels['side_8_label'];
	}
	return $data;
    }

//ADDED BY SOMIN
   function get3dCoordinate($product_id) {
  $this->db->where('designnbuy_threed_configarea.product_id', $product_id);
  $this->db->where('designnbuy_product_sidelabels.language_id', '1');
  $this->db->join('designnbuy_threed', 'designnbuy_threed.product_id = designnbuy_threed_configarea.product_id');
  $this->db->join('designnbuy_product_settings', 'designnbuy_product_settings.product_id = designnbuy_threed_configarea.product_id');
  $this->db->join('designnbuy_product_sidelabels', 'designnbuy_product_sidelabels.product_id = designnbuy_threed_configarea.product_id');
  $query = $this->db->get_where('designnbuy_threed_configarea');
  $data = $query->row_array();
  if ($data['global_side_label'] != '0') {
      $gloablSideLabels = $this->getGloablSideLabels();
      $data['side_1_label'] = $gloablSideLabels['side_1_label'];
      $data['side_2_label'] = $gloablSideLabels['side_2_label'];
      $data['side_3_label'] = $gloablSideLabels['side_3_label'];
      $data['side_4_label'] = $gloablSideLabels['side_4_label'];
      $data['side_5_label'] = $gloablSideLabels['side_5_label'];
      $data['side_6_label'] = $gloablSideLabels['side_6_label'];
      $data['side_7_label'] = $gloablSideLabels['side_7_label'];
      $data['side_8_label'] = $gloablSideLabels['side_8_label'];
  }
  return $data;
    }

      function saveCoordinate3d($data) {
  $this->db->where('product_id', $data['product_id']);
  $this->db->update('designnbuy_threed_configarea', $data);
  return $data['product_id'];
    }
//ADDED BY SOMIN


    function getGloablSideLabels() {
	//$this->db->where('printcommerce_product_sidelabels.language_id', $language_id);
	$query = $this->db->get_where('designnbuy_global_sidelabels', array('general_settings_id' => '1', 'language_id' => '1'));
	return $query->row_array();
    }

    function saveCoordinate($data) {
	$this->db->where('product_id', $data['product_id']);
	$this->db->update('designnbuy_product_configarea', $data);
	return $data['product_id'];
    }


  
}