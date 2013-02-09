<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Model {

    public function getDetails($name, $field)
    {

        $this->db->select('*');
        $this->db->where('name', $name);
        $this->db->where('language_id', $this->language_id);
        $this->db->or_where('language_id', NULL);
        $setting = $this->db->get('settings');  	
        $setting = $setting->result_array();
        
        if(empty($setting)){
            return;
        }
        
        if($field == null){
            return $setting[0];
        }
        else{  	
            return $setting[0][$field];
        }

    }

    public function getSettings()
    {
        
        $this->db->select('*');
        $this->db->where('language_id', $this->language_id);
        $this->db->or_where('language_id', NULL);
        $settings = $this->db->get('settings');  	
        $settings = $settings->result_array();
                
        $settings_arr = array();
        foreach($settings as $setting){
            $settings_arr[$setting['name']] = $setting['value'];
        }
        
        return $settings_arr;
        
    }
    
    public function getTemplate()
    {    
    	$settings = self::getSettings();    
        return $settings['template']; 
    }
    
    public function check($data)
    {
        
        $this->db->select('*');
        $this->db->where('name', $data['name']);
        $this->db->where('language_id', $data['language_id']);
        $settings = $this->db->get('settings');  	
        $settings = $settings->result_array();
        
        return count($settings) == 0 ? false : $settings[0]['id'];
        
    }
    
    public function save()
    {
        
        $settings = $this->input->post('settings');

        foreach($settings as $name => $setting){
                        
            switch($name){
                case "site_name":
                case "meta_description":
                case "meta_keywords":
                    $data['language_id'] = $this->language_id;
                 break;
                default:
                    $data['language_id'] = NULL;
                 break;
            }
            
            $data['name']  = $name;
            $data['value'] = $setting;
            
            if($id = self::check($data)){
                $where  = "id = '".$id."'";
                $query  = $this->db->update_string('settings', $data, $where);
                $result = $this->db->query($query);
            }
            else{
                $query  = $this->db->insert_string('settings', $data);
                $result = $this->db->query($query);
            }
         
            if($result != true){
                $error_flag = true;
            }            
            
        }
        
        if(isset($error_flag) && $error_flag == true){            
            $this->session->set_userdata('error_msg', lang('msg_save_settings_error'));
        }
        else{
            $this->session->set_userdata('good_msg', lang('msg_save_settings'));
        }
        

    }
    
}