
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('components/contact_forms/img/iconContact_forms_25.png');?>" >
            <span><?php echo lang('label_contact_forms');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($contact_form_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"                    class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"                   class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('components/contact_forms');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	      	      <span class="header" ><?php echo lang('label_mandatory');?> <?php echo lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?php echo set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_text_above');?></span>
                        <div class="editor_div" >
                          <textarea name="text_above" class="editor"  ><?php echo set_value('text_above', isset($text_above) ? $text_above : "");?></textarea>
                        </div>                        
	            </div>
                    
                    <div class="box" >
	      	        <span class="header" >
			    <?php echo lang('label_form_fields');?>
			    <a id="toggle_fields" href="#" data-show="<?php echo lang('label_show_fields');?>" data-hide="<?php echo lang('label_hide_fields');?>" >
				<?php echo isset($id) ? lang('label_show_fields') : lang('label_hide_fields'); ?>
			    </a>
			</span>
	                
                        <div class="box_content" <?php echo isset($id) ? 'style="display: none"' : ''; ?> >
                            <table class="box_table fields form_fields" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <ul id="form_fields">
                                            <?php $fields = set_value('fields', isset($fields) ? $fields : "");
                                                  
                                                  $count_fields = count($fields)-1;
                                                  $count_fields == 0 ? $count_fields = 1 : '';
                                                  
                                                  for($key = 1; $key <= $count_fields; $key++){ ?>
                                            <li>

                                                <table>
                                                    
                                                    <tr>
                                                        <td colspan="2" >
                                                            <fieldset>
                                                                <legend>
                                                                    <?php echo lang('label_field');?> 
                                                                    <span><?php echo $key;?></span>
                                                                </legend>
                                                                <div class="button" >
                                                                    <img src="<?php echo base_url('img/iconMove.png');?>" class="handle" alt="move" >
                                                                    <a class="styled delete delete_field" title="<?php echo lang('label_delete');?>" >&nbsp;</a>    
                                                                </div>                                                
                                                            </fieldset>
                                                        </td>     
                                                    </tr>    

                                                    <tr>	      			
                                                        <th><label><?php echo lang('label_label');?>:</label></th>
                                                        <td>
                                                            <input type="text" name="fields[<?php echo $key;?>][label]" value="<?php echo set_value('fields['.$key.'][label]', isset($fields[$key]['label']) ? $fields[$key]['label'] : "");?>" >
                                                        </td>
                                                    </tr>

                                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                                    <tr>	      			
                                                        <th><label><?php echo lang('label_type');?>:</label></th>
                                                        <td>
                                                            <?php $type = set_value('fields['.$key.'][type]', isset($fields[$key]['type']) ? $fields[$key]['type'] : ""); ?>
                                                            <select name="fields[<?php echo $key;?>][type]" data-key="<?php echo $key;?>" class="type" >
                                                                <?php echo create_options_array($this->config->item('custom_field_types'), set_value('fields['.$key.'][type]', isset($fields[$key]['type']) ? $fields[$key]['type'] : ""));?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tbody class="params" >
                                                    <?php $type == "" ? $type = "text" : "";
                                                          $action = $type;
                                                          $this->load->view('contact_forms/'.$type, compact('fields', 'key', 'action')); ?>                      
                                                    </tbody>

                                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                                    <tr>	      			
                                                        <th><label><?php echo lang('label_mandatory');?>:</label></th>
                                                        <td>
                                                            <select name="fields[<?php echo $key;?>][mandatory]" >
                                                                <?php echo create_options_array($this->config->item('mandatory_options'), set_value('fields['.$key.'][mandatory]', isset($fields[$key]['mandatory']) ? $fields[$key]['mandatory'] : ""));?>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr><td colspan="2" class="empty_line" >&nbsp;</td></tr>
                                                    
                                                </table>

                                            </li>
                                            <?php } ?>
                                        </ul>
                                        
                                    </td>
                                </tr>
                                
                            </table>
                            
                            <table class="box_table fields" cellpadding="0" cellspacing="0" >
                                    
                                <tr>
                                    <td colspan="2" >
                                        <a class="styled add" id="add_field" ><?php echo lang('label_add');?></a>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" >&nbsp;</td></tr>

                                <tr>
                                    <td colspan="2" >
                                        <fieldset>
                                            <legend>
                                                <?php echo lang('label_captcha');?> Captcha
                                            </legend>                                               
                                        </fieldset>
                                    </td>     
                                </tr>
                                    
                                <tr>	      			
                                    <th><label><?php echo lang('label_captcha');?>Enable:</label></th>
                                    <td>
                                        <select name="fields[captcha][enabled]" >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('fields[captcha][enabled]', isset($fields['captcha']['enabled']) ? $fields['captcha']['enabled'] : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                            
                    </div>
		    
		    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_text_under');?></span>
                        <div class="editor_div" >
                          <textarea name="text_under" class="editor"  ><?php echo set_value('text_under', isset($text_under) ? $text_under : "");?></textarea>
                        </div>                        
	            </div>
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor"  ><?php echo set_value('description', isset($description) ? $description : "");?></textarea>
                        </div>                        
	            </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?php echo lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?php echo create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes'));?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        
	            </div>
                    
	      
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
                                    <th><label><?php echo lang('label_email_to');?>:</label></th>
                                    <td>
                                        <textarea class="required" name="to" ><?php echo set_value('to', isset($to) ? $to : "");?></textarea>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_email_cc');?>:</label></th>
                                    <td>
                                        <textarea name="cc" ><?php echo set_value('cc', isset($cc) ? $cc : "");?></textarea>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_email_bcc');?>:</label></th>
                                    <td>
                                        <textarea name="bcc" ><?php echo set_value('bcc', isset($bcc) ? $bcc : "");?></textarea>
                                    </td>
                                </tr>
				
				<tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label class="multilang" ><?php echo lang('label_msg_success');?>:</label></th>
                                    <td>
                                        <textarea name="msg_success" ><?php echo set_value('msg_success', isset($msg_success) ? $msg_success : "");?></textarea>
                                    </td>
                                </tr>
				
				<tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label class="multilang" ><?php echo lang('label_msg_error');?>:</label></th>
                                    <td>
                                        <textarea name="msg_error" ><?php echo set_value('msg_error', isset($msg_error) ? $msg_error : "");?></textarea>
                                    </td>
                                </tr>
                                
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


