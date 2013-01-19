<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('parceXML'))
{
	function parceXMLfile($file)
	{
            
            $xml_obj = simplexml_load_file($file);                
            $components = json_decode(json_encode($xml_obj), 1);         
            return $components;
		
	}
}

// --------------------------------------------------------------------

