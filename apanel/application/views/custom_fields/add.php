
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <?php if(lang('label_'.$this->extension)){ ?>
            <img src="<?php echo base_url('img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?php echo lang('label_'.$this->extension);?></span>
            <?php }else{ 
                    $this->load->language($this->extension.'/com_gallery_labels'); ?>
            <img src="<?php echo base_url('components/'.$this->extension.'/img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?php echo lang('com_'.$this->extension.'_label_'.$this->extension);?></span>
            <?php } ?>
            
            <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_custom_fields');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"                           class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"                          class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('custom_fields/'.$this->extension);?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	            
                    <!-- required information  -->
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
	            <!-- required information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header" ><?php echo lang('label_description');?></span>
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

                                <?php if(isset($extension_keys_arr)){ ?>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_'.$extension_keys_label);?>:</label></th>
                                    <td>
					
					<div class="menu_list" >
					    <table class="menu_list" cellpadding="0" cellspacing="0" >
						
						<?php $extension_keys = set_value('extension_keys', isset($extension_keys) ? $extension_keys : "");
						      foreach($extension_keys_arr as $extension_id => $extension){ 
							  $checked = "";
							  if(@in_array($extension_id, $extension_keys)){
							      $checked = "checked";
							  } ?>

						<tr>
						    <td style="width: 1%;" >
							<input type="checkbox" <?php echo $checked;?> name="extension_keys[]" id="custom_menu<?php echo $extension_id;?>" value="<?php echo $extension_id;?>" >
						    </td>
						    <td>
							<label for="custom_menu<?php echo $extension_id;?>" ><?php echo $extension;?></label>
						    </td>
						</tr>

						<?php } ?>

					    </table>
					</div>
					
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <?php }else{ ?>
                                
                                <tr><td colspan="2" ><input type="hidden" name="extension_key" value="all" ></td></tr>
                                                                
                                <?php } ?>
                                
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
                                    <th><label><?php echo lang('label_multilang');?>:</label></th>
                                    <td>
                                        <select name="multilang" >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('multilang', isset($multilang) ? $multilang : "no"));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_required');?>:</label></th>
                                    <td>
                                        <?php if(!isset($required)){$required = 'no';} ?>
                                        <select name="mandatory" >
                                            <?php echo create_options_array($this->config->item('mandatory_options'), set_value('required', isset($required) ? $required : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>
                                    <th><label><?php echo lang('label_type');?>:</label></th>
                                    <td>
                                        <?php $type = set_value('type', isset($type) ? $type : ""); ?>
                                        <select name="type" >
                                            <?php echo create_options_array($this->config->item('custom_field_types'), $type);?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tbody id="params" >
                                <?php $type == "" ? $type = "text" : "";
                                      $this->load->view('custom_fields/'.$type); ?>                      
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


