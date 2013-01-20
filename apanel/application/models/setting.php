<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Model {

    public function getDetails($type, $field)
    {

        $this->db->select('*');
        $this->db->where('type', $type);
        $setting = $this->db->get('settings');  	
        $setting = $setting->result_array();
  	
        $setting = $setting[0];
        
        if(isJson($setting['value'])){
            $setting['value'] = json_decode($setting['value'], true);
        }
        
        if($field == null){
            return $setting;
        }
        else{  	
            return $setting[$field];
        }

    }

    public function getSettings()
    {
        
        $this->db->select('*');
        $settings = $this->db->get('settings');  	
        $settings = $settings->result_array();
                
        $settings_arr = array();
        foreach($settings as $setting){
            if(isJson($setting['value'])){
                $setting['value'] = json_decode($setting['value'], true);  
            }
            $settings_arr[$setting['name']] = $setting['value'];
        }
        
        return $settings_arr;
        
    }
    
    public function getTemplate()
    {    
    	$settings = self::getSettings();    
        return $settings['template']; 
    }
    
    public function check($type)
    {
        
        $this->db->select('*');
        $this->db->where('type', $type);
        $settings = $this->db->get('settings');  	
        $settings = $settings->result_array();
        
        return count($settings) == 0 ? false : true;
        
    }
    
    public function save()
    {
        
        $settings = $this->input->post('settings');

        foreach($settings as $type => $setting){
                        
            switch($type){
                case "site_name":
                case "meta_description":
                case "meta_keywords":
                    $data['value'] = self::getDetails($type, 'value');
                    $data['value'][$this->trl] = $setting;
                    $data['value'] = json_encode($data['value']);
                 break;
                default:
                    $data['value'] = $setting;
                 break;
            }
            
            if(self::check($type)){
                $where  = "type = '".$type."'";
                $query  = $this->db->update_string('settings', $data, $where);
                $result = $this->db->query($query);
            }
            else{
                $data['type']  = $type;
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