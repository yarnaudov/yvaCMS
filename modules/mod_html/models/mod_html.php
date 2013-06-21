<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_html extends CI_Model{
	
    function run($module)
    {
	
	$this->load->helper('simple_html_dom');
        
        $html = str_get_html($module['params']['html']);

	/*
	 * fix links to full path
	 */
	foreach($html->find('a') as $key => $a){

	    if(!preg_match('/^http:\/\//', $a->href) && !preg_match('/^mailto:/', $a->href)){

		$a->href = site_url($a->href);                
		$html->find('a', $key)->href = $a->href; 

	    }

	}
	
	/*
	 * fix links to full path special for HTML5 map 
	 */
	foreach($html->find('area') as $key => $a){

	    if(!preg_match('/^http:\/\//', $a->href) && !preg_match('/^mailto:/', $a->href)){

		$a->href = site_url($a->href);                
		$html->find('area', $key)->href = $a->href; 

	    }

	}
	
        /*
         * fix images src to full path
         */
        foreach($html->find('img') as $key => $img){
            
            if(!preg_match('/^http:\/\//', $img->src)){
                
                $img->src = base_url($img->src);                
                $html->find('img', $key)->src = $img->src;    
                
            }
            
        }
        
	return $html;

    }
    
}