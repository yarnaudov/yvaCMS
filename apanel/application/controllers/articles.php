<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends MY_Controller {
    
    public  $extension = 'articles';
    public  $page;
    public  $layout;
    private $article_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Article');
        
        $this->page       = isset($_GET['page']) ? $_GET['page'] : 1;                
        $this->article_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit'){
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");            
                        
            $script = "$('#article_images a.delete').live('click', function(){
                           $(this).parents('li').toggle('slow', function() {
                               $(this).remove();
                           });
                       });
                       $('#article_images').sortable({handle : '.handle'});";
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if ($method == 'add'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique[articles.alias]');
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique_edit[articles.alias.id.'.$this->article_id.']');
                }
            
                if ($this->form_validation->run() == TRUE){
                    
                    $article_id = $this->Article->$method($this->article_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('articles/');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('articles/edit/'.$article_id);
                        exit();
                    }
                    
                }
            }
            
        }        
        
        $this->$method();

    }
    
    public function index()
    {
        
        $this->layout = 'default';
        $redirect     = 'articles';
        
        if($this->uri->segment(3) != ''){
            
            $this->jquery_ext->add_plugin('dialog-select-article');
            $this->jquery_ext->add_plugin('iframe_auto_height');
            $script = "autoHeightIframe('jquery_ui_iframe');";
            
            $script .= "$('table.list tr.row td a').bind('click', function(){
                
                           var article_id = $(this).attr('href').split('/');
                           article_id     = article_id[article_id.length-1];
                           
                           var article_alias = $(this).attr('lang');

                           var article_name = $.trim($(this).html());                          
                           
                           if(parent.$('#article_name').attr('type')){
                               parent.$('#article').val(article_id);
                               parent.$('#article_name').val(article_name);     
                           }
                           else{
                               parent.tinyMCE.execCommand('mceInsertContent', false, '<a href=\"article:'+article_alias+'\" >'+article_name+'</a>');
                           }

                           parent.$( '#jquery_ui' ).dialog( 'close' );
                           
                           return false;
                           
                       });";
            $this->jquery_ext->add_script($script);
            
            $this->layout = $this->uri->segment(3);
            $redirect     = 'articles/index/'.$this->layout;
        }
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Article, 'articles', $redirect);

        // get articles        
        $articles = $this->Article->getArticles($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $articles[0] = $articles;
        }
        else{
          $articles = array_chunk($articles, $data['limit']);
          $data['max_pages'] = count($articles);
        }

        $data['articles']   = count($articles) == 0 ? array() : $articles[($this->page-1)];        
        $data['categories'] = $this->Category->getForDropdown();
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content = $this->load->view('articles/list', $data, true);
        $this->load->view('layouts/'.$this->layout , compact('content'));
        
    }
	
    public function add()
    {   
        
        $categories    = $this->Category->getForDropdown();
        $custom_fields = $this->Custom_field->getCustomFields(array('extension_key' => set_value('category', key($categories)),
                                                                    'status'        => 'yes'));

        $content = $this->load->view('articles/add', compact('categories', 'custom_fields'), true);		
        $this->load->view('layouts/default', compact('content'));
        
    }
	
    public function edit()      
    {
                
        if($this->uri->segment(4) == 'history'){
            $data = $this->Article->getHistoryDetails($this->article_id, urldecode($this->uri->segment(5)) );
            $data['id'] = $data['article_id'];
            $this->language_id  = $data['language_id'];
        }
        else{
            $data = $this->Article->getDetails($this->article_id);
        }
        
        $data['categories'] = $this->Category->getForDropdown();
               
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('category', isset($data['category_id']) ? $data['category_id'] : ""), 
                                                                            'status'        => 'yes'));

        $content["content"] = $this->load->view('articles/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function history()
    {
        
        $this->jquery_ext->add_plugin('iframe_auto_height');
        
        $script = "$('a').live('click', function(event){
                       
                       event.preventDefault();
                       
                       parent.window.location = $(this).attr('href');
                       
                   });";
        
        $this->jquery_ext->add_script($script, 'general');
            
        $history = $this->Article->getHistory($this->article_id);
        
        $content = $this->load->view('articles/history', compact('history'), true);    
        $this->load->view('layouts/simple', compact('content'));
        
    }
    
    public function images()
    {
        
        $this->jquery_ext->add_plugin('iframe_auto_height');
        
        $content = $this->load->view('articles/images', true);
        $this->load->view('layouts/simple', compact('content'));
        
    }
    
    public function statistics()
    {
	
	$this->jquery_ext->add_library("flot/jquery.flot.min.js");
	$this->jquery_ext->add_library("flot/jquery.flot.resize.min.js");
	$this->jquery_ext->add_library("flot/jquery.flot.pie.js");
	
	$script = "p = $.plot($('#plotContainer'), data, {
			series: typeChartSettings,
			shadowSize: 0,
			grid: {
				hoverable: true,
				clickable: true,
				color: '#333333',
				borderWidth: 0.4
			},
			xaxis: {
				alignTicksWithAxis: 1,
				ticks: axisTicks,
				tickSize: 1
			},
			yaxis:{
				min:0
			}

		});
		$('plotContainer').UseTooltip();";
	$this->jquery_ext->add_script($script);
	
	$content = $this->load->view('articles/statistics', '', true);
        $this->load->view('layouts/simple', compact('content'));
	
    }
    
}