
<form name="list" action="<?=current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <img src="<?=base_url('img/iconMenus_25.png');?>" >
            
            <span><?=lang('label_menus');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?=site_url('menus/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('menus/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('menus');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"             class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    

    <!-- start page content -->
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <!-- start page content -->


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
                <input type="text" name="filters[search_v]" value="<?=isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?=lang('label_clear');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[category]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_category');?> - </option>
                    <?=create_options_array($categories, isset($filters['category']) ? $filters['category'] : "");?>
                </select>

                <select name="filters[status]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_status');?> - </option>
                    <?=create_options_array($this->config->item('statuses'), isset($filters['status']) ? $filters['status'] : "");?>
                </select>

            </div>
		
	</div>
	
	<table class="list" cellpadding="0" cellspacing="2" >
		
            <tr>
                <th style="width:3%;"  >#</th>	
                <th style="width:3%;"  >&nbsp;</th>
                <th style="width:31%;" class="sortable" id="title"       ><?=lang('label_title');?></th>
                <th style="width:10%;" class="sortable" id="type"        ><?=lang('label_type');?></th>
                <th style="width:12%;" class="sortable" id="category_id" ><?=lang('label_category');?></th>
                <th style="width:6%;"  class="sortable" id="default"     ><?=lang('label_default');?></th>
                <th style="width:6%;"  class="sortable" id="status"      ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"       ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by"  ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on"  ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php $numb = 0;
                  foreach($menus as $menu){                    
                    $row_class = $numb&1 ? "odd" : "even";
                    $numb++; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=$numb;?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="menus[]" value="<?=$menu['menu_id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <?php for($i = 1; $i < $menu['lavel']; $i++){ ?>
                        <span>|&mdash;</span>
                    <?php } ?>
                    <a href="<?=site_url('menus/edit/'.$menu['menu_id']);?>" >
                        <?=$menu['title'];?>
                    </a>
                    <div class="description" >(<span class="head" ><?=lang('label_alias');?>:</span> <span class="content" ><?=$menu['alias'];?></span>)</div>
                </td>
                <td><?=lang('label_'.$menu['params']['type']);?></td>
                <td><?=$this->Category->getDetails($menu['category_id'], 'title');?></td>
                <td>
                    <?php if($menu['default'] == 'yes'){ ?>             
                    <img src="<?=base_url('img/iconStar16.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($menu['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($menu['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($menu['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($menu['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Menu->count($menu['category_id'], $menu['parent_id']);
                              if($menu['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$menu['order'];?>
                    </span>
                </td>
                <td><?=User::getDetails($menu['created_by'], 'user');?></td>
                <td><?=($menu['created_on']);?></td>
                <td><?=$menu['menu_id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($menus) == 0){ ?>
            <tr>
                <td colspan="11" ><?=lang('msg_no_results_found');?></td>
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