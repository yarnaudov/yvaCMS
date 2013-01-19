<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Model {

    public $data;
    
    function __construct()
    {
        
        parent::__construct();
                
        if(isset($_POST['search'])){
           self::_search();           
        }
        
    }
    
    function _search()
    {
        $this->data['search_v'] = $this->input->post('search_v');
                
        $this->data['articles'] = $this->Article->search($this->input->post('search_v'));

        //print_r($articles);
        
    }
    
}