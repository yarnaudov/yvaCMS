
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?=base_url('img/iconMenus_25.png');?>" >
            <span><?=lang('label_menus');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($menu_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"  class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply" class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('menus/');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    
    
    <!-- start messages -->
    <?php $errors = validation_errors();
        if(!empty($errors)){ ?>      
        <div class="error_msg" >
            <ul>
              <?=validation_errors('<li>', '</li>');?>
            </ul>
        </div>
    <?php } ?>

    <?php $good_msg = $this->session->userdata('good_msg');
          $this->session->unset_userdata('good_msg');
          if(!empty($good_msg)){ ?>
          <div class="good_msg" >
              <?=$good_msg;?>            
          </div>
    <?php } ?>

    <?php $error_msg = $this->session->userdata('error_msg');
          $this->session->unset_userdata('error_msg');
          if(!empty($error_msg)){ ?>
          <div class="error_msg" >
              <?=$error_msg;?>            
          </div>
    <?php } ?>
    <!-- end messages -->
    
    
    <!-- start page content -->
    <div id="page_content" >
	
	<table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?=lang('label_main_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?=lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?=lang('label_alias');?>:</label></th>
                                <td><input class="required" type="text" name="alias" value="<?=set_value('alias', isset($alias) ? $alias : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            <div class="box" >
	      	        <span class="header" ><?=lang('label_advanced');?>&nbsp;<?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_type');?>:</label></th>
                                    <td>
                                        
                                        <?php 
                                        
                                        $type = set_value('params[type]', isset($params['type']) ? $params['type'] : "");
                                        
                                        if(preg_match('/^components{1}/', $type)){
                                            
                                            $type_arr = explode('/', $type);
                                            
                                            $options_file = '../../'.$type_arr[0].'/'.$type_arr[1].'/views/apanel_options';
                                            
                                            if(count($type_arr) > 2){                                                
                                                $param      = $type_arr[2];
                                                $type_label = lang('com_'.$type_arr[1]).' > '.lang($this->components[$type_arr[1]]['menus'][$type_arr[2]]);
                                            }
                                            else{                                                
                                                $type_label = lang($this->components[$type_arr[1]]['menus'][$type_arr[1]]);
                                            }
              
                                        }elseif(!empty($type)){ 
                                            $options_file = 'menus/'.$type;
                                            $type_label   = lang('label_'.$type);
                                        } 
                                        ?>

                                        <span id="type_label" >
                                        <?php if(isset($type_label)){ ?>
                                        <strong><?=$type_label;?></strong> -
                                        <?php } ?>
                                        </span>
                                        
                                        <input type="hidden" class="type" name="params[type]" value="<?=$type;?>" >
                                                   
                                        <a href  = "<?=site_url('menus/types');?>"
                                           class = "load_jquery_ui_iframe"
                                           title = "<?=lang('label_select')." ".lang('label_menu')." ".lang('label_type');?>"
                                           lang  = "dialog-select-menu-type" >
                                            <?=lang('label_select');?>
                                        </a>
                                    </td>
                                </tr>
                                
                                <tbody id="menu_options" >
                                <?php if(isset($options_file)){
                                          $this->load->view($options_file, compact('param', isset($param) ? $param : ''));
                                      } ?>
                                </tbody>
                                                                
                            </table>
                            
                        </div>
                    </div>
                    
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset($description) ? $description : "");?></textarea>
                        </div>
                        
                        <div id="menu_description_as_page_title" >
                            <?php $description_as_page_title = set_value('description_as_page_title', isset($description_as_page_title) ? $description_as_page_title : ""); ?>
                            <input type="checkbox" value="yes" name="description_as_page_title" id="description_as_page_title" <?=$description_as_page_title == 'yes' ? 'checked' : ''; ?> >
                            <label for="description_as_page_title" ><?=lang('label_description_as_page_title');?></label>    
                        </div>
                                                
	            </div>
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_metadata');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label class="multilang" ><?=lang('label_keywords');?>:</label></th>
                                    <td>
                                        <textarea name="meta_keywords" ><?=set_value('meta_keywords', isset($meta_keywords) ? $meta_keywords : "");?></textarea> 
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label class="multilang" ><?=lang('label_description');?>:</label></th>
                                    <td>
                                        <textarea name="meta_description" ><?=set_value('meta_description', isset($meta_description) ? $meta_description : "");?></textarea>                                            
                                    </td>
                                </tr>
                                
                            </table>
                                
                        </div>
                    </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <?php $this->load->view('translation'); ?>                    
	      
	            <div class="box" >
	      	        <span class="header" ><?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <?php $this->load->view('categories'); ?>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?=create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_default');?>:</label></th>
                                    <td>
                                        <?php if(!isset($default)){$default = 'no';} ?>
                                        <select name="default" <?=$default == 'yes' ? 'disabled' : '';?> >
                                            <?=create_options_array($this->config->item('yes_no'), set_value('default', isset($default) ? $default : ""));?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_language');?>:</label></th>
                                    <td>
                                        <select name="show_in_language" >
                                            <option value="all" ><?=lang('label_all');?></option>
                                            <?=create_options('languages', 'id', 'title', set_value('show_in_language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_access');?>:</label></th>
                                    <td>
                                        <select name="access" >
                                            <?=create_options_array($this->config->item('accesses'), set_value('access', isset($access) ? $access : "") );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_open_in');?>:</label></th>
                                    <td>
                                        <select name="target" >
                                            <?=create_options_array($this->config->item('menu_targets'), set_value('target', isset($target) ? $target : "") );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_parent');?>:</label></th>
                                    <td>                                      
                                        <select name="parent" class="combobox" >
                                            <option value="none" >- - -</option>
                                            <?=create_options_array($menus, set_value('parent', isset($parent_id) ? $parent_id : "") );?>
                                        </select>
                                    </td>
                                </tr>
                                                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>                               
                                
                                <tr>	      			
                                    <th><label><?=lang('label_image');?>:</label></th>
                                    <td>
                                        <input class="image" type="text" readonly name="image" id="media" value="<?=set_value('image', isset($image) ? $image : "");?>" style="width: 58%">
                                       
                                        <a href="<?=site_url('media/browse');?>" 
                                           class = "load_jquery_ui_iframe"
                                           title="<?=lang('label_browse').' '.lang('label_media');?>"
                                           lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                                         class = "clear_jquery_ui_inputs"
                                                                                                                         lang  = "image" ><?=lang('label_clear');?></a>
                                                                                
                                    </td>
                                </tr>
                                                                
                                <?php $this->load->view('templates', array('name' => 'main_template', 'template' => set_value('main_template', isset($main_template) ? $main_template : ''), 'default' => true)); ?>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_content_template');?>:</label></th>
                                    <td>

                                        <select name="content_template" >
                                            <option value="default" ><?=lang('label_default');?></option>                                            
                                            <?php foreach($this->content_templates as $template_file ){
                                                      echo '<option '.($template_file == set_value('content_template', isset($content_template) ? $content_template : "") ? "selected" : "").' value="'.$template_file.'" >'.$template_file.'</option>';
                                                  } ?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tbody id="custom_fields" >
                                <?php if(count($custom_fields) > 0){
                                          $this->load->view('custom_fields/load_fields');
                                      } ?>
                                </tbody>
                                
                            </table>
                        </div>
                            
                    </div>                  
                    
                    <?php if(isset($created_by)){ ?>
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_information');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_created_by');?>:</label></th>
                                    <td>
                                        <strong><?=User::getDetails($created_by, 'user');?></strong>
                                    </td>
                                </tr>                                                       

                                <tr>	      			
                                    <th><label><?=lang('label_created_on');?>:</label></th>
                                    <td>
                                        <strong><?=$created_on;?></strong>
                                    </td>
                                </tr>
                                
                                <?php if(isset($updated_by)){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_updated_by');?>:</label></th>
                                    <td>
                                        <strong><?=isset($updated_by) ? User::getDetails($updated_by, 'user') : "";?></strong>
                                    </td>
                                </tr>

                                <tr>	      			
                                    <th><label><?=lang('label_updated_on');?>:</label></th>
                                    <td>
                                        <strong><?=isset($updated_on) ? $updated_on : "";?></strong>
                                    </td>
                                </tr>
                                <?php } ?>

                            </table>
                        </div>
                        
                    </div>
                    <?php } ?>
	        	
                </td>
                <!-- end right content  -->
                
            </tr>
            
        </table>
	
    </div>
    <!-- end page content -->

</form>


