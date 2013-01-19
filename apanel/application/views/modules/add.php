
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?=base_url('img/iconModules_25.png');?>" >
            <span><?=lang('label_modules');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($module_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"    class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"   class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('modules/');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
	      	      <span class="header" ><?=lang('label_mandatory');?> <?=lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?=lang('label_title');?>:</label></th>
                                <td><input type="text" name="title" value="<?=set_value('title', isset(${'title_'.$this->trl}) ? ${'title_'.$this->trl} : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset(${'description_'.$this->trl}) ? ${'description_'.$this->trl} : "");?></textarea>
                        </div>
	            </div>
                    
                    <div class="box" >
	      	      <span class="header" ><?=lang('label_display_module_in');?></span>
	      	      
                      <div class="box_content" >
                        
                          <select name="display_in" style="width: 280px;">
                              <?=create_options_array($this->config->item('module_display'), set_value('display_in', isset($display_in) ? $display_in : "") );?>
                          </select>
                          <button class="styled toggle" type="button" >Toggle selection</button>
                          
                          <div id="tabs">

                              <?php 
                              $menus_by_category = $this->Menu->getMenusByCategory(array(), "`order`");
                              //print_r($menus);
                              //$menus = array();
                              $tabs     = "";
                              $contents = "";
                              $tab_numb = 0;
                              foreach($menus_by_category as $category => $menus_arr){
                                  
                                  $tab_numb++;
                                  
                                  $tabs .= "<li>";
                                  $tabs .= "	<a href=\"#tabs-".$tab_numb."\" >".$category."</a>";
                                  $tabs .= "</li>";

                                  $contents .= "<div id=\"tabs-".$tab_numb."\">";
                                  
                                  $display_menus = set_value('params[display_menus]', isset($params['display_menus']) ? $params['display_menus'] : "");
                                  
                                  foreach($menus_arr as $menu_id => $menu){

                                      $checked = "";
                                      if(@in_array($menu_id, $display_menus)){
                                          $checked = "checked";
                                      }           
                                      
                                      $contents .= "<div>";
                                      $contents .= " <input ".$checked." style=\"width: 15px;\" type=\"checkbox\" class=\"display_menus\" id=\"display_menu".$menu_id."\" name=\"params[display_menus][]\" value=\"".$menu_id."\" >";
                                      $contents .= " <label for=\"display_menu".$menu_id."\" >".$menu."</label>";
                                      $contents .= "</div>";

                                  }

                                  $contents .= "</div>";

                            } 
                            ?>    	  	

                            <ul>
                                    <?php echo $tabs; ?>
                            </ul>
                            <?php echo $contents; ?>

                        </div>
                          
                      </div>
	      	              
                    </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?=lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?=create_options('languages', 'abbreviation', 'title', $this->trl, array('status' => 'yes'));?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        
	            </div>
                    
	      
	            <div class="box" >
	      	        <span class="header" ><?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_category');?>:</label></th>
                                    <td>
                                        <select name="category" >
                                            <?=create_options('categories', 'category_id', 'title_'.Language::getDefault(), set_value('category', isset($category_id) ? $category_id : ""), array('extension' => 'modules', 'status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>

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
                                    <th><label><?=lang('label_language');?>:</label></th>
                                    <td>
                                        <select name="language" >
                                            <option value="all" ><?=lang('label_all');?></option>
                                            <?=create_options('languages', 'language_id', 'title', set_value('language', isset($language_id) ? $language_id : ""), array('status' => 'yes') );?>
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
                                    <th><label><?=lang('label_start_date');?>:</label></th>
                                    <td>
                                        <input type="text" class="datepicker" name="start_publishing" value="<?=set_value('start_publishing', isset($start_publishing) ? $start_publishing : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_end_date');?>:</label></th>
                                    <td>
                                        <input type="text" class="datepicker" name="end_publishing" value="<?=set_value('end_publishing', isset($end_publishing) ? $end_publishing : "");?>" >
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_display');?>&nbsp;<?=lang('label_title');?>:</label></th>
                                    <td>
                                        <?php if(!isset($show_title)){$show_title = 'yes';} ?>
                                        <select name="show_title" >
                                            <?=create_options_array($this->config->item('yes_no'), set_value('show_title', isset($show_title) ? $show_title : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_css_class_suffix');?>:</label></th>
                                    <td>
                                        <input type="text" name="params[css_class]" value="<?=set_value('params[css_class]', isset($params['css_class']) ? $params['css_class'] : "");?>"  >
                                    </td>
                                </tr>
                                
                                <?php if(count($custom_fields) > 0){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                <tr>
                                    <td colspan="2" class="empty_line" >
                                        <fieldset style="border:none;border-top: 1px solid #aaa;padding-left: 10px;">
                                            <legend style="font-weight: bold;padding: 0 5px;" ><?=lang('label_custom_fields');?></legend>
                                        </fieldset>
                                    </td>
                                </tr>
                                
                                <?php $this->load->view('custom_fields/load_fields'); ?>
                                
                                <?php } ?>
  
                            </table>
                        </div>
                            
                    </div>
                    
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_advanced');?> <?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_type');?>:</label></th>
                                    <td>
                                        <?php $type = set_value('type', isset($type) ? $type : "");
                                              if(!empty($type)){
                                              	  $this->load->language($type);
                                              } ?>
                                        <input type="hidden" name="type" value="<?=$type;?>" >
                                        
                                        <?php if(!empty($type)){ ?>
                                        <strong><?=lang('label_'.$type);?></strong> - 
                                        <?php } ?>
                                        
                                        <a href  = "<?=site_url('articles');?>" 
                                           class = "load_jquery_ui_iframe" 
                                           lang  = "dialog-select-module-type" ><?=lang('label_select');?></a>
                                        
                                        <!-- start jquery UI -->
                                        <div id    = "dialog-select-module-type"
                                             class = "jquery_ui_iframe"
                                             title = "<?=lang('label_select').' '.lang('label_type');?>" 
                                             lang  = "<?=site_url('modules/types');?>" ></div>
                                    </td>
                                </tr>
                                
                                <?php if(file_exists('modules/' . $type . '/views/apanel_options.php')){
                                	      include_once 'modules/' . $type . '/views/apanel_options.php';
                                      } ?>
                               
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


