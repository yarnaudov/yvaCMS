<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Model {

    private $settings;
    
    function __construct() 
    {
        
        parent::__construct();
        
        $this->load->helper('isJson');
        $this->settings = self::getSettings();
        
    }
    
    public function getDetails($type, $field)
    {
        
        $this->db->select('*');
        $this->db->where('type', $type);
        $setting = $this->db->get('settings');  	
        $setting = $setting->result_array();
  	
        if(isJson($setting[0]['value'])){
            $setting[0]['value'] = json_decode($setting[0]['value'], true);
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
        $settings = $this->db->get('settings');  	
        $settings = $settings->result_array();
                
        $settings_arr = array();
        foreach($settings as $setting){
            if(isJson($setting['value'])){
                $setting['value'] = json_decode($setting['value'], true);  
            }
            $settings_arr[$setting['type']] = $setting['value'];
        }
        
        return $settings_arr;
        
    }
        
    public function getSiteName()
    {                
        return $this->settings['site_name'][get_lang()];
    }
    
    public function getSiteNameInTitle()
    {                
        return $this->settings['site_name_in_title'];
    }
    
    public function getSiteNameInTitleSeparator()
    {                
        return $this->settings['site_name_in_title_separator'];
    }
    
    public function getMetaDescription()
    {        
        return $this->settings['meta_description'][get_lang()];
    }
    
    public function getMetaKeywords()
    {        
        return $this->settings['meta_keywords'][get_lang()];      
    }
    
    public function getRobots()
    {        
        return $this->settings['robots'];
    }
    
    public function getTemplate()
    {        
        return $this->settings['template']; 
    }
    
    public function getUrlSuffix()
    {        
        return $this->settings['url_suffix'];  
    }
    
    public function getMailer()
    {        
        return $this->settings['mailer'];  
    }
    
    public function getSendmail()
    {        
        return $this->settings['sendmail'];  
    }
    
    public function getSSMTHost()
    {        
        return $this->settings['ssmt_host'];  
    }
    
    public function getSSMTPort()
    {        
        return $this->settings['ssmt_port'];  
    }
    
    public function getSSMTSecurity()
    {
        return $this->settings['ssmt_security'];  
    }
    
    public function getSSMTUser()
    {        
        return $this->settings['ssmt_user'];  
    }
    
    public function getSSMTPass()
    {        
        return $this->settings['ssmt_pass'];  
    }
    
    public function getFromName(){
        return $this->settings['from_name']; 
    }
    
    public function getFromEmail(){
        return $this->settings['from_email']; 
    }
    
}