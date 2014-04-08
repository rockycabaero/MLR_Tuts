<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_helper {
    
    /*
     * @building
     * @street
     * @city
     * @province
     * @country
     */
    public static function insert_address($address) {
        $ci = get_instance();
        $ci->load->model('address_model');
        return $ci->address_model->insert($address);
    }
    
}
