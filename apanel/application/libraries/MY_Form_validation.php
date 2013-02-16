<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    /*
    public function is_unique_custom($str, $field){
        
        list($table, $field, $field2, $value2)=explode('.', $field);		
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, $field2 => $value2));
                
        return $query->num_rows() === 0;
        
    }
    
    public function is_unique_custom_edit($str, $field){
        
        list($table, $field, $field2, $value2, $id_field, $id_value)=explode('.', $field);		
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, $field2 => $value2, $id_field.' !=' => $id_value));
                
        return $query->num_rows() === 0;
        
    }
    */
    public function is_unique($str, $field)
    {
        list($table, $field)=explode('.', $field);
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, 'status !=' => 'trash'));

        return $query->num_rows() === 0;
    }
    
    public function is_unique_edit($str, $field)
    {
	
        list($table, $field, $id_field, $id_value)=explode('.', $field);		
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, $id_field.' !=' => $id_value, 'status !=' => 'trash'));
                
        return $query->num_rows() === 0;
		
    }  
  
}