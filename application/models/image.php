<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Model {

    public function getDetails($image_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('image_id', $image_id);

        $image = $this->db->get('images');  	
        $image = $image->result_array();

        if($field == null){
            $image[0]['custom_fields'] = $this->Custom_field->getValues('images', $image[0]['image_id']);
            return $image[0];
        }
        else{  	
            return $image[0][$field];
        }

    }
  
    public function getImagesByArticle($article_id)
    {
        
        $this->db->select('*');
        $this->db->where('article_id',  $article_id);
        $this->db->where('status', 'yes');
        $images = $this->db->get('images');  	
        $images = $images->result_array();

        if(empty($images)){
            return '';
        }
        
        $images_arr = array();
        foreach($images as $image){
            $image['custom_fields'] = $this->Custom_field->getValues('images', $image['article_id']);
            $images_arr[] = $image; 
        }
        
        return $images_arr;

    }
    
    public function getImagesByCategory($category)
    {
        
        if(is_int($category)){
            $category_id = $category;            
        }
        else{
            $category_id = @$this->Category->getByAlias($category, 'images', 'category_id');
        }
        
        $this->db->select('*');
        $this->db->where('category_id',  $category_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order');
        $images = $this->db->get('images');  	
        $images = $images->result_array();
        
        if(empty($images)){
            return '';
        }
        
        $images_arr = array();
        foreach($images as $image){
            $image['custom_fields'] = $this->Custom_field->getValues('images', $image['article_id']);
            $images_arr[] = $image; 
        }
        
        return $images_arr;

    }
    
}