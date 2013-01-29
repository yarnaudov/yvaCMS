
<form name="list" action="<?=current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('components/gallery/img/iconImages_25.png');?>" >
            <span><?=lang('com_gallery_label_gallery');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?=lang('com_gallery_label_images');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?=site_url('components/gallery/images/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('components/gallery/images/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('components/gallery/images');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"                                 class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div id="page_header" >
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
			
                <select name="filters[album]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('com_gallery_label_album');?> - </option>
                    <?=create_options_array($albums, isset($filters['album']) ? $filters['album'] : "");?>
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
                <th style="width:3%;"  >&nbsp;</th>
                <th style="width:41%;" class="sortable" id="title_<?=Language::getDefault();?>" ><?=lang('label_title');?></th>
                <th style="width:12%;" class="sortable" id="album_id"   ><?=lang('com_gallery_label_album');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($images as $numb => $image){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="images[]" value="<?=$image['id'];?>" />
                </td>
                <td>
                    <a href="<?=site_url('components/gallery/images/edit/'.$image['id']);?>" >
                        <img class="image" src="<?=base_url('../'.$this->config->item('thumbs_dir').'/'.$image['id'].'.'.$image['ext']);?>" > 
                    </a>
                    <?php $image_data = getimagesize(FCPATH.'../'.$this->config->item('images_dir').'/'.$image['id'].'.'.$image['ext']);
                          $thumb_data = getimagesize(FCPATH.'../'.$this->config->item('thumbs_dir').'/'.$image['id'].'.'.$image['ext']); ?>
                    <span class="image_size">
                        <strong><?=$image_data[0];?></strong>x<strong><?=$image_data[1];?></strong>px,
                        <strong><?=$thumb_data[0];?></strong>x<strong><?=$thumb_data[1];?></strong>px
                    </span>
                </td>
                <td style="text-align: left;" >
                    <a href="<?=site_url('components/gallery/images/edit/'.$image['id']);?>" >
                        <?=$image['title'];?>
                    </a>
                    <?php if(!empty($image['description'])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($image['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?=$this->Album->getDetails($image['album_id'], 'title');?></td>
                <td>
                    <?php if($image['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($image['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($image['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($image['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Image->count($image['album_id']);
                            if($image['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$image['order'];?>
                    </span>
                </td>
                <td><?=User::getDetails($image['created_by'], 'user');?></td>
                <td><?=($image['created_on']);?></td>
                <td><?=$image['id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($images) == 0){ ?>
            <tr>
                <td colspan="10" ><?=lang('msg_no_results_found');?></td>
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