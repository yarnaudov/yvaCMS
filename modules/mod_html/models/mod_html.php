<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_html extends CI_Model{
	
    function run($module)
    {
	
	$this->load->helper('fix_links');
        
        $elements = array(0 => array('area', 'href'));
        
        $html = fix_links($module['params']['html'], $elements);
        
	return $html;

    }
    
}