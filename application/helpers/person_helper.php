<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person_helper {
    
    /*
     * @firstname
     * @lastname
     * @birthdate
     * @gender
     */
    public static function create_person($person) {
      $ci = get_instance();
      $ci->load->model('person_model');
      return $ci->person_model->insert($person);
    }
    
}
