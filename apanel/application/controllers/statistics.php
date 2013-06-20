<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends MY_Controller {

    public  $extension;
    public  $page; 
    
    function __construct()
    {
  	
        parent::__construct();
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->extension = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        
        if(!method_exists($this, $method)){
            $this->extension = $method;
            $method = 'index';
        }
        
        $this->jquery_ext->add_library("select_active_menu.js");
        $this->$method();

    }
    
    public function index()
    {
        
	if($this->extension == 'articles'){
	    $data = self::_articles();
	}
	elseif($this->extension == 'banners'){
	    $data = self::_banners();
	}
	
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $data['sub_menu'] = $this->Ap_menu->getSubActions($parent_id);
        
	
	// load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");	
	$this->jquery_ext->add_plugin("jqplot");
	
        $content = $this->load->view('statistics/'.$this->extension, $data, true);
        $this->load->view('layouts/default', compact('content'));
        
    }
    
    private function _articles()
    {
	
	$this->load->model('Article');
	
	$data['filters'] = self::_filters();
	
	$articles = $this->Article->getArticles();
	foreach($articles as $article){
	    $data['articles'][$article['id']] = $article['title'];
	}
	
	$statistics = $this->Article->getStatistics($data['filters']);
	$data['statistics'] = array_reverse($statistics);
	
        $data['max_views']   = 0;
        $data['total_views'] = 0;
	$data['line1']       = array();
	
	foreach($statistics as $statistic){
	    
            if($statistic['views'] > $data['max_views']){
                $data['max_views'] = $statistic['views'];
            }
            
            $data['total_views'] += $statistic['views'];
            
	    $data['line1'][] = array($statistic['date'], $statistic['views']);
	    
	}
	
	$data['line1'] = self::_fill_empty_dates($data['filters']['start_date'], $data['filters']['end_date'], $data['line1']);
	
	return $data;
	
    }
    
    private function _banners()
    {
	
	$this->load->model('Banner');
	
	$data['filters'] = self::_filters();
	
	$banners = $this->Banner->getBanners();
	foreach($banners as $banner){
	    $data['banners'][$banner['id']] = $banner['title'];
	}
	
	$statistics = $this->Banner->getStatistics($data['filters']);
	$data['statistics'] = array_reverse($statistics);
	
        $data['max_views']         = 0;
        $data['total_impressions'] = 0;
	$data['total_clicks']      = 0;
	$data['line1'] = $data['line2'] = array();
	
	foreach($statistics as $statistic){
	    
            if($statistic['views'] > $data['max_views']){
                $data['max_views'] = $statistic['views'];
            }
            
	    $data['line1'][] = array($statistic['date'], $statistic['impressions']);
	    $data['total_impressions'] += $statistic['impressions'];

	    $data['line2'][] = array($statistic['date'], $statistic['clicks']);
	    $data['total_clicks'] += $statistic['clicks'];
	    
	}
	
	//print_r($data['line2']);
	
	$data['line1'] = self::_fill_empty_dates($data['filters']['start_date'], $data['filters']['end_date'], $data['line1']);
	$data['line2'] = self::_fill_empty_dates($data['filters']['start_date'], $data['filters']['end_date'], $data['line2']);
	
	return $data;
	
    }
    
    private function _filters()
    {
	
	// set filters
        if(isset($_POST['search'])){
            $filters = array();
            foreach($_POST['filters'] as $name => $value){
                if(!empty($value) && $value != 'none'){
                    $filters[$name] = $value;
                }
            }            
            $this->session->set_userdata('statistics_'.$this->extension.'_filters', $filters);
            redirect('statistics/'.$this->extension);
            exit();
        }
	$filters = $this->session->userdata('statistics_'.$this->extension.'_filters') == '' ? array() : $this->session->userdata('statistics_'.$this->extension.'_filters');
	
	if(!isset($filters['start_date'])){
	    $filters['start_date'] = date('Y-m-d', strtotime("-7 days"));
	    $filters['end_date']   = date('Y-m-d');
	}
	
	return $filters;
	
    }
    
    private function _fill_empty_dates($start_date, $end_date, $data)
    {
	
	while($start_date <= $end_date){
	    
	    $exists = FALSE;
	    foreach($data as $v){
		if(in_array($start_date, $v) == TRUE){
		    $exists = TRUE;
		}
	    }
	    
	    if($exists == FALSE){
		$data[] = array($start_date, 0);
	    }
	    
	    $start_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
	    
	}
	
	return $data;
	
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */