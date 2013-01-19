<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pulls extends MY_Controller {

    public  $page;
    private $puul_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Pull');
                    
    }
    
    public function vote()
    {
        
        echo "\npull = ".$this->input->post('pull_id')."\n";
        echo "answer = ".$this->input->post('answer_id')."\n";
        
        $pull_id   = $this->input->post('pull_id');
        $answer_id = $this->input->post('answer_id');
        
        setcookie("pulls[".$pull_id."]", $answer_id, time()+60*60*24*30, "/");
        
        $this->Pull->vote($answer_id);
        
    }
    
}


