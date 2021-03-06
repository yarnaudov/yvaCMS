
<form name="list" action="<?=current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconModules_25.png');?>" >
            <span><?=lang('label_modules');?></span>
        </div>
	
        <?php if($this->layout != 'simple_ajax'){ ?>
	<div class="actions" >
	    
            <a href="<?=site_url('modules/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('modules/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('modules');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"               class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
        <?php } ?>
        
    </div>
    <!-- end page header -->
    
    
    <?php if($this->layout != 'simple_ajax'){ ?>
    <!-- start page content -->
    <div id="sub_actions" >
	<?php
	
	$main_menu = $this->Adm_menu->getDetails(7);
	$menu[$main_menu['title_'.get_lang()]] = $main_menu['alias'];
        
        $children_menus = $this->Adm_menu->getChildrenMenus(7, 1);
                
        $children_menu = array();
        foreach($children_menus as $children_menu_d){
            $menu[$children_menu_d['title_'.get_lang()]] = $children_menu_d['alias']; 
        }
        				
        echo $this->menu_lib->create_menu($menu);
  
        ?>
    </div>
    <!-- start page content -->
    <?php } ?>


    <!-- start messages -->
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
	
	<div id="filter_content" >
		
            <div class="search" >
                <input type="text" name="search_v" value="<?=isset($search_v) ? $search_v : "";?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?=lang('label_clear');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="category" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_category');?> - </option>
                    <?=create_options('categories', 'category_id', 'title_'.Language::getDefault(), isset($category) ? $category : "", array('extension' => 'modules', 'status' => 'yes') );?>
                </select>

                <select name="status" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_status');?> - </option>
                    <?=create_options_array($this->config->item('statuses'), isset($status) ? $status : "");?>
                </select>

            </div>
		
	</div>
	
	<table class="list" cellpadding="0" cellspacing="2" >
		
            <tr>
                <th style="width:3%;"  >#</th>
                
                <th style="width:3%;"  >&nbsp;</th>
                
                
                <th style="width:39%;" class="sortable" id="title_<?=Language::getDefault();?>" ><?=lang('label_title');?></th>
                <th style="width:10%;" class="sortable" id="type"        ><?=lang('label_type');?></th>
                <th style="width:12%;" class="sortable" id="category_id" ><?=lang('label_category');?></th>
                
                <?php if($this->layout != 'simple_ajax'){ ?>
                <th style="width:6%;"  class="sortable" id="status"      ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"       ><?=lang('label_order');?></th>
                <?php } ?>
                
                <th style="width:8%;"  class="sortable" id="created_by"  ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on"  ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($modules as $numb => $module){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=($numb+1);?></td>
                
                <td>
                    <input type="checkbox" class="checkbox" name="modules[]" value="<?=$module['module_id'];?>" />
                </td>                                
                <td style="text-align: left;" >
                    <a href="<?=site_url('modules/edit/'.$module['module_id']);?>" >
                        <?=$module['title_'.Language::getDefault()];?>
                    </a>
                    <?php if(!empty($module['description_'.Language::getDefault()])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($module['description_'.Language::getDefault()]);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?=lang('label_'.$module['type']);?></td>
                <td><?=$this->Category->getDetails($module['category_id'], 'title_'.Language::getDefault());?></td>
                <td>
                    <?php if($module['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($module['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($module['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($module['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Module->count($module['category_id']);
                            if($module['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$module['order'];?>
                    </span>
                </td>                
                <td><?=User::getDetails($module['created_by'], 'user');?></td>
                <td><?=($module['created_on']);?></td>
                <td><?=$module['module_id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($modules) == 0){ ?>
            <tr>
                <td colspan="9" ><?=lang('msg_no_results_found');?></td>
            </tr>
            <?php } ?>
            
	</table>
	
       <?php $this->load->view('paging'); ?>
        
    </div>
    <!-- end page content -->

</form>

<!-- start jquery UI -->
<div id="dialog-edit1" title="<?=lang('label_error');?>" >
    <p><?=lang('msg_select_item');?></p>
</div>

<div id="dialog-edit2" title="<?=lang('label_error');?>" >
    <p><?=lang('msg_select_one_item');?></p>
</div>

<div id="dialog-delete" title="<?=lang('label_confirm');?>" >
    <p><?=lang('msg_delete_confirm');?></p>
</div>
<!-- end jquery UI -->