<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    
    function __get($key)
    {
        echo "<br/>--->".$key." - get property in the model<br/>";
        //echo "<br/>--->".CI::$APP->$key."<-----------<br/>";

        $CI =& get_instance();

        //print_r($CI);

        return $CI->$key;
    }
    
}