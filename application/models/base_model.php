<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends CI_Model {
  protected function _get($id, $table_name) {
    if ($id === FALSE || $id < 1)
      return FALSE;

    $this->db->where('id', $id);
    $raw = $this->db->get($table_name);
    return $raw->row();
  }

  protected function _get_with_where($where, $table_name) {
    if (!$where)
      return FALSE;

    foreach($where as $k=>$v)
      $this->db->where($k, $v);

    $this->db->limit(1);
    $raw = $this->db->get($table_name);
    return $raw->row();
  }
  
  protected function _count($where, $table_name) {
    foreach($where as $k => $v) {
      $this->db->where($k, $v);
    }
    $this->db->from($table_name);
    return $this->db->count_all_results();
  }
  
  protected function _sum($column_name, $where, $table_name) {
    foreach($where as $k => $v) {
      $this->db->where($k, $v);
    }
    $this->db->select_sum($column_name);
    $query = $this->db->get($table_name);
    return $query->row();
  }

  protected function _insert($row_data, $table_name) {
    if (!$row_data)
      throw new Exception("Attempting to insert an empty row in $table_name.");

    $this->db->insert($table_name, $row_data);
    return $this->db->insert_id();
  }

  protected function _update($row_data, $table_name, $where = array()) {
    foreach($row_data as $key => $val) {
      $this->db->set($key, $val);
    }
    
    foreach($where as $key => $val) {
      $this->db->where($key, $val);
    }
    $this->db->update($table_name);
    return $this->db->affected_rows();
  }

  protected function _psuedo_delete($id, $table_name) {
    $target = $this->_get($id, $table_name);
    if (!$target)
      throw new Exception("Attempting to delete a non-existent item from $table_name.");

    $target->deleted = 1;
    $this->db->where('id', $id);
    $this->db->update(
      $table_name,
      $target);
    return $this->db->affected_rows();
  }

  protected function _actual_delete($id, $table_name) {
    $target = $this->_get($id, $table_name);
    if (!$target)
      throw new Exception("Attempting to delete a non-existent item from $table_name.");

    $this->db->where('id', $id);
    $this->db->delete($table_name);
    return $this->db->affected_rows();
  }

  protected function _actual_delete_with_where($where, $table_name) {
    $target = $this->_get_with_where($where, $table_name);
    if (!$target)
      throw new Exception("Attempting to delete a non-existent item from $table_name.");

    foreach($where as $k=>$v)
      $this->db->where($k, $v);

    $this->db->delete($table_name);
  }
  
  protected function _get_rows_like(
    $table_name,
    $like = array(),
    $settings = NULL,
    $wildcard = 'both') {

    if (!ListingSettings::is_valid($settings))
      throw new Exception('Invalid listing settings.');

    if (!empty($like)) {
      foreach ($like as $k=>$v)
        $this->db->like($k, $v, $wildcard);
    }

    if (!$settings) {
      $query = $this->db->get($table_name);
    } else {

      if (!empty($settings->sort_by)) {
        if (!empty($settings->sort_direction))
          $this->db->order_by($settings->sort_by, $settings->sort_direction);
        else
          $this->db->order_by($settings->sort_by, SqlSortDirection::ASC);
      }

      if (!empty($settings->page_size) && !empty($settings->page)) {
        $query = $this->db->get(
                    $table_name,
                    $settings->page_size,
                    ($settings->page-1)*$settings->page_size);
      } else {
        $query = $this->db->get($table_name);
      }
    }
    
    return $query->result();
  }

  protected function _get_rows(
    $table_name,
    $where = array(),
    $settings = NULL) {

    if (!ListingSettings::is_valid($settings))
      throw new Exception('Invalid listing settings.');

    if (!empty($where)) {
      foreach ($where as $k=>$v)
        $this->db->where($k, $v);
    }

    if (!$settings) {
      $query = $this->db->get($table_name);
    } else {

      if (!empty($settings->sort_by)) {
        if (!empty($settings->sort_direction))
          $this->db->order_by($settings->sort_by, $settings->sort_direction);
        else
          $this->db->order_by($settings->sort_by, SqlSortDirection::ASC);
      }

      if (!empty($settings->page_size) && !empty($settings->page)) {
        $query = $this->db->get(
                    $table_name,
                    $settings->page_size,
                    ($settings->page-1)*$settings->page_size);
      } else {
        $query = $this->db->get($table_name);
      }
    }

    return $query->result();
  }
}
