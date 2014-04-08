<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volunteer_helper {
    
    public static function get_volunteer($id) {
        if($id < 1)
            throw new Exception("Invalid Volunteer ID: $id");
        
        $ci = get_instance();
        $ci->load->model('volunteer_model');
        $result = $ci->volunteer_model->get($id);
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    public static function get_by_username($username) {
        $ci = get_instance();
        $ci->load->model('volunteer_model');
        $result = $ci->volunteer_model->get(array('username' => $username));
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    private static function sanitize($raw_data) {
        return $raw_data;
    }
    
    /*
     * @username
     * @password
     * @address->building
     * @address->street
     * @address->city
     * @address->province
     * @adddress->country
     * @person->firstname
     * @person->lastname
     * @person->birthdate YYYY-MM-DD
     * @person->gender
     * @email
     * @contact = array(name, value)
     */
    public static function create_volunteer($volunteer) {
        if(self::get_by_username($volunteer->username))
            throw new Exception("Username exists: $volunteer->username");
        
        $ci = get_instance();
        $helpers = array('address', 'email_address', 'person');
        $ci->load->helper($helpers);
        $ci->load->library('encrypt');
        $ci->load->model('volunteer_model');
        
        $vol['username'] = $volunteer->username;
        $vol['password'] = $ci->encrypt->encode($volunteer->password);
        $vol['deleted']  = FALSE;
        $volunteer_id = $ci->volunteer_model->insert($vol);
        
        $address_id = Address_helper::insert_address($volunteer->address);
        $person_id  = Person_helper::create_person($volunteer->person);
        $email_id   = Email_address_helper::insert_email($volunteer->email);
        
        foreach($volunteer->contact as $name => $value) {
            if(self::insert_contact_number($volunteer_id, $name, $value)) {
                throw new Exception("Failed to add Volunteer contact number: $name = $value");
            }
        }
        
        $profile_id = self::bind_volunteer_profile($volunteer_id, $address_id, $person_id, $email_id);
        return $volunteer_id;
    }
    
    private static function bind_volunteer_profile($volunteer_id, $address_id, $person_id, $email_id) {
        $ci = get_instance();
        $ci->load->model('volunteer_profile_model');
        
        $vol['volunteer_id']    = $volunteer_id;
        $vol['address_id']      = $address_id;
        $vol['person_id']       = $person_id;
        $vol['email_id']        = $email_id;
        return $ci->volunteer_profile_model->insert($vol);
    }
    
    /*
     * @contact['name' => 'value']
     * Sample:
     * $contact['mobile'] = 1234
     * $contact['telephone'] = 1245
     * $contact['work'] = 1234
     */
    public static function insert_contact_number($volunteer_id, $name, $value) {
        if($volunteer_id < 1)
            throw new Exception("Invalid volunteer ID: $volunteer_id");
        
        $ci = get_instance();
        $ci->load->model('volunteer_contact_model');
        
        $num['name']    = $name;
        $num['value']   = $value;
        return $ci->volunteer_contact_model->insert($num);
    }
    
    
}
