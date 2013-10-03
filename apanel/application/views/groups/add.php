
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconUsers_25.png');?>" >
            <span><?php echo lang('label_users');?></span>
            <span>&nbsp;»&nbsp;</span>
            <!--
	    <img src="<?php echo base_url('img/iconCategories.png');?>" >
            -->
            <span><?php echo lang('label_groups');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($user_group_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"        class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"       class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('groups/users');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
                        <span class="header" ><?php echo lang('label_access');?></span>
                        
                        <div class="box_content" >
                            
                            <?php if($access == '*'){ ?>
                            
                            <div><?php echo lang('msg_group_super_admin');?>By default this group has full rights</div>
                            <input type="hidden" name="no_access" value="true" >
                            
                            <?php }else{ ?>
                                                        
                            <div class="menu_list" >
                                                                
                                <table  class="box_table" cellpadding="0" cellspacing="0" >
                                <?php
                                
                                $access        = @json_decode($access, true);                                
                                $user_group_id = $this->User->getDetails('', 'user_group_id');
                                $group_access  = $this->User_group->getDetails($user_group_id, 'access');

                                $no_access_flag = false;
                                if( (count(@array_diff_key($access, $this->access)) > 0 && $group_access != '*') || $user_group_id == $this->uri->segment(3)){
                                	
                                    echo '<tr>';
                                    echo '  <td>';
                                    echo      lang('msg_group_no_access');
                                    echo '    <input type="hidden" name="no_access" value="true" >';
                                    echo '  </td>';
                                    echo '<tr>';
                                    
                                    echo '<tr><td>&nbsp;</td></tr>';
                                    
                                    $no_access_flag = true;
                                    
                                }

                                $menus = $this->Ap_menu->getMenus('general', null, 'no');
                                foreach($menus as $menu){

                                    echo '<tr>';
                                    echo '  <td class="access main" >';
                                    echo '    <input '.(@$access[$menu['alias']] == "on" ? "checked" : "").' type="checkbox" '.($no_access_flag == true ? 'disabled' : 'name="access['.$menu['alias'].']"').' id="'.$menu['alias'].'" class="main" >';
                                    echo '    <label for="'.$menu['alias'].'" >'.$menu['title_'.get_lang()].'</label>';
                                    echo '  </td>';
                                    echo '<tr>';


                                    $children_menus1 = $this->Ap_menu->getChildrenMenus($menu['id'], 'general_sub', null, 'no');
                                    $children_menus2 = $this->Ap_menu->getChildrenMenus($menu['id'], 'sub_action', null, 'no');
                                    $children_menus  = $children_menus1+$children_menus2;                                    
                                    
                                    foreach($children_menus as $children_menu){
                                        
                                        echo '<tr>';
                                        echo '  <td class="access" style="padding-left: 15px;" >';
                                        echo '    <input '.(@$access[$children_menu['alias']] == "on" ? "checked" : "").' type="checkbox" '.( (!isset($this->access[$children_menu['alias']]) || $no_access_flag == true) ? 'disabled' : 'name="access['.$children_menu['alias'].']"').' id="'.$children_menu['alias'].'" class="'.$menu['alias'].'" >';
                                        echo '    <label for="'.$children_menu['alias'].'" >&nbsp; - '.$children_menu['title_'.get_lang()].'</label>';
                                        echo '  </td>';
                                        echo '<tr>';

                                    }

                                    echo '<tr><td>&nbsp;</td></tr>';

                                }
                                
                                ?>
                                </table>
                            
                            </div>
                            
                            <?php } ?>                    
                                                       
                        </div>
                        
                    </div>
                    
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
                               
                                <tr>	      			
                                    <th><label><?php echo lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?php echo create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
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


