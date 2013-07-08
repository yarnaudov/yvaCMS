<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Fix links to full path
 *
 * @access	public
 * @param	string	the html to parse
 * @param	string	array with element => atributes
 * @return	string parsed html string
 */
if ( ! function_exists('fix_links'))
{
	function fix_links($html, $elements = array())
	{
            
            $default = array('a'   => array('href'),
                             'img' => array('src', 'base_url'));
            
            $elements = array_merge($default, $elements);
            
            $CI =& get_instance();
            $CI->load->helper('simple_html_dom');
               
            $html = str_get_html($html);
            
            foreach($elements as $element => $value){
                
                $attributes = is_array($value[0]) ? $value[0] : array(0 => $value[0]);
                $url_func   = isset($value[1]) ? $value[1] : 'site_url';
                
                foreach($html->find($element) as $key => $el){
                    
                    foreach($attributes as $attribute){
                    
                        if(!preg_match('/^http:\/\//', $el->$attribute) &&
                           !preg_match('/^https:\/\//', $el->$attribute) &&
                           !preg_match('/^mailto:/', $el->$attribute)){

                            $el->$attribute = $url_func($el->$attribute);                
                            $html->find($element, $key)->$attribute = $el->$attribute; 

                        }
                    
                    }

                }
            
            }
            
            return $html;
            
	}
}