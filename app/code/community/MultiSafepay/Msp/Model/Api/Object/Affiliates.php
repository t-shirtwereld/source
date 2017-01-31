<?php
require_once dirname(__FILE__) . "/Core.php";
class Object_Affiliates extends Object_Core {

  public $success;
  public $data;

  public function patch($body, $endpoint = '') {
    $result = parent::patch(json_encode($body), $endpoint);
    $this->success = $result->success;
    $this->data = $result->data;
    return $result;
  }

  public function get($type = 'websites', $id, $body = array(), $query_string = false) {
    $result = parent::get($type, $id, $body, $query_string);
    $this->success = $result->success;
    $this->data = $result->data;
    return $this->data;
  }

  public function post($body, $endpoint = 'websites') {
    $result = parent::post(json_encode($body), $endpoint);
    $this->success = $result->success;
    $this->data = $result->data;
    return $this->data;
  }

}
