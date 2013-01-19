<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_value'))
{
    
    function set_value($field = '', $default = '') //params[field1][label]
    {
        /*
        $fields = explode('[', $field);
        
        if(count($fields) > 1){
            $_post = $_POST;
            
            foreach($fields as $field){

                $field = str_replace(']', '', $field);   

                if(isset($_post[$field])){                    
                    $_post = $_post[$field];
                    $post  = $_post;
                }
                else{
                    break;
                }

            }

        }
        elseif(isset($_POST[$field])){
            $post = $_POST[$field];
        }
        */
        
        if(preg_match('/\[{1}/', $field)){

            $posts = explode('[', $field);
            $field1 = $posts[0];
            $field2 = str_replace(']', '', $posts[1]);
            if(isset($posts[2])){
                $field3 = str_replace(']', '', $posts[2]);
                if(isset($_POST[$field1][$field2][$field3])){
                    $post = $_POST[$field1][$field2][$field3];
                }
            }            
            elseif(isset($_POST[$field1][$field2])){
                $post = $_POST[$field1][$field2];
            }
        }
        elseif(isset($_POST[$field])){
            $post = $_POST[$field];
        }
        
        if (FALSE === ($OBJ =& _get_validation_object()))
        {
            if(isset($post))
            {
                return form_prep($post, $field);
                
            }

            return $default;
        }
        
        if(isset($post))
        {
            $default = $post;  
        }
        
        return form_prep($OBJ->set_value($field, $default), $field);
    }
    
}