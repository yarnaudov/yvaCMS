<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {
    
    public function locate($segments) {
                                
        if(!isset($_SESSION['user_id'])){
            $segments[0] = 'home';
            $segments[1] = 'login';
        }
        elseif(isset($_SESSION['no_access_page'])){
            $segments[0] = 'home';
            $segments[1] = 'no_access';
        }
        
        
        return parent::locate($segments);
        
    }
}