<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    
    function _dataExists($table, $column, $id)
    {
        
        $this->db->select('*');
        $this->db->where($column, $id);
        $this->db->where('language_id', $this->trl);
        $this->db->from($table);
        $count = $this->db->count_all_results();
        
        return $count;
        
    }
    
}