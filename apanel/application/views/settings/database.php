
<form name="list" action="<?php echo current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconSettings_25.png');?>" >
            <span><?php echo lang('label_settings');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_database');?></span>
        </div>
	
	<div class="actions" >
	    
            <button type="submit" name="save"     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('settings/');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
        
    </div>
    <!-- end page header -->

    <!-- start page content -->
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <!-- start page content -->

    <?php echo $this->load->view('messages');?>

    <!-- start page content -->
    <div id="page_content" >
		
        <table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" style="padding: 0;" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?php echo lang('label_mail');?> <?php echo lang('label_settings');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?php echo lang('label_host');?>:</label></th>
                                <td><input type="text" name="db_host" value="<?php echo set_value('db_host', DB_HOST);?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_name');?>:</label></th>
                                <td><input type="text" name="db_name" value="<?php echo set_value('db_name', DB_NAME);?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_user');?>:</label></th>
                                <td><input type="text" name="db_user" value="<?php echo set_value('db_user', DB_USER);?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_password');?>:</label></th>
                                <td><input type="text" name="db_pass" value="<?php echo set_value('db_pass', DB_PASS);?>" ></td>
                            </tr>

                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_type');?>:</label></th>
                                <td>
                                    <select name="db_type" style="width:auto;" >
                                        <?php echo create_options_array(array('mysql' => 'mysql', 'mysqli' => 'mysqli'), set_value('db_type', DB_TYPE)); ?>
                                    </select>
                                </td>
                            </tr>
                            
                        </table>
                      </div>
	      	              
                    </div>
                    
                    
                </td>
                
                
            </tr>
            
        </table>
        	        
    </div>
    <!-- end page content -->

</form>
