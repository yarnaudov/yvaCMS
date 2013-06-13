
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconLanguages_25.png');?>" >
            <span><?=lang('label_languages');?></span>
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
		
	    <button type="submit" name="save"     class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('languages');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                                <th><label><?=lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?=lang('label_abbreviation');?>:</label></th>
                                <td><input class="required" type="text" name="abbreviation" value="<?=set_value('abbreviation', isset($abbreviation) ? $abbreviation : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset($description) ? $description : "");?></textarea>
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
                                    <th><label><?=lang('label_image');?>:</label></th>
                                    <td>
                                        <input class="image" type="text" readonly name="image" id="language_image" value="<?=set_value('image', isset($image) ? $image : "");?>" style="width: 58%">
                                       
                                        <a href="<?=site_url('media/browse');?>" 
                                           class = "load_jquery_ui_iframe"
                                           title="<?=lang('label_browse').' '.lang('label_media');?>"
                                           lang  = "dialog-media-browser"
					   target = "language_image" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                                    class = "clear_jquery_ui_inputs"
                                                                                                                    lang  = "image" ><?=lang('label_clear');?></a>
                                                                                
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
