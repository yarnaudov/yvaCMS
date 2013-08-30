
<form name="list" action="<?=current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconModules_25.png');?>" >
            <span><?=lang('label_modules_c');?></span>
        </div>
	
	<div class="actions" >
	    
            <a href="<?=site_url('modules/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('modules/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
	    <a href="<?=site_url('banners');?>"      class="styled copy"   ><?=lang('label_copy');?></a>
            <a href="<?=site_url('modules');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"               class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
        
    </div>
    <!-- end page header -->
    
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>

    <?=$this->load->view('messages');?>

    <!-- start page content -->
    <div id="page_content" >
	
	<div id="filter_content" >
		
            <div class="search" >
                <input type="text" name="filters[search_v]" value="<?=isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?=lang('label_clear');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[position]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_position');?> - </option>
                    <?=create_options_array($positions, isset($filters['position']) ? $filters['position'] : "" );?>
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
                <th style="width:39%;" class="sortable" id="title"      ><?=lang('label_title');?></th>
                <th style="width:10%;" class="sortable" id="type"       ><?=lang('label_type');?></th>
                <th style="width:12%;" class="sortable" id="position"   ><?=lang('label_position');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($modules as $numb => $module){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>
                
                <td>
                    <input type="checkbox" class="checkbox" name="modules[]" value="<?=$module['id'];?>" />
                </td>                                
                <td style="text-align: left;" >
                    <a href="<?=site_url('modules/edit/'.$module['id']);?>" >
                        <?=$module['title'];?>
                    </a>
                    <?php if(!empty($module['description'])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($module['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?=lang('label_'.$module['type']);?></td>
                <td><?=$module['position'];?></td>
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
                        <?php $max_order = $this->Module->count($module['position']);
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
                <td><?=$module['id'];?></td>
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
<div id="dialog-copy" title="<?=lang('label_confirm');?>" >
    <p><?=lang('msg_copy_confirm');?></p>
</div>
<!-- end jquery UI -->