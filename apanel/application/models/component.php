<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component extends CI_Model { 
    
    function load($component)
    {
        
        $component = self::_get($component);      
        
        if($component == false){
            return;
        }
        
        /*
         * check and load component jquery_ext libraries if exists
         */
        foreach($component['libraries']['js'] as $library){
            $this->jquery_ext->add_plugin($library);       
        }
        
        /*
         * check and load component js files if exists
         */
        foreach($component['files']['js'] as $file){            
            $file = 'components/'.$component['directory'].'/js/'.$file;       
            if(file_exists($file)){
                $this->jquery_ext->add_library('../'.$file);
            }            
        }
        
        /*
         * check and load component css files if exists
         */
        foreach($component['files']['css'] as $file){            
            $file = 'components/'.$component['directory'].'/css/'.$file;       
            if(file_exists($file)){
                $this->jquery_ext->add_css('../'.$file);
            }            
        }
        
        
        /*
         * check and load component language files if exists
         */
        foreach($component['files']['lang'] as $file){            
            $file = 'components/'.$component['directory'].'/language/'.get_lang().'/'.$file; 
            if(file_exists($file.'_lang.php')){
                $this->load->language('../../../'.$file);
            }            
        }
        
        
        /*
         * check and load component model if exists
         */
        if(isset($component['model'])){
            $model_file = 'components/'.$component['directory'].'/models/'.$component['model'];
            if(file_exists($model_file.'.php')){
                $this->load->model('../../'.$model_file);
                return $this->$component['model']->data;
            }
        }
        
        
    }
    
    function _get($component)
    {
        
        $components = self::_parseXMLfile();
        
        if(!isset($components[$component])){            
            return false;
        }
        else{
            $component = $components[$component];
        }
        //echo print_r($component)."<---search--<br/>";
        
        if(!is_array(@$component['libraries']['js'])){
            $component['libraries']['js'] = array(@$component['libraries']['js']);
        }
        
        if(!is_array(@$component['files']['js'])){
            $component['files']['js'] = array(@$component['files']['js']);
        }
        
        if(!is_array(@$component['files']['css'])){
            $component['files']['css'] = array(@$component['files']['css']);
        }
        
        if(!is_array(@$component['files']['lang'])){
            $component['files']['lang'] = array(@$component['files']['lang']);
        }
        
        return $component;
        
    }
    
    function _parseXMLfile()
    {
        
        $xml_obj = simplexml_load_file('components/components.xml');                
        $components = json_decode(json_encode($xml_obj), 1);         
        return $components;
        
    }
    
}