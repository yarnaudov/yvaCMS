<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_google_map extends MY_Model {

    public function save($module_id)
    {
        echo "Save module";

        $markers = $this->input->post('markers');

        $this->db->query('DELETE FROM mod_google_map_markers WHERE module_id = '.$module_id);

        foreach($markers as $marker){

            $marker['module_id'] = $module_id;
            $query = $this->db->insert_string('mod_google_map_markers', $marker);
            $this->db->query($query);

        }

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