<?php

/**
 * get system base url 
 */
function get_base_url() {
    return "http://37.97.236.145/~shirtwere/";
}

function plateform_solutions($plateform) {
    $data = array();
    if ($plateform == 'opencart') {
	$data['product_path'] = 'index.php?route=designnbuy/initdata/product';
	$data['product_category_path'] = 'index.php?route=designnbuy/initdata/getProductCategory';
	$data['products_from_category_path'] = 'index.php?route=designnbuy/initdata/getProductFromCategory';
	$data['setting_path'] = 'index.php?route=designnbuy/initdata/getSettings';
	$data['order_details_path'] = 'index.php?route=designnbuy/initdata/getOrderDetails';
	$data['mydesign'] = 'index.php?route=designnbuy/designtool&design_id=';
	$data['sharedesign'] = 'index.php?route=designnbuy/designtool&design_id=';
    } else if($plateform == 'magento') {
	$data['product_path'] = 'design/index/product/?';
	$data['product_category_path'] = 'design/index/getProductCategory/';
	$data['products_from_category_path'] = 'design/index/getrelatedproduct/?';
	$data['setting_path'] = 'design/index/getSettings/';
	$data['order_details_path'] = 'design/index/getOrderDetails/?';
	$data['mydesign'] = 'design/index/index/design_id/';
	$data['sharedesign'] = 'design/index/index/design_id/';
    } else if ($plateform == 'prestashop') {
	$data['product_path'] = 'index.php?fc=module&module=designnbuy&controller=initdata&param=product';
	$data['product_category_path'] = 'index.php?fc=module&module=designnbuy&controller=initdata&param=getProductCategory';
	$data['products_from_category_path'] = 'index.php?fc=module&module=designnbuy&controller=initdata&param=getProductFromCategory';
	$data['setting_path'] = 'index.php?fc=module&module=designnbuy&controller=initdata&param=getsettings';
	$data['order_details_path'] = 'index.php?fc=module&module=designnbuy&controller=initdata&param=getOrderDetails';
	$data['mydesign'] = 'index.php?fc=module&module=designnbuy&controller=personalize&design_id=';
	$data['sharedesign'] = 'index.php?fc=module&module=designnbuy&controller=personalize&design_id=';
    } else if($plateform == 'wordpress') {	 
	 $data['product_path'] = 'webservice/designnbuy/?post_type=productAction';
	 $data['product_category_path'] = 'webservice/designnbuy/?post_type=getProductCategory';
	 $data['products_from_category_path'] = 'webservice/designnbuy/?post_type=getProductFromCategory';
	 $data['setting_path'] = 'webservice/designnbuy/?post_type=getSettings';
	 $data['order_details_path'] = 'webservice/designnbuy/?post_type=getOrderDetails';
	 $data['mydesign'] = 'design-tool/?design_id=';
	 $data['sharedesign'] = 'design-tool/?design_id=';
     }
    return $data;
}

/*
 * @Function : send_email
 * @param :$from,$to,$subject,$message
 * @return true if email send else false
 */

function send_email($from, $to, $subject, $message) {

    $CI = & get_instance();
    if (isset($to) && isset($from) && isset($subject) && isset($message)) {
	$CI->email->from($from, ADMIN_EMAIL_NAME);
	$CI->email->to($to);
	$CI->email->subject($subject);
	$CI->email->message($message);

	if ($CI->email->send())
	    return true;
	else
	    return false;
    }else {
	return false;
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This helper will return the directory name basis on the id for e.g id = 20 so it's under range 1 to 100 so 1_100 will be return
 * @param id [primary key of any image table $id
 * @return directory name
 *
  function get_image_directory($id)
  {
  $image_dir=(int) (($id/100)+1);
  return $dirname=(($image_dir*100)-99)."_".$image_dir*100;
  }

  /**
 * Make the directory in given path and change the permission
 * If image directoty is not there then create an directory otherwise change permision
 * @param NONE
 *
  function set_image_direcotry_permission($path)
  {

  if(!is_dir($path))
  {

  mkdir($path,0777);
  chmod($path,0777);
  }
  }

  /**
 * Fetch Image hash According to time
 * @param NONE
 * @return NONE
 *
  function get_image_hash($file_name)
  {
  return sha1($file_name);
  }
  /**
 * Fetch Check user is login or not
 * @param NONE
 * @return session user
 *
  function check_login_user()
  {
  $CI =& get_instance();
  $session_data = $CI->session->all_userdata(); // logged_in_data

  if(isset($session_data['logged_in_data']['id_user']) && $session_data['logged_in_data']['id_user'] != '')
  {
  return $session_data['logged_in_data']['id_user'];
  }else
  {
  return false;
  }

  }
  /**
 * @Function : check_admin_user
 * Fetch Check from session user is admin or not
 * @param NONE
 * @return true if admin user else false
 *

  function check_admin_user()
  {
  $CI =& get_instance();
  $session_data = $CI->session->all_userdata(); // logged_in_data

  if(isset($session_data['logged_in_data']['user_role']) && $session_data['logged_in_data']['user_role'] == 'A')
  {
  return true;
  }else
  {
  return false;
  }

  }
 * 
 */
/* function _get_perm_page_link($url_name)
  {
  $newVariable = str_replace(array("?"," ","/"),array("","-","-"),$url_name);
  $newVariable = str_replace("&", "and", $newVariable);
  $permlink_name = strtolower($newVariable);
  return $permlink_name;
  } */
?>
