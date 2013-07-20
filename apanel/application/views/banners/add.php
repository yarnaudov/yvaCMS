
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconBanners_25.png');?>" >
            <span><?=lang('label_banners');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($banner_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"   class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"  class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('banners');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
        
    <?=$this->load->view('messages');?>   
    
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
                                <th><label><?=lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>
                            
                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_advanced');?> <?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_type');?>:</label></th>
                                    <td>
                                        
                                        <?php $type = set_value('type', isset($type) ? $type : ""); ?>
                                        <input type="hidden" class="type" name="type" value="<?=$type;?>" >
                                        
                                        <?php if(!empty($type)){ ?>
                                        <strong><?=lang('label_'.$type);?></strong> - 
                                        <?php } ?>
                                        
                                        <a href="<?=site_url('banners/types');?>"
                                           class = "load_jquery_ui_iframe"
                                           title = "<?=lang('label_select').' '.lang('label_type');?>"
                                           lang  = "dialog-select-module-type" ><?=lang('label_select');?></a>
                  
                                    </td>
                                </tr>
                                
                                <?php if(!empty($type)){
                                          $this->load->view('banners/'.$type);
                                      } ?>
                                                                                                           
                            </table>
                            
                        </div>
                    </div>
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset($description) ? $description : "");?></textarea>
                        </div>
	            </div>
	            
                    <div class="box" >
	      	      <span class="header" ><?=lang('label_display_banner_in');?></span>
	      	      
                      <div class="box_content" >
                        
                          <?php $this->load->view('display_in'); ?>
                          
                      </div>
	      	              
                    </div>
                                        
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?=lang('label_options');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >
                                
                                <?php $this->load->view('positions'); ?>
                                
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
                                        <select name="show_in_language" >
                                            <option value="all" ><?=lang('label_all');?></option>
                                            <?=create_options('languages', 'id', 'title', set_value('show_in_language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <?php $this->load->view('start_end_dates'); ?>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_show_title');?>:</label></th>
                                    <td>
                                        <select name="show_title" >
                                            <?=create_options_array($this->config->item('yes_no'), set_value('show_title', isset($show_title) ? $show_title : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_css_class_suffix');?>:</label></th>
                                    <td>
                                        <input type="text" name="css_class_sufix" value="<?=set_value('css_class_sufix', isset($css_class_sufix) ? $css_class_sufix : "");?>"  >
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
                       
                    <?php $this->load->view('display_rules'); ?>
                    
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
