<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PC_Controller extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$site_url = $this->getBaseUrl();
	$license_const = array('http://37.97.236.145/~shirtwere/');
	if(!in_array($site_url,$license_const)){
	    exit;
	}
	/* if ($this->uri->segment(1) == 'admin') {
	  $session_dir = dirname($_SERVER['DOCUMENT_ROOT']);
	  $session_txt = $session_dir . '/session.txt';
	  $file = file_get_contents($session_txt, true);
	  $data = unserialize(base64_decode($file));
	  if (empty($data)) {
	  redirect(get_base_url() . 'admin');
	  } else {
	  if (!isset($data['designnbuy']) && !isset($data['token']) && $data['designnbuy'] != 'designnbuyadminlogin' && $data['token'] == '') {
	  redirect(get_base_url() . 'admin');
	  }
	  }
	  } */
	//$this->setRequestData();
    }

    public function successStatus($success = array('SUCCESS')) {
	return array(
	    'status' => 'SUCCESS',
	    'message' => $success,
	);
    }

    public function errorStatus($error = array('0', 'opps! unknown Error ')) {
	return array(
	    'status' => 'FAIL',
	    //'message' => array('errorcode' => $error[0], 'description' => $error[1]), 
	    'message' => is_array($error) ? $error[0] : $error,
	);
    }

    public function printResult($data) {
	$json_data = json_encode($data);
	if (isset($_GET['callback']) && $_GET['callback'] != '') {
	    print $_GET['callback'] . "(" . $json_data . ")";
	} else {
	    header('content-type:application/json');
	    echo $json_data;
	}
	exit;
    }

    public function getBaseUrl() {
	// output: /myproject/index.php
	$currentPath = $_SERVER['PHP_SELF'];

	// output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
	$pathInfo = pathinfo($currentPath);

	// output: localhost
	$hostName = $_SERVER['HTTP_HOST'];

	// output: http://
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';

	// return: http://localhost/myproject/
	return $protocol . $hostName . dirname($pathInfo['dirname']) . "/";
    }

}