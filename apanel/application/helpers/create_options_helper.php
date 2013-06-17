<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_options'))
{
	function create_options($table, $field1, $field2, $selected, $filters = array())
	{
	    $CI =& get_instance();
            
	    $CI->db->select('*');
	    
            foreach($filters as $field => $value){
 
                $CI->db->where($field, $value);
          
            }
            
            $options = $CI->db->get($table);
            $options = $options->result_array();
            
            $options_str = '';
            foreach($options as $option){
                $selected_opt = "";                
                if($option[$field1] == $selected){
                    $selected_opt = "selected";                
                }
                
                $options_str .= '<option '.$selected_opt.' value="'.$option[$field1].'" >'.$option[$field2].'</option>';
            
            }
            
            return $options_str;
		
	}
}


if ( ! function_exists('create_options_array'))
{
	function create_options_array($options, $selected)
	{
	                
            $options_str = '';
            foreach($options as $option_v => $option_t){
                                
                if(is_array($option_t)){
                    
                    $options_str .= '<optgroup label="'.$option_v.'" >';
                    
                    foreach($option_t as $option_v2 => $option_t2){
                        
                        $selected_opt = ""; 
                        
                        if($option_v2 == $selected){
                            $selected_opt = "selected";                
                        }
                        $options_str .= '<option '.$selected_opt.' value="'.$option_v2.'" >'.$option_t2.'</option>';
                        
                    }
                    
                    $options_str .= '</optgroup>';
                    
                }
                else{
                    
                    $selected_opt = "";                
                    if($option_v == $selected){
                        $selected_opt = "selected";                
                    }
   
                    if(lang($option_t)){
                       $option_t = lang($option_t);
                    }

                    $options_str .= '<option '.$selected_opt.' value="'.$option_v.'" >'.$option_t.'</option>';
                    
                }
            
            }
            
            return $options_str;
		
	}
}
// --------------------------------------------------------------------

