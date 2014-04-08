<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_helper {
    
    public static function get($customer_id = 0) {
        if($customer_id < 1)
            throw new Exception("Invalid Customer ID: $customer_id");
        
        $ci = get_instance();
        $ci->load->model('customer_model');
        
        $result = $ci->customer_model->get($customer_id);
        return empty($result) ? FALSE : self::sanitize($result);
    }
    
    public static function get_by_username($username) {
        $ci = get_instance();
        $ci->load->model('customer_model');
        $result = $ci->customer_model->get(array('username' => $username));
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
     * @address->country
     * @person->firstname
     * @person->lastname
     * @person->birthdate
     * @person->gender
     * @email
     * @contact = array(name, value)
     * @contact['name' => 'value']
     *     Sample:
     *     $contact['mobile'] = 1234
     *     $contact['telephone'] = 1245
     *     $contact['work'] = 1234
     */
    public static function create_customer($customer) {
        $ci = get_instance();
        $helpers = array(
            'address',
            'email_address',
            'person'
        );
        $ci->load->helper($helpers);
        $ci->load->library('encrypt');
        $ci->load->model('customer_model');
        
        if(self::get_by_username($customer->username))
            throw new Exception("Username exists: $customer->username");
        
        $cust['username'] = $customer->username;
        $cust['password'] = $ci->encrypt->encode($customer->password);
        $cust['deleted']  = FALSE;
        $customer_id    = $ci->customer_model->insert($cust);
        $address_id = Address_helper::insert_address($customer->address);
        $email_id   = Email_address_helper::insert_email($customer->email);
        $person_id  = Person_helper::create_person($customer->person);
        
        foreach($customer->contact as $name => $value) {
            if(!self::insert_contact_number($customer_id, $name, $value)) {
                throw new Exception("Failed to add Customer contact number. $name = $value");
            }
        }
        
        $profile_id = self::bind_customer_profile($customer_id, $address_id, $person_id, $email_id);
        return $customer_id;
    }
    
    private static function bind_customer_profile($customer_id, $address_id, $person_id, $email_id) {
        $ci = get_instance();
        $ci->load->model('customer_profile_model');
        
        $cust['address_id']     = $address_id;
        $cust['customer_id']    = $customer_id;
        $cust['person_id']      = $person_id;
        $cust['email_id']       = $email_id;
        return $ci->customer_profile_model->insert($cust);
    }

    public static function insert_contact_number($customer_id, $name, $value) {
        if($customer_id < 1)
            throw new Exception("Invalid Customer ID: $customer_id");
        
        $ci = get_instance();
        $ci->load->model('customer_contact_model');
        $num['name']    = $name;
        $num['value']   = $value;
        return $ci->customer_contact_model->insert($num);
    }
}
