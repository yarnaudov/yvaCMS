<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polls extends MY_Controller {

    public  $page;
    private $puul_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Poll');
                    
    }
    
    public function vote()
    {
        
        echo "\npoll = ".$this->input->post('poll_id')."\n";
        echo "answer = ".$this->input->post('answer_id')."\n";
        
        $poll_id   = $this->input->post('poll_id');
        $answer_id = $this->input->post('answer_id');
        
        setcookie("polls[".$poll_id."]", $answer_id, time()+60*60*24*30, "/");
        
        $this->Poll->vote($answer_id);
        
    }
    
}


