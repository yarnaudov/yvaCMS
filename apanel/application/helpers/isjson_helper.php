<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('isJson'))
{
	function isJson($string)
	{
            
            return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
		
	}
}

// --------------------------------------------------------------------

