
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconLanguages_25.png');?>" >
            <span><?php echo lang('label_languages');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($language_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('languages');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?php echo lang('label_abbreviation');?>:</label></th>
                                <td><input class="required" type="text" name="abbreviation" value="<?php echo set_value('abbreviation', isset($abbreviation) ? $abbreviation : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?php echo set_value('description', isset($description) ? $description : "");?></textarea>
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
                                    <th><label><?php echo lang('label_default');?>:</label></th>
                                    <td>
                                        <?php if(!isset($default)){$default = 'no';} ?>
                                        <select name="default" <?php echo $default == 'yes' ? 'disabled' : '';?> >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('default', isset($default) ? $default : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>                               
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_image');?>:</label></th>
                                    <td>
                                        <input class="image" type="text" readonly name="image" id="language_image" value="<?php echo set_value('image', isset($image) ? $image : "");?>" style="width: 58%">
                                       
                                        <a href="<?php echo site_url('media/browse');?>" 
                                           class = "load_jquery_ui_iframe"
                                           title="<?php echo lang('label_browse').' '.lang('label_media');?>"
                                           lang  = "dialog-media-browser"
					   target = "language_image" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                                    class = "clear_jquery_ui_inputs"
                                                                                                                    lang  = "image" ><?php echo lang('label_clear');?></a>
                                                                                
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
