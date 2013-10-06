<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_advanced_html extends CI_Model{
	
    function run($module)
    {

	$data = array();
	
	if(!file_exists(FCPATH . $module['params']['source_file'])){
	    $data['error'] = "Settings file \"". FCPATH . $module['params']['source_file']."\" not found";
	}
	else{
           
	    switch($module['params']['source_format']){

		case "php":
		    $data['data'] = include FCPATH . $module['params']['source_file'];
		break;

		case "json":
		    $data['data'] = file_get_contents(FCPATH . $module['params']['source_file']);
		    $data['data'] = json_decode($data['data'], true);
		break;

		case "ini":
		    $data['data'] = parse_ini_file(FCPATH . $module['params']['source_file'], true);
		break;
            
                case "xml":
                    $xml = file_get_contents(FCPATH . $module['params']['source_file']);
		    $data['data'] = new SimpleXMLElement($xml);
		break;

	    }
	    
	}

        return module::loadContent($module, $data);

    }
    
}

