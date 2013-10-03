
<?php

$cancel_link = $this->extension;
$menu_id     = $this->Ap_menu->getDetailsByAlias($this->extension, 'id');
if(empty($menu_id)){
    $cancel_link = 'components/'.$this->extension;
    $menu_id     = $this->Ap_menu->getDetailsByAlias('components/'.$this->extension, 'id');
}

?>

<form name="list" action="<?php echo current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <?php if(lang('label_'.$this->extension)){ ?>
            <img src="<?php echo base_url('img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?php echo lang('label_'.$this->extension);?></span>
            <?php }else{ 
                    $this->load->language($this->extension.'/com_gallery_labels'); ?>
            <img src="<?php echo base_url('components/'.$this->extension.'/img/icon'.ucfirst($this->extension).'_25.png');?>" >
            <span><?php echo lang('com_'.$this->extension.'_label_'.$this->extension);?></span>
            <?php } ?>
            
            <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_custom_fields');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?php echo site_url('custom_fields/add/'.$this->extension);?>"  class="styled add"    ><?php echo lang('label_add');?></a>
            <a href="<?php echo site_url('custom_fields/edit/'.$this->extension);?>" class="styled edit"   ><?php echo lang('label_edit');?></a>
            <a href="<?php echo site_url('custom_fields');?>"                        class="styled delete" ><?php echo lang('label_delete');?></a>
            <a href="<?php echo site_url($cancel_link);?>"                           class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>

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
                <th style="width:33%;" class="sortable" id="title"      ><?php echo lang('label_title');?></th>
                <th style="width:8%;"  class="sortable" id="type"       ><?php echo lang('label_type');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?php echo lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?php echo lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?php echo lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?php echo lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($custom_fields as $numb => $custom_field){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?php echo $row_class;?>" >
                <td><?php echo (($numb+1)+($limit*($this->page-1)));?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="custom_fields[]" value="<?php echo $custom_field['id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <a href="<?php echo site_url('custom_fields/edit/'.$custom_field['id'].'/'.$this->extension);?>" >
                        <?php echo $custom_field['title'];?>
                    </a>
                    <?php if(!empty($custom_field['description'])){ ?>
                    <div class="description" >(<span class="head" ><?php echo lang('label_description');?>:</span> <span class="content" ><?php echo strip_tags($custom_field['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?php echo lang('label_'.$custom_field['type'].'_field');?></td>
                <td>
                    <?php if($custom_field['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?php echo base_url('img/iconActive.png');?>" >
                    <?php }elseif($custom_field['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconBlock.png');?>" >
                    <?php }elseif($custom_field['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($custom_field['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?php echo base_url('img/iconArrowUp.png');?>" >
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = count($custom_fields);
                            if($custom_field['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?php echo base_url('img/iconArrowDown.png');?>" >
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?php echo $custom_field['order'];?>
                    </span>
                </td>
                <td><?php echo User::getDetails($custom_field['created_by'], 'user');?></td>
                <td><?php echo ($custom_field['created_on']);?></td>
                <td><?php echo $custom_field['id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($custom_fields) == 0){ ?>
            <tr>
                <td colspan="9" ><?php echo lang('msg_no_results_found');?></td>
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
<!-- end jquery UI -->