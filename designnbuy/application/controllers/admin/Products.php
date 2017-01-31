<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends PC_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
	parent::__construct();
	// Load model into the controler
    $this->pc_rootURL = get_base_url();
	$this->plateform_solution = plateform_solutions(PLATEFORM);
    $this->load->library('curl');
	$this->load->library("pagination");
	$this->load->model('Designnbuy_products_model');
	$this->load->model('Designnbuy_common_webservice_model');
    }

    /**
     * user listing page
     */
    public function index() {

	$data['title'] = 'Products';
	$searchparam = $this->input->get('keyword');
	$categoryparam = $this->input->get('category');
	$productstatus = $this->input->get('status');
	$limit = trim($this->input->get('limit'));
	$store_id = $this->input->get('store_id');
	$data['limit'] = $limit;
	$config = array();
	$config['base_url'] = BASE_ADMIN_URL . 'products/index?keyword=' . $searchparam . '&category=' . $categoryparam . '&productstatus=' . $productstatus . '&store_id=' . $store_id;
	$config['total_rows'] = $this->Designnbuy_products_model->record_count($searchparam, $categoryparam, $productstatus, $store_id);
	$data['total_rows'] = $config['total_rows'];
	if (isset($limit) && $limit != '') {
	    $config['per_page'] = $limit;
	} else {
	    $config['per_page'] = 25;
	}
	$config['page_query_string'] = TRUE;
	$num_links = $config['total_rows'] / $config['per_page'];
	$config['num_links'] = round($num_links);
	$config['cur_tag_open'] = '&nbsp;<a class="current">';
	$config['cur_tag_close'] = '</a>';
	$config['next_link'] = '>>';
	$config['prev_link'] = '<<';

	$this->pagination->initialize($config);
	$paged = (isset($_GET['per_page'])) ? $_GET['per_page'] : 0;

	$str_links = $this->pagination->create_links();
	$data["links"] = explode('&nbsp;', $str_links);
	$data["keywords"] = $searchparam;

	$data['products'] = $this->Designnbuy_products_model->getProductList($searchparam, $categoryparam, $productstatus, $store_id, $config["per_page"], $paged, $config['total_rows']);
	$data['categories'] = $this->Designnbuy_products_model->getProductCategory();
	$data['stores'] = $this->Designnbuy_products_model->getStore();
	$data['status'] = $this->Designnbuy_products_model->getStatus();
	$data['store_id'] = $store_id;
	$data['keyword'] = $searchparam;

	$this->layout_admin->view('admin/products/list', $data);
    }

    public function submitdata() {
	$param = $this->input->post();
	if ($param['product_setting_id'] != '') {
	    $this->editproductsettingdata($param);
	} else {
	    $this->addproductdata($param);
	}
    }

    public function configuration() {
	$getdata = $this->input->get(); 
	$store_id = $getdata['store_id'];
	$productid = $getdata['product_id'];
	$data['title'] = 'Products';
	$data['product_id'] = $productid;
	$data['printing_methods'] = $this->Designnbuy_products_model->getPrintingmethods();
	$data['selected_printing_methods'] = $this->Designnbuy_products_model->getseletedPrintingmethods($productid);
	//$data['package'] = PACKAGE_TYPE;
	$data['product_settings'] = $this->Designnbuy_products_model->getProductsettingdata($productid);
	
	$data['sidelabel'] = $this->Designnbuy_products_model->getProductSideLabels($productid);
	$data['languages'] = $this->Designnbuy_language_model->getLanguageList();
	$data['no_of_side'] = $data['product_settings']['no_of_sides']; 
    $data['is_multicolor'] = $data['product_settings']['is_multicolor']; // Add By Ashok
    $data['product_colors'] = $this->getProductColor($productid); // Added by ashok
    $data['is_3d'] = $data['product_settings']['is_3d']; // Add By Somin
    $data['output'] = $data['product_settings']['output']; // Add By Somin
	$data['is_pretemplate'] = $data['product_settings']['is_pretemplate'];
	$data['productimages'] = $this->Designnbuy_products_model->getProductimages($productid);
    $data['multicolor_productimages'] = $this->Designnbuy_products_model->getMulticolorProductimages($productid);
    $data['coordinates'] = json_encode($this->getCoordinate($productid,$data['is_multicolor'],$data['product_colors'])); // Changed by Ashok
    $data['threedcoordinates'] = json_encode($this->get3dCoordinate($productid)); // Changed by Somin
    $data['product_3d'] = $this->Designnbuy_products_model->getProduct3ddata($productid);//added by somin
// print '<pre>';
//     print_r($data['threedcoordinates']);
//     exit;
	$data['configurefeature'] = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();

	if (empty($data['product_settings'])) {
	    $data['product_settings']['product_setting_id'] = '';
	    $data['product_settings']['no_of_sides'] = '';
	    $data['product_settings']['is_pretemplate'] = 'no';
	    $data['product_settings']['base_unit'] = '';
	} else {
	    $data['product_settings'] = $data['product_settings'];
	}
	$data['store_id'] = $store_id;
	$this->layout_admin->view('admin/products/productsetting', $data);
    }

    function addproductdata($param) {
	$data = array(
	    'product_id' => $param['product_id'],
	    'no_of_sides' => $param['no_of_sides'],
	    'is_pretemplate' => $param['is_pretemplate'],
	    'base_unit' => $param['base_unit'],
	    'name_number' => $param['name_number'],
        'is_multicolor' => $param['is_multicolor'], // Add By Ashok
        'is_3d' => $param['is_3d'], // Add By Somin
        'output' => $param['output'], // Add By Somin
	    'global_side_label' => '1'
	);
	$this->db->insert('designnbuy_product_settings', $data);	
	
	foreach ($param['printing_method_id'] as $pc) {
	    $printingmethoddata = array(
		'product_id' => $param['product_id'],
		'printing_method_id' => $pc
	    );
	    $this->db->insert('designnbuy_printing_methods_product', $printingmethoddata);
	}
	$imageoption = array(
	    'product_id' => $param['product_id'],
	    'side1_product' => '',
	    'side1_mask' => '',
	    'side1_overlay' => '',
	    'side2_product' => '',
	    'side2_mask' => '',
	    'side2_overlay' => '',
	    'side3_product' => '',
	    'side3_mask' => '',
	    'side3_overlay' => '',
	    'side4_product' => '',
	    'side4_mask' => '',
	    'side4_overlay' => '',
	    'side5_product' => '',
	    'side5_mask' => '',
	    'side5_overlay' => '',
	    'side6_product' => '',
	    'side6_mask' => '',
	    'side6_overlay' => '',
	    'side7_product' => '',
	    'side7_mask' => '',
	    'side7_overlay' => '',
	    'side8_product' => '',
	    'side8_mask' => '',
	    'side8_overlay' => '',
	);
	$this->db->insert('designnbuy_product_tool_images', $imageoption);

    //ADDED BY SOMIN FOR 3D
       $threedproduct = array(
		'product_id' => $param['product_id'],
		'modal_image' => '',
		'map_image' => ''
	    );
	   $this->db->insert('designnbuy_threed', $threedproduct);

	   $configareadata3d = array(
	    'product_id' => $param['product_id']
	  );
	   $this->db->insert('designnbuy_threed_configarea', $configareadata3d);
    //ADDED BY SOMIN FOR 3D

	$configareadata = array(
	    'product_id' => $param['product_id']
	);
	$this->db->insert('designnbuy_product_configarea', $configareadata);
	
	$this->session->set_flashdata('msg', 'Product Setting inserted sucessfully');
	redirect(BASE_ADMIN_URL . 'products/configuration/?store_id=' . $param["store_id"] . '&product_id='. $param["product_id"]);
    }

    function editproductsettingdata($param) { 


	if ($param['product_setting_id'] != '') {
	    $data = array(
		'product_id' => $param['product_id'],
		'no_of_sides' => $param['no_of_sides'],
		'is_pretemplate' => $param['is_pretemplate'],
		'base_unit' => $param['base_unit'],
		'name_number' => $param['name_number'],
        'is_multicolor' => $param['is_multicolor'], // Add By Ashok
        'is_3d' => $param['is_3d'], // Add By Somin
        'output' => $param['output'], // Add By Somin
		'global_side_label' => $param['global_side_label']
	    );
	    $this->db->where('product_setting_id', $param['product_setting_id']);
	    $this->db->update('designnbuy_product_settings', $data);
	}
	
	$this->db->delete('designnbuy_product_sidelabels', array('product_id' => $param['product_id']));
	foreach ($param['sidelabel'] as $language_id => $value) {
	    $langdata = array();
	    $langdata = array(
		'product_id' => $param['product_id'],
		'language_id' => $language_id,
		'side_1_label' => $value['side_1_label'],
		'side_2_label' => $value['side_2_label'],
		'side_3_label' => $value['side_3_label'],
		'side_4_label' => $value['side_4_label'],
		'side_5_label' => $value['side_5_label'],
		'side_6_label' => $value['side_6_label'],
		'side_7_label' => $value['side_7_label'],
		'side_8_label' => $value['side_8_label']
	    );
	    $this->db->insert('designnbuy_product_sidelabels', $langdata);
	}

	$this->db->delete('designnbuy_printing_methods_product', array('product_id' => $param['product_id']));
	foreach ($param['printing_method_id'] as $pc) {
	    $printingmethoddata = array(
		'product_id' => $param['product_id'],
		'printing_method_id' => $pc,
	    );
	    $this->db->insert('designnbuy_printing_methods_product', $printingmethoddata);
	}
	/* UPLOAD IMAGE */
	$imageoption = array(
	    'product_id' => $param['product_id'],
	    'side1_product' => $param['side1_product'],
	    'side1_mask' => $param['side1_mask'],
	    'side1_overlay' => $param['side1_overlay'],
	    'side2_product' => $param['side2_product'],
	    'side2_mask' => $param['side2_mask'],
	    'side2_overlay' => $param['side2_overlay'],
	    'side3_product' => $param['side3_product'],
	    'side3_mask' => $param['side3_mask'],
	    'side3_overlay' => $param['side3_overlay'],
	    'side4_product' => $param['side4_product'],
	    'side4_mask' => $param['side4_mask'],
	    'side4_overlay' => $param['side4_overlay'],
	    'side5_product' => $param['side5_product'],
	    'side5_mask' => $param['side5_mask'],
	    'side5_overlay' => $param['side5_overlay'],
	    'side6_product' => $param['side6_product'],
	    'side6_mask' => $param['side6_mask'],
	    'side6_overlay' => $param['side6_overlay'],
	    'side7_product' => $param['side7_product'],
	    'side7_mask' => $param['side7_mask'],
	    'side7_overlay' => $param['side7_overlay'],
	    'side8_product' => $param['side8_product'],
	    'side8_mask' => $param['side8_mask'],
	    'side8_overlay' => $param['side8_overlay'],
	);   
    // Added By Ashok
    if($param['multicolor'] &&  count($param['multicolor'])){
        // echo '<pre>'; print_r($param['multicolor']);exit;
       $this->db->delete('designnbuy_product_tool_multicolor_images', array('product_id' => $param['product_id']));
       foreach($param['multicolor'] as $_image){
        if($_image['image']){
            $this->db->delete('designnbuy_product_tool_multicolor_images', array('product_id' => $param['product_id'],
                                                                                 'side_no'    => $_image['side_no'],
                                                                                 'color_id'   => $_image['color_id']));
            $this->db->insert('designnbuy_product_tool_multicolor_images', $_image);
         }
       } 
    }
    // Added By Ashok

   //ADDED BY SOMIN
	    if ($param['designnbuy_threed_id'] != '') {
	        $threedproduct = array(
			'product_id' => $param['product_id'],
			'modal_image' => $param['modal_image'],
			'map_image' => $param['map_image']
		    );
		   $this->db->where('designnbuy_threed_id', $param['designnbuy_threed_id']);
		   $this->db->update('designnbuy_threed', $threedproduct);
		}else{
			 $this->db->delete('designnbuy_threed', array('product_id' => $param['product_id']));
			  $threedproduct = array(
				'product_id' => $param['product_id'],
			 	'modal_image' => $param['modal_image'],
			    'map_image' => $param['map_image']
		    );
		   $this->db->insert('designnbuy_threed', $threedproduct);
		}

	    $configareadata3d = array(
		    'product_id' => $param['product_id']
		);
		$this->db->insert('designnbuy_threed_configarea', $configareadata3d);
     //ADDED BY SOMIN
    
	$this->db->where('product_id', $param['product_id']);
	$this->db->update('designnbuy_product_tool_images', $imageoption);
	$this->session->set_flashdata('msg', 'Product Setting Updated sucessfully');
	redirect(BASE_ADMIN_URL . 'products/?store_id=' . $param["store_id"]);
    }

    function deleteimage() {
	if ($this->input->is_ajax_request()) {
	    $productid = $_POST['productid'];
	    $side = $_POST['side'];
	    $imageoption = array($side => '');
	    $this->db->where('product_id', $productid);
	    $this->db->update('designnbuy_product_tool_images', $imageoption);
	    echo 'data';
	    exit;
	}
    }

    function deletecolorimage() {
	if ($this->input->is_ajax_request()) {
	    $multi_color_id = $_POST['multi_color_id'];
	    if($multi_color_id){
	       $this->db->delete('designnbuy_product_tool_multicolor_images', array('multicolor_id' => $multi_color_id));
	    } 
	    
	    echo 'data';
	    exit;
	}
    }
    function uploadify() {
	if ($_FILES['Filedata']['error'] == 0) {
	    $filename = $_FILES['Filedata']['name'];
	    $ext = pathinfo($filename, PATHINFO_EXTENSION);
	    // if( $ext != 'gif' || $ext != 'png' || $ext != 'jpg' ) {
	    //    echo '1';
	    // }else{
	    $file = $_FILES['Filedata']['name'];
	    $ext = pathinfo($file, PATHINFO_EXTENSION);
	    $filename = uniqid() . '.' . $ext;
	    $filepath = TOOL_ADMIN_IMG_PATH . "productimage/" . $filename;
	    move_uploaded_file($_FILES['Filedata']['tmp_name'], $filepath);
	    echo $filename;
	    //}
	} else
	    echo 'false';
    } 
    // Changed By Ashok
    private function getCoordinate($product_id,$isMultiColor,$productColor) {
       
	$data = $this->Designnbuy_products_model->getCoordinate($product_id);
    if($isMultiColor){
      $multiColorImageForConfigarea = $this->Designnbuy_products_model->getMulticolorProductimagesForConfigarea($product_id,$productColor);
      if(count($multiColorImageForConfigarea) > 0){
          foreach($multiColorImageForConfigarea as $_image){
            $data['side' . $_image['side_no'] . '_product'] = $_image['image'];
          }
      }
   
    }
   
	$flashvar = array();
	$result = array();
	if ($data['no_of_sides']) {
	    for ($i = 1; $i <= $data['no_of_sides']; $i++) {
	      
		$flashvar[] = array(
		    'image_path' => get_base_url() . 'designnbuy/uploads/productimage/' . $data['side' . $i . '_product'],
            'name' => $data['side_' . $i . '_label'],
		    'X' => $data['side' . $i . '_x'],
		    'Y' => $data['side' . $i . '_y'],
		    'W' => $data['side' . $i . '_width'],
		    'H' => $data['side' . $i . '_height'],
		    'OW' => $data['side' . $i . '_output_width'],
		    'OH' => $data['side' . $i . '_output_height']
		);
	    }
	} 
	$result['coordinate'] = $flashvar;
	return $result;
    }

//added by somin
    private function get3dCoordinate($product_id) {
			$data = $this->Designnbuy_products_model->get3dCoordinate($product_id);
			$flashvar = array();
			$result = array();
			// if ($data['no_of_sides']) {
			//     for ($i = 1; $i <= $data['no_of_sides']; $i++) {
			    $i=1;
				$flashvar[] = array(
				    'image_path' => get_base_url() . 'designnbuy/uploads/productimage/' . $data['map_image'],
		            'name' => $data['side_' . $i . '_label'],
				    'X' => $data['side' . $i . '_x'],
				    'Y' => $data['side' . $i . '_y'],
				    'W' => $data['side' . $i . '_width'],
				    'H' => $data['side' . $i . '_height'],
				    'OW' => $data['side' . $i . '_output_width'],
				    'OH' => $data['side' . $i . '_output_height']
				);
			//     }
			// } 
			$result['coordinatethreed'] = $flashvar;
			return $result;
    }

    public function saveCoordinate() {
	$param = $this->input->post();	
	$productId = $param['product_id'];
	$coordinate = $param['jasonData']['coordinate'];
	$noofside = $param['noofSide'];	
	$data = array();
	$cnt = count($coordinate);
	if ($noofside) {
	    $j = 0;
	    $data['product_id'] = $productId;
	    for($i = 1; $i<=8; $i++) {	
		if($i <= $noofside) {
		    if($noofside == 1) {
			$data['side'.$i.'_height'] = $coordinate[0]['H'];
			$data['side'.$i.'_width'] = $coordinate[0]['W'];
			$data['side'.$i.'_x'] = $coordinate[0]['X'];
			$data['side'.$i.'_y'] = $coordinate[0]['Y'];
			$data['side'.$i.'_output_width'] = $coordinate[0]['OW'];
			$data['side'.$i.'_output_height'] = $coordinate[0]['OH'];
		    } else {
			$data['side'.$i.'_height'] = $coordinate[$j]['H'];
			$data['side'.$i.'_width'] = $coordinate[$j]['W'];
			$data['side'.$i.'_x'] = $coordinate[$j]['X'];
			$data['side'.$i.'_y'] = $coordinate[$j]['Y'];
			$data['side'.$i.'_output_width'] = $coordinate[$j]['OW'];
			$data['side'.$i.'_output_height'] = $coordinate[$j]['OH'];
		    }
		    $j++;
		} else {
		    $data['side'.$i.'_height'] = 0;
		    $data['side'.$i.'_width'] = 0;
		    $data['side'.$i.'_x'] = 0;
		    $data['side'.$i.'_y'] = 0;
		    $data['side'.$i.'_output_width'] = 0;
		    $data['side'.$i.'_output_height'] = 0;
		}
	    }
	}
	$result = $this->Designnbuy_products_model->saveCoordinate($data);
	if($result > 0){
	    $response = 'true';
	} else {
	    $response = 'false';
	}
	echo $response; exit;
    }

//added by somin
     public function save3dCoordinate() {
	$param = $this->input->post();	
	$productId = $param['product_id'];
	$coordinate = $param['jasonData']['coordinatethreed'];
	$noofside = $param['noofSide'];	
	$data = array();
	$cnt = count($coordinate);
	if ($noofside) {
	    $j = 0;
	    $data['product_id'] = $productId;
	    for($i = 1; $i<=8; $i++) {	
		if($i <= $noofside) {
		    if($noofside == 1) {
			$data['side'.$i.'_height'] = $coordinate[0]['H'];
			$data['side'.$i.'_width'] = $coordinate[0]['W'];
			$data['side'.$i.'_x'] = $coordinate[0]['X'];
			$data['side'.$i.'_y'] = $coordinate[0]['Y'];
			$data['side'.$i.'_output_width'] = $coordinate[0]['OW'];
			$data['side'.$i.'_output_height'] = $coordinate[0]['OH'];
		    } else {
			$data['side'.$i.'_height'] = $coordinate[$j]['H'];
			$data['side'.$i.'_width'] = $coordinate[$j]['W'];
			$data['side'.$i.'_x'] = $coordinate[$j]['X'];
			$data['side'.$i.'_y'] = $coordinate[$j]['Y'];
			$data['side'.$i.'_output_width'] = $coordinate[$j]['OW'];
			$data['side'.$i.'_output_height'] = $coordinate[$j]['OH'];
		    }
		    $j++;
		} else {
		    $data['side'.$i.'_height'] = 0;
		    $data['side'.$i.'_width'] = 0;
		    $data['side'.$i.'_x'] = 0;
		    $data['side'.$i.'_y'] = 0;
		    $data['side'.$i.'_output_width'] = 0;
		    $data['side'.$i.'_output_height'] = 0;
		}
	    }
	}
	$result = $this->Designnbuy_products_model->saveCoordinate3d($data);
	if($result > 0){
	    $response = 'true';
	} else {
	    $response = 'false';
	}
	echo $response; exit;
    }
//added by somin
    private function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
	$arrData = array();
	// if input is object, convert into array
	if (is_object($arrObjData)) {
	    $arrObjData = get_object_vars($arrObjData);
	}

	if (is_array($arrObjData)) {
	    foreach ($arrObjData as $index => $value) {
		if (is_object($value) || is_array($value)) {
		    $value = $this->objectsIntoArray($value, $arrSkipIndices);
		}
		if (in_array($index, $arrSkipIndices)) {
		    continue;
		}
		$arrData[$index] = $value;
	    }
	}
	return $arrData;
    }
    // funtion Added by Ashok
    public function getProductColor($product_id){
        $url = $this->pc_rootURL . $this->plateform_solution['product_path'].'&pid=' . $product_id;
       
    	$this->curl->create($url);
    	$this->curl->option('returntransfer', 1);
    	$data = $this->curl->execute();
        $data = json_decode($data, true);
        
        
        $productColors = array();
        foreach($data['option']['color'] as $color){
            $productColors[$color['optionID']] =  $color['colorName'];
        }
        //echo '<pre>'; print_r($productColors);exit;
       return $productColors;
    }

}
