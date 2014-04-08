<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_address_helper {
    
    public static function insert_email($email) {
        $ci = get_instance();
        $ci->load->model('email_model');
        return $ci->email_model->insert(array('address' => $email));
    }
}
