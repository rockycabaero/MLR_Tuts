<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volunteer_profile_model extends Base_model{
    const TABLE_NAME = 'contact';
    
    public function get($id) {
        $where = array('id' => $id);
        if(is_array($id) || is_object($id)) $where = $id;
        return $this->_get_with_where($where, self::TABLE_NAME);
    }
    
    public function get_rows($where = array(), $settings = array()) {
        return $this->_get_rows(self::TABLE_NAME, $where, $settings);
    }
    
    public function count($where = array()) {
        return $this->_count($where, self::TABLE_NAME);
    }
}
