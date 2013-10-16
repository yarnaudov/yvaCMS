<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Model {
    
    public function run($data = array())
    {
	
	$this->load->language('components/search');
	
	$templates = isset($this->Content->templates['search']) ? $this->Content->templates['search'] : array();
	
	$query = $this->input->get('query', TRUE);
	$type = 'article';
	
	if($this->input->get('tag', TRUE)){
	    $query = $this->input->get('tag', TRUE);
	    $type  = 'tag';
	}
	
	$articles = $this->Article->search($query, $type);
		
	$view = 'search';
	if(isset($templates['search'])){
	    $view = $templates['search'];
	}
	
        return $this->load->view($view, compact('query', 'articles'), true);
	
    }
    
}