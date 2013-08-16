<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function getDetails($id, $field = null){
        
        $query = "SELECT 
                      *
                    FROM
                      categories c
                      LEFT JOIN categories_data cd ON (c.id = cd.category_id AND cd.language_id = '".$this->language_id."')
                    WHERE
                      c.id = '".$id."'
		     AND
                      c.status = 'yes'";
        
        $category = $this->db->query($query);
	$category = $category->row_array();

	if(empty($category)){
            return;
        }
	
        if($field == null){
            return $category;
        }
        else{  	
            return $category[$field];
        }
        
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
    
    public function getCategories($extension)
    {
        
        $this->db->select('*');        
        $this->db->join('categories_data' , 'categories.id = categories_data.category_id', 'LEFT');
        $this->db->where('extension', $extension);
        $this->db->where('language_id = '.$this->language_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $categories = $this->db->get('categories')->result_array();  	

        return $categories;

    }
    
}