<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller {

    public  $extension = 'menus';
    public  $model     = 'Menu';
    public  $element_id;
    public  $page;
    private $menu_id;
    
    public  $templates;
    public  $content_templates;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Menu');
        
        $this->page    = isset($_GET['page']) ? $_GET['page'] : 1;        
        $this->menu_id = $this->element_id = $this->uri->segment(3);
        
        /*
         * load modules languages
         */
        foreach($this->components as $component => $data){
           $this->_loadComponetLanguages($component);                                                                   
        }
        
        $this->templates = parent::_getTemplates();
        $this->content_templates = parent::_getTemplates('content');
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {   
            
            $this->load->model('Article');
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");  
                                 
            $script = "$('select[name=category]').bind('change', function(){
                
                           var src = '".site_url('home/ajax/get_menus')."?menu=".$this->menu_id."&category='+$(this).val();
                           $.get(src, function(data){
                           
                               data = JSON.parse(data);
                               
                               $('select[name=parent] option').each(function(index){
                                   if(index > 0){
                                       $(this).remove();
                                   }
                               });

                               $(data).each(function(index){
                                   $('select[name=parent]').append(new Option(this['text'], this['value']));
                               });

                           });

                       }); ";
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');          
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if($method == 'add'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique[menus.alias]|callback_alias_check_forbiden');
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique_edit[menus.alias.id.'.$this->menu_id.']|callback_alias_check_forbiden');
                }
                
                if ($this->form_validation->run() == TRUE){
                    
                    $menu_id = $this->Menu->$method($this->menu_id);
                    
                    if(isset($_POST['save'])){
                        redirect('menus/');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('menus/edit/'.$menu_id);
                        exit();
                    }
                    
                }
            }
            
        }
        
        $this->$method();

    }

    public function index()
    {
        
        $script ="$('.default').hover(
                      function(){
                      
                          $(this).append('<img src=\"".base_url('img/iconStar16.png')."\" title=\"".lang('label_make_default')."\" style=\"cursor: pointer;\" >');
                          
                          $(this).find('img').click(function(){
                          
                              var element_id = $(this).parents('tr').find('.checkbox').val();

                              $('form').append('<input type=\"hidden\" name=\"element_id\" value=\"'+element_id+'\" >');
                              $('form').append('<input type=\"hidden\" name=\"make_default\" value=\"yes\" >');
                              $('form').submit();
                              
                          });
                          
                      },
                      function(){
                          $(this).find('img').remove();
                      }
                  );";
        $this->jquery_ext->add_script($script);
        
        // make default
        if(isset($_POST['make_default'])){
            $result = $this->Menu->makeDefault($_POST['element_id']);
            if($result == true){
                $page = '';
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('menus'.$page);
                exit();
            }
        }
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Menu, 'menus', 'menus');
                
        // get menus     
        $menus = $this->Menu->getMenus($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $menus[0] = $menus;
        }
        else{
          $menus = array_chunk($menus, $data['limit']);
          $data['max_pages'] = count($menus);
        }

        $data['menus']      = count($menus) == 0 ? array() : $menus[($this->page-1)];  
        $data['categories'] = $this->Category->getForDropdown();
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);       
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('menus/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {           
        
        $data['categories']    = $this->Category->getForDropdown();
        $data['menus']         = $this->Menu->getForDropdown(array('category_id' => set_value('category', key($data['categories']))));
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('category', key($data['categories'])),
                                                                            'status'        => 'yes'));
        
        $content = $this->load->view('menus/add', $data, true);		
        $this->load->view('layouts/default', compact('content'));
        
    }
	
    public function edit()      
    {
        
        $data                  = $this->Menu->getDetails($this->menu_id);   
        $data['categories']    = $this->Category->getForDropdown();
        $data['menus']         = $this->Menu->getForDropdown(array('id' => '!'.$this->menu_id, 'category_id' => set_value('category', isset($data['category_id']) ? $data['category_id'] : "")));
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('category', isset($data['category_id']) ? $data['category_id'] : ""), 
                                                                            'status'        => 'yes'));
        
        //print_r($data);

        $content["content"] = $this->load->view('menus/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

    public function types()
    {
        
        $script = "$('a.type').live('click', function(event){
            
                       event.preventDefault();
                       
                       parent.$('input.type').val($(this).attr('href'));

                       $.get('".site_url('home/ajax/load_menu_type')."?type='+$(this).attr('href'), function(data){                            
                            parent.$('#type_label').html($(data, '#type_label').html());
                            parent.$('#menu_options').css('display', 'none');
			    parent.document.getElementById('menu_options').innerHTML = data;
                            parent.$('#menu_options').find('#type_label').remove();
                            parent.$('#menu_options').toggle('slow');
                            parent.$( '#jquery_ui' ).dialog( 'close' );
                       });
                                              
                   });";
        $this->jquery_ext->add_script($script, 'general');
        
        foreach($this->components as $component => $data){
            parent::_loadComponetLanguages($component);
        }
        
        $data['menus'] = $this->config->item('menu_types');
        
        $content["content"] = $this->load->view('menus/types', $data, true);
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
    public function alias_check_forbiden($alias)
    {
	
	if($alias == 'search' || $alias == 'ajax' || $alias == 'load'){
	    $this->form_validation->set_message('alias_check_forbiden', lang('alias_check_forbiden'));
	    return FALSE;
	}
	
	return TRUE;
	
    }
    
}