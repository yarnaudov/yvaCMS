<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_field extends CI_Model {
  
    public function getDetails($custom_field_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $custom_field_id);

        $custom_field = $this->db->get('custom_fields');  	
        $custom_field = $custom_field->result_array();

        if($field == null){
                return $custom_field[0];
        }
        else{  	
            return $custom_field[0][$field];
        }

    }
  
    public function getFields($extension)
    {
        
        $this->db->select('*');
        $this->db->where('extension', $extension);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $custom_fields = $this->db->get('custom_fields');  	
        $custom_fields = $custom_fields->result_array();

        return $custom_fields;

    }
    
    public function getValues($extension, $element_id)
    {
        
        if(empty($element_id)){
            return array();
        }
        
        $custom_fields = $this->Custom_field->getFields($extension);
        
        if(count($custom_fields) == 0){
            return array();
        }
        
        foreach($custom_fields as $custom_field){
            $custom_fields_ids[] = $custom_field['id'];
        }
        
        $query = "SELECT 
                      *
                    FROM
                      custom_fields_values
                    WHERE
                      element_id = ".$element_id."
                     AND
                      custom_field_id IN (".implode(',', $custom_fields_ids).")
                     AND
                      (language_id = '".$this->language_id."' || language_id IS NULL)";
        
        //echo $query;
        
        $custom_fields = $this->db->query($query)->result_array();
        
        $data = array();
        
        foreach($custom_fields as $custom_field){
                       
            if(isJson($custom_field['value'])){
                $custom_field['value'] = json_decode($custom_field['value']);
            }
            $data['field'.$custom_field['custom_field_id']] = $custom_field['value'];
                  
            
        }

        return $data;
        
    }
    
}