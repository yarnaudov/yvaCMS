<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function getByAlias($alias, $extension, $field = null){
        
        $this->db->select('*');
        $this->db->where('alias',     $alias);
        $this->db->where('extension', $extension);        
        $category = $this->db->get('categories');  	
        $category = $category->result_array();

        if($field == null){
            return $category[0];
        }
        else{  	
            return $category[0][$field];
        }
        
    }
    
}