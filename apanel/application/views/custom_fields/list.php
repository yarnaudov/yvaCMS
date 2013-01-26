
<?php

$cancel_link = $this->extension;
$menu_id     = $this->Ap_menu->getDetailsByAlias($this->extension, 'id');
if(empty($menu_id)){
    $cancel_link = 'components/'.$this->extension;
    $menu_id     = $this->Ap_menu->getDetailsByAlias('components/'.$this->extension, 'id');
}

?>

<form name="list" action="<?=current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <?php if(lang('label_'.$this->extension)){ ?>
            <img src="<?=base_url('img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?=lang('label_'.$this->extension);?></span>
            <?php }else{ 
                    $this->load->language($this->extension.'/com_gallery_labels'); ?>
            <img src="<?=base_url('components/'.$this->extension.'/img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?=lang('com_'.$this->extension.'_label_'.$this->extension);?></span>
            <?php } ?>
            
            <span>&nbsp;»&nbsp;</span>
            <span><?=lang('label_custom_fields');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?=site_url('custom_fields/add/'.$this->extension);?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('custom_fields/edit/'.$this->extension);?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('custom_fields');?>"                        class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url($cancel_link);?>"                           class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>

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
            </div class="search" >
		
            <div class="filter" >
                
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
                <th style="width:33%;" class="sortable" id="title"      ><?=lang('label_title');?></th>
                <th style="width:8%;"  class="sortable" id="type"       ><?=lang('label_type');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($custom_fields as $numb => $custom_field){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="custom_fields[]" value="<?=$custom_field['id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <a href="<?=site_url('custom_fields/edit/'.$custom_field['id'].'/'.$this->extension);?>" >
                        <?=$custom_field['title'];?>
                    </a>
                    <?php if(!empty($custom_field['description'])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($custom_field['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?=lang('label_'.$custom_field['type']);?></td>
                <td>
                    <?php if($custom_field['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($custom_field['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($custom_field['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($custom_field['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = count($custom_fields);
                            if($custom_field['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$custom_field['order'];?>
                    </span>
                </td>
                <td><?=User::getDetails($custom_field['created_by'], 'user');?></td>
                <td><?=($custom_field['created_on']);?></td>
                <td><?=$custom_field['id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($custom_fields) == 0){ ?>
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