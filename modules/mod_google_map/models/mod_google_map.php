<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_google_map extends CI_Model{
	
    function run($module)
    {
	
	$data['id']     = $module['id'];
        $data['params'] = $module['params'];
        $data['params']['markers'] = self::getMarkers($module['id']);

        return module::loadContent($module, $data);

    }

    function getMarkers($module_id)
    {

        $query = "SELECT 
                        *
                    FROM
                        mod_google_map_markers
                    WHERE
                        module_id = ".$module_id;
         
        //echo $query."<br/>"

        $markers = $this->db->query($query)->result_array();

        return $markers;

    }
    
}