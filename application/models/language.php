<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends CI_Model {
  
    public function getDefault()
    {
        $language = $this->db->query("SELECT abbreviation FROM languages WHERE `default` = 'yes'")->result_array();
        return $language[0]['abbreviation'];

    }
  
    public function getDetails($language_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('language_id', $language_id);

        $language = $this->db->get('languages');  	
        $language = $language->result_array();

        if($field == null){
            $language[0]['custom_fields'] = $this->Custom_field->getValues('languages', $language[0]['language_id']);
            return $language[0];
        }
        else{
            return $language[0][$field];
        }

    }
    
    public function getDetailsByAbbr($abbr, $field = null)
    {

        $this->db->select('*');
        $this->db->where('abbreviation', $abbr);

        $language = $this->db->get('languages');  	
        $language = $language->result_array();

        if($field == null){
            $language[0]['custom_fields'] = $this->Custom_field->getValues('languages', $language[0]['language_id']);
            return $language[0];
        }
        else{
            return $language[0][$field];
        }

    }
    
    public function getAll()
    {
        
        $this->db->select('*');
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $languages = $this->db->get('languages');  	
        $languages = $languages->result_array();
        
        foreach($languages as $language){
            $languages_arr[$language['abbreviation']] = $language['title'];
        }
        
        return $languages_arr;
        
    }
    
    
    
}
