<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Model {

    private $settings;
    
    function __construct() 
    {
        
        parent::__construct();
        
        $this->load->helper('isJson');
        $this->settings = self::getSettings();
        
    }
    
    public function getDetails($name, $field)
    {
        
        $this->db->select('*');
        $this->db->where('name', $name);
        $setting = $this->db->get('settings');  	
        $setting = $setting->result_array();
  	        
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
       
    public function getEnvironment()
    {                
        return $this->settings['environment'];
    }
    
    public function getSiteName()
    {                
        return $this->settings['site_name'];
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
        return $this->settings['meta_description'];
    }
    
    public function getMetaKeywords()
    {        
        return $this->settings['meta_keywords'];      
    }
    
    public function getRobots()
    {        
        return $this->settings['robots'];
    }
    
    public function getTemplate($type = null)
    {        
        if($type == 'main'){
            $template = explode("/", $this->settings['template']);
            return current($template);
        } 
        
        return $this->settings['template']; 
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