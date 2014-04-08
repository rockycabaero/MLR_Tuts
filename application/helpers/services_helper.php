<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services_helper {
    
    public static function get_service($id) {
        if($id < 1)
            throw new Exception("Invalid Service ID: $id");
        
        $ci = get_instance();
        $ci->load->model('service_model');
        $result = $ci->service_model->get($id);
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    public static function get_service_by_name($name) {
        $ci = get_instance();
        $ci->load->model('service_model');
        $result = $ci->service_model->get(array('name' => $name));
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    public static function get_service_by_code($code) {
        $ci = get_instance();
        $ci->load->model('service_model');
        $result = $ci->service_model->get(array('code' => $code));
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    private static function sanitize($raw_data) {
        return $raw_data;
    }
    
    public static function insert_service($name, $code) {
        if(self::get_service_by_name($name))
            throw new Exception("Service already exists: $name");
        if(self::get_service_by_code($code))
            throw new Exception("Service already exists: $code");
        
        $ci = get_instance();
        $ci->load->model('service_model');
        $data['name']   = $name;
        $data['code']   = $code;
        return $ci->service_model->insert($data);
    }
    
    public static function bind_volunteer_service($volunteer_id, $service_code) {
        if($volunteer_id < 1)
            throw new Exception("Invalid Volunteer ID: $volunteer_id");
        
        $ci = get_instance();
        $ci->load->model('volunteer_service_model');
        
        $service = self::get_service_by_code($code);
        
        if(!$service)
            throw new Exception("Cannot bind service to volunteer. Service does not exist. Code: $service_code");
        
        $data['volunteer_id']   = $volunteer_id;
        $data['service_id']     = $service->id;
        return $ci->volunteer_service_model->insert($data);
    }
    
    public static function add_service_component($volunteer_id, $service_id, $name, $value) {
        if($volunteer_id < 1)
            throw new Exception("Invalid Volunteer ID: $volunteer_id");
        
        
        
    }
}
