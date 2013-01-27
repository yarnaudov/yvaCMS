
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('components/contact_forms/img/iconContact_forms_25.png');?>" >
            <span><?=lang('label_contact_forms');?></span>
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
		
	    <button type="submit" name="save"                    class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"                   class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('components/contact_forms');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                          <textarea name="description" class="editor"  ><?=set_value('description', isset(${'description_'.$this->trl}) ? ${'description_'.$this->trl} : "");?></textarea>
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
                                            <?=create_options('languages', 'id', 'title', $this->trl, array('status' => 'yes'));?>
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
                                    <th><label><?=lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?=create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
                                    </td>
                                </tr>
  
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_email_to');?>:</label></th>
                                    <td>
                                        <textarea name="to" ><?=set_value('to', isset($to) ? $to : "");?></textarea>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_email_cc');?>:</label></th>
                                    <td>
                                        <textarea name="cc" ><?=set_value('cc', isset($cc) ? $cc : "");?></textarea>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_email_bcc');?>:</label></th>
                                    <td>
                                        <textarea name="bcc" ><?=set_value('bcc', isset($bcc) ? $bcc : "");?></textarea>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                            
                    </div>
                    
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_form_fields');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table fields form_fields" cellpadding="0" cellspacing="0" >

                                <?php $fields = set_value('fields', isset($fields) ? $fields : "");
                                      $fields[0] = array();
                                      ksort($fields);
                                      foreach($fields as $number => $field){ ?>
                                <tbody id="field<?=$number;?>" <?=$number == 0 ? 'style="display: none;"' : '';?> >
                                             
                                    <tr>
                                        <td colspan="2" >
                                            <fieldset>
                                                <legend>
                                                    <?=lang('label_field');?> 
                                                    <span><?=$number;?></span>
                                                </legend>
                                                <div class="button" >
                                                    <a class="styled delete" lang="field<?=$number;?>" title="<?=lang('label_delete');?>" >&nbsp;</a>    
                                                </div>                                                
                                            </fieldset>
                                        </td>     
                                    </tr>    



                                    <tr>	      			
                                        <th><label><?=lang('label_type');?>:</label></th>
                                        <td>
                                            <select name="fields[<?=$number;?>][type]" >
                                                <?=create_options_array($this->config->item('custom_field_types'), set_value('fields['.$number.'][type]', isset($fields[$number]['type']) ? $fields[$number]['type'] : ""));?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>	      			
                                        <th><label class="multilang" ><?=lang('label_label');?>:</label></th>
                                        <td>
                                            <input type="text" name="fields[<?=$number;?>][label_<?=$this->trl;?>]" value="<?=set_value('fields['.$number.'][label_'.$this->trl.']', isset($fields[$number]['label_'.$this->trl]) ? $fields[$number]['label_'.$this->trl] : "");?>" >
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>	      			
                                        <th><label class="multilang" ><?=lang('label_value');?>:</label></th>
                                        <td>
                                            <textarea name="fields[<?=$number;?>][value_<?=$this->trl;?>]" ><?=set_value('fields['.$number.'][value_'.$this->trl.']', isset($fields[$number]['value_'.$this->trl]) ? $fields[$number]['value_'.$this->trl] : "");?></textarea>
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>	      			
                                        <th><label><?=lang('label_mandatory');?>:</label></th>
                                        <td>
                                            <select name="fields[<?=$number;?>][mandatory]" >
                                                <?=create_options_array($this->config->item('mandatory_options'), set_value('fields['.$number.'][mandatory]', isset($fields[$number]['mandatory']) ? $fields[$number]['mandatory'] : ""));?>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr><td colspan="2" class="empty_line" >&nbsp;</td></tr>
                                
                                </tbody>
                                <?php } ?>
                                
                            </table>
                            
                            <table class="box_table fields" cellpadding="0" cellspacing="0" >
                                    
                                <tr>
                                    <td colspan="2" >
                                        <a class="styled add" ><?=lang('label_add');?></a>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" >&nbsp;</td></tr>

                                <tr>
                                    <td colspan="2" >
                                        <fieldset>
                                            <legend>
                                                <?=lang('label_captcha');?> Captcha
                                            </legend>                                               
                                        </fieldset>
                                    </td>     
                                </tr>
                                    
                                <tr>	      			
                                    <th><label><?=lang('label_captcha');?>Enable:</label></th>
                                    <td>
                                        <select name="fields[captcha][enabled]" >
                                            <?=create_options_array($this->config->item('yes_no'), set_value('fields[captcha][enabled]', isset($fields['captcha']['enabled']) ? $fields['captcha']['enabled'] : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
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


