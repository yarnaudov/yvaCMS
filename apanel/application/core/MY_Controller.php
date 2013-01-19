<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    
    public $access;
    
    public function __construct()
    {

        parent::__construct();
        
        $this->load->library('Lang_lib');
        
        $this->load->model('Adm_menu');
         
        if(!isset($_SESSION['user_id'])){            
            
            if(preg_match('/_ajax{1}$/', current_url())){                
                $this->jquery_ext->add_script("parent.location.reload();");
            }
            
        }
        else{
        	  
            $group_id = $this->User->getDetails('', 'group_id');
            $access   = $this->Group->getDetails($group_id, 'access');
        	  
            if($access == '*'){        	  	
                $this->access = $this->Adm_menu->getAllMenus();
            }
            else{        	  
              $this->access = json_decode($access, true);
            }
            
        }
        
        $this->Adm_menu->setConfig();
        
        /*
         * Check access to pages. If user does not have access redirect to no_access page
         */
        $segment1 = $this->uri->segment(1);
        $segment2 = $this->uri->segment(2);
        $segment3 = $this->uri->segment(3);
        $segment4 = $this->uri->segment(4);
        
        $url = '';
        if(!empty($segment1)){
            $url = $segment1;
        }
                      
        if($segment2 == 'add' && !empty($segment3)){
           $url = $url.'/'.$segment3; 
        }
        elseif($segment2 == 'edit' && !empty($segment4) && $segment4 != 'history'){
           $url = $url.'/'.$segment4; 
        }
        elseif(!empty($segment2) && $segment2 != 'add' && $segment2 != 'edit' && $segment2 != 'index'){
            $url = $url.'/'.$segment2;
        }
        
        if(@$this->access[$url] != 'on' && !in_array($url, $this->config->item('no_login')) && isset($_SESSION['user_id']) && !isset($_SESSION['no_access_page'])){
           $_SESSION['no_access_page'] = $_SERVER['REQUEST_URI'];
           $this->session->set_userdata('no_access_page', $_SERVER['REQUEST_URI']);
           redirect(current_url());
           exit;
        }
        else{
            unset($_SESSION['no_access_page']);
        }
        
        
        $this->jquery_ext->add_library("jquery/plugins/jquery.fixFloat.js");            
        $script = "fixFloat($('#page_header'), 'page_header_clone');";
            
        $script .= "$('.multilang').attr('title', '".lang('msg_multilang_info')."');";
        
        /*
         * disable components menu
         */
        $script .= "$('#menu ul li a').each(function(){
                        var url = $(this).attr('href').split('/');
                        if(url[url.length-1] == 'components'){
                            $(this).click(function(){return false;});
                        }
                    });";
        
        /*
         * add class current to main menu when sum menu is focusted
         */
        $script .= "$('#menu ul li ul').hover(
                        function () {
                            $(this).parent().addClass('current2');
                        },
                        function () {
                            $(this).parent().removeClass('current2');
                        }
                    );";
        
        if(preg_match('/_ajax{1}$/', current_url())){ 
            $script .= "$('#main').css('padding', 0);";
        }
                
        $this->jquery_ext->add_script($script);
               
    }

}
