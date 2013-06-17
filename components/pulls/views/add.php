
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('components/pulls/img/iconPull_25.png');?>" >
            <span><?=lang('label_pulls');?></span>
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
		
	    <button type="submit" name="save"  class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply" class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('pulls');?>"  class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                                <th><label><?=lang('label_title');?>:</label></th>
                                <td><input type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor"  ><?=set_value('description', isset($description) ? $description : "");?></textarea>
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
                                
                            </table>
                        </div>
                            
                    </div>
                    
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_answers');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table fields form_fields" cellpadding="0" cellspacing="0" >

                                <?php $answers = set_value('answers', isset($answers) ? $answers : "");
                                      //print_r($answers);
                                      $answers[0] = array();
                                      ksort($answers);
                                      foreach($answers as $number => $answer){ ?>
                                <tbody id="field<?=$number;?>" <?=$number == 0 ? 'style="display: none;"' : '';?> >
                                             
                                    <tr>
                                        <td colspan="2" >
                                            <fieldset>
                                                <legend>
                                                    <?=lang('label_answer');?> 
                                                    <span><?=$number;?></span>
                                                </legend>
                                                <div class="button" >
                                                    <a class="styled delete" lang="field<?=$number;?>" title="<?=lang('label_delete');?>" >x</a>    
                                                </div>                                                
                                            </fieldset>
                                        </td>     
                                    </tr>

                                    <tr>	      			
                                        <th><label><?=lang('label_text');?>:</label></th>
                                        <td>
                                        	  <input type="text" name="answers[<?=$number;?>][title]" value="<?=set_value('answers['.$number.'][title]', isset($answer['title']) ? $answer['title'] : "");?>" >
                                        </td>
                                    </tr>
                                    
                                    <tr><td colspan="2" class="empty_line" >&nbsp;</td></tr>
                                    
                                    <tr>	      			
                                        <th><label><?=lang('label_status');?>:</label></th>
		                                    <td>
		                                        <select name="answers[<?=$number;?>][status]" >
		                                            <?=create_options_array($this->config->item('statuses'), set_value('answers['.$number.'][status]', isset($answer['status']) ? $answer['status'] : ""));?>
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


