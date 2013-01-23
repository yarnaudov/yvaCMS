
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconUsers_25.png');?>" >
            <span><?=lang('label_users');?></span>
            <span>&nbsp;»&nbsp;</span>
            <!--
	    <img src="<?=base_url('img/iconCategories.png');?>" >
            -->
            <span><?=lang('label_groups');?></span>
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
		
	    <button type="submit" name="save"        class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"       class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('groups/users');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                        <span class="header" ><?=lang('label_access');?></span>
                        
                        <div class="box_content" >
                            
                            <?php if($access == '*'){ ?>
                            
                            <div><?=lang('msg_group_super_admin');?>By default this group has full rights</div>
                            <input type="hidden" name="no_access" value="true" >
                            
                            <?php }else{ ?>
                                                        
                            <div class="menu_list" >
                                                                
                                <table  class="box_table" cellpadding="0" cellspacing="0" >
                                <?php
                                
                                $access       = @json_decode($access, true);                                
                                $user_group_id     = $this->User->getDetails('', 'user_group_id');
                                $group_access = $this->User_group->getDetails($user_group_id, 'access');

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

                                $menus = $this->Adm_menu->getMainMenus(1, 'no');
                                foreach($menus as $menu){

                                    echo '<tr>';
                                    echo '  <td class="access main" >';
                                    echo '    <input '.(@$access[$menu['alias']] == "on" ? "checked" : "").' type="checkbox" '.($no_access_flag == true ? 'disabled' : 'name="access['.$menu['alias'].']"').' id="'.$menu['alias'].'" class="main" >';
                                    echo '    <label for="'.$menu['alias'].'" >'.$menu['title_'.get_lang()].'</label>';
                                    echo '  </td>';
                                    echo '<tr>';


                                    $children_menus1 = $this->Adm_menu->getChildrenMenus($menu['id'], 0, 1, 'no');
                                    $children_menus2 = $this->Adm_menu->getChildrenMenus($menu['id'], 1, 1, 'no');
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
	      	        <span class="header" ><?=lang('label_description');?></span>
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


