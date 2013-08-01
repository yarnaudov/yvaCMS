<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_html extends CI_Model{
	
    function run($module)
    {
	
	if(empty($module['params']['html'])){
	    return '';
	}
	
	$this->load->helper('fix_links');        
        $elements = array('area' => array('href'));
        
        $html = fix_links($module['params']['html'], $elements);
        
	return $html;

    }
    
}