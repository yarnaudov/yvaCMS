
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconBanners_25.png');?>" >
            <span><?php echo lang('label_banners');?></span>
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
		
	    <button type="submit" name="save"   class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"  class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('banners');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
        
    <?php echo $this->load->view('messages');?>   
    
    <!-- start page content -->
    <div id="page_content" >
	
	<table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?php echo lang('label_main_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?php echo lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?php echo set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>
                            
                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
                    <div class="box" >
	      	        <span class="header" ><?php echo lang('label_advanced');?> <?php echo lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?php echo lang('label_type');?>:</label></th>
                                    <td>
                                        
                                        <?php $type = set_value('type', isset($type) ? $type : ""); ?>
                                        <input type="hidden" class="type" name="type" value="<?php echo $type;?>" >
                                        
                                        <?php if(!empty($type)){ ?>
                                        <strong><?php echo lang('label_'.$type);?></strong> - 
                                        <?php } ?>
                                        
                                        <a href="<?php echo site_url('banners/types');?>"
                                           class = "load_jquery_ui_iframe"
                                           title = "<?php echo lang('label_select').' '.lang('label_type');?>"
                                           lang  = "dialog-select-module-type" ><?php echo lang('label_select');?></a>
                  
                                    </td>
                                </tr>
                                
                                <?php if(!empty($type)){
                                          $this->load->view('banners/'.$type);
                                      } ?>
                                                                                                           
                            </table>
                            
                        </div>
                    </div>
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?php echo set_value('description', isset($description) ? $description : "");?></textarea>
                        </div>
	            </div>
	            
                    <div class="box" >
	      	      <span class="header" ><?php echo lang('label_display_banner_in');?></span>
	      	      
                      <div class="box_content" >
                        
                          <?php $this->load->view('display_in'); ?>
                          
                      </div>
	      	              
                    </div>
                                        
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?php echo lang('label_options');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >
                                
                                <?php $this->load->view('positions'); ?>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?php echo create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                                            
                                <tr>	      			
                                    <th><label><?php echo lang('label_language');?>:</label></th>
                                    <td>
                                        <select name="show_in_language" >
                                            <option value="all" ><?php echo lang('label_all');?></option>
                                            <?php echo create_options('languages', 'id', 'title', set_value('show_in_language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <?php $this->load->view('start_end_dates'); ?>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_show_title');?>:</label></th>
                                    <td>
                                        <select name="show_title" >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('show_title', isset($show_title) ? $show_title : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_css_class_suffix');?>:</label></th>
                                    <td>
                                        <input type="text" name="css_class_sufix" value="<?php echo set_value('css_class_sufix', isset($css_class_sufix) ? $css_class_sufix : "");?>"  >
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
	      	        <span class="header" ><?php echo lang('label_information');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?php echo lang('label_created_by');?>:</label></th>
                                    <td>
                                        <strong><?php echo User::getDetails($created_by, 'user');?></strong>
                                    </td>
                                </tr>                                                       

                                <tr>	      			
                                    <th><label><?php echo lang('label_created_on');?>:</label></th>
                                    <td>
                                        <strong><?php echo $created_on;?></strong>
                                    </td>
                                </tr>
                                
                                <?php if(isset($updated_by)){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_updated_by');?>:</label></th>
                                    <td>
                                        <strong><?php echo isset($updated_by) ? User::getDetails($updated_by, 'user') : "";?></strong>
                                    </td>
                                </tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_updated_on');?>:</label></th>
                                    <td>
                                        <strong><?php echo isset($updated_on) ? $updated_on : "";?></strong>
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
