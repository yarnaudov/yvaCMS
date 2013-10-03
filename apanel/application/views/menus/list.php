
<form name="list" action="<?php echo current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <img src="<?php echo base_url('img/iconMenus_25.png');?>" >
            
            <span><?php echo lang('label_menus');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?php echo site_url('menus/add');?>"  class="styled add"    ><?php echo lang('label_add');?></a>
            <a href="<?php echo site_url('menus/edit');?>" class="styled edit"   ><?php echo lang('label_edit');?></a>
	    <a href="<?php echo site_url('menus');?>"      class="styled copy"   ><?php echo lang('label_copy');?></a>
            <a href="<?php echo site_url('menus');?>"      class="styled delete" ><?php echo lang('label_delete');?></a>
            <a href="<?php echo site_url();?>"             class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	
	<div id="filter_content" >
		
            <div class="search" >
                <input type="text" name="filters[search_v]" value="<?php echo isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?php echo lang('label_clear');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[category]" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('label_category');?> - </option>
                    <?php echo create_options_array($categories, isset($filters['category']) ? $filters['category'] : "");?>
                </select>

                <select name="filters[status]" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('label_status');?> - </option>
                    <?php echo create_options_array($this->config->item('statuses'), isset($filters['status']) ? $filters['status'] : "");?>
                </select>

            </div>
		
	</div>
	
	<table class="list" cellpadding="0" cellspacing="2" >
		
            <tr>
                <th style="width:3%;"  >#</th>	
                <th style="width:3%;"  >&nbsp;</th>
                <th style="width:31%;" class="sortable" id="title"       ><?php echo lang('label_title');?></th>
                <th style="width:10%;" class="sortable" id="type"        ><?php echo lang('label_type');?></th>
                <th style="width:12%;" class="sortable" id="category_id" ><?php echo lang('label_category');?></th>
                <th style="width:6%;"  class="sortable" id="default"     ><?php echo lang('label_default');?></th>
                <th style="width:6%;"  class="sortable" id="status"      ><?php echo lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"       ><?php echo lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by"  ><?php echo lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on"  ><?php echo lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php $numb = 0;
                  foreach($menus as $menu){                    
                    $row_class = $numb&1 ? "odd" : "even";
                    $numb++; ?>
		
            <tr class="row <?php echo $row_class;?>" >
                <td><?php echo $numb;?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="menus[]" value="<?php echo $menu['menu_id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <?php for($i = 1; $i < $menu['lavel']; $i++){ ?>
                        <span>|&mdash;</span>
                    <?php } ?>
                    <a href="<?php echo site_url('menus/edit/'.$menu['menu_id']);?>" >
                        <?php echo $menu['title'];?>
                    </a>
                    <div class="description" >(<span class="head" ><?php echo lang('label_alias');?>:</span> <span class="content" ><?php echo $menu['alias'];?></span>)</div>
                </td>
                <td>
                    <?php if(preg_match('/^components{1}/', $menu['type'])){
                              
                              $type_arr = explode('/', $menu['type']);
                              
                              if(count($type_arr) > 2){
                                  echo lang('com_'.$type_arr[1]).' > '.lang($this->components[$type_arr[1]]['menus'][$type_arr[2]]);
                              }
                              else{                                                
                                  echo lang($this->components[$type_arr[1]]['menus'][$type_arr[1]]);
                              }
                                            
                          }
                          else{
                              echo lang('label_'.$menu['type']);
                          }
                        ?>
                </td>
                <td><?php echo $this->Category->getDetails($menu['category_id'], 'title');?></td>
                <td <?php echo $menu['default'] == 'no' ? 'class="default"' : '';?> >
                    <?php if($menu['default'] == 'yes'){ ?>             
                    <img src="<?php echo base_url('img/iconStar16.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($menu['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?php echo base_url('img/iconActive.png');?>" >
                    <?php }elseif($menu['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconBlock.png');?>" >
                    <?php }elseif($menu['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($menu['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?php echo base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Menu->count($menu['category_id'], $menu['parent_id']);
                              if($menu['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?php echo base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?php echo $menu['order'];?>
                    </span>
                </td>
                <td><?php echo User::getDetails($menu['created_by'], 'user');?></td>
                <td><?php echo ($menu['created_on']);?></td>
                <td><?php echo $menu['menu_id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($menus) == 0){ ?>
            <tr>
                <td colspan="11" ><?php echo lang('msg_no_results_found');?></td>
            </tr>
            <?php } ?>
            
	</table>
	
        <?php $this->load->view('paging'); ?>
        
    </div>
    <!-- end page content -->

</form>

<!-- start jquery UI -->
<div id="dialog-edit1" title="<?php echo lang('label_error');?>" >
	<p><?php echo lang('msg_select_item');?></p>
</div>

<div id="dialog-edit2" title="<?php echo lang('label_error');?>" >
	<p><?php echo lang('msg_select_one_item');?></p>
</div>

<div id="dialog-delete" title="<?php echo lang('label_confirm');?>" >
	<p><?php echo lang('msg_delete_confirm');?></p>
</div>
<div id="dialog-copy" title="<?php echo lang('label_confirm');?>" >
    <p><?php echo lang('msg_copy_confirm');?></p>
</div>
<!-- end jquery UI -->