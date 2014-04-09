<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Customer extends REST_Controller{
    
    public function register() {
        try {
            
        } catch (Exception $ex) {
            $this->response(array('error' => $ex->getMessage()));
        }
    }
}
