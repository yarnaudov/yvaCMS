<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('alias'))
{
	function alias($string)
	{
            
            $string = trim($string);
            
	    $string = preg_replace("/[^a-z0-9 _]/i", "", $string);
            $string = preg_replace("/[ ]/", "_", $string);
            
            return $string;
		
	}
}

// --------------------------------------------------------------------

