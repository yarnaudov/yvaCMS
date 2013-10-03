
<form name="list" action="<?php echo current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('components/gallery/img/iconImages_25.png');?>" >
            <span><?php echo lang('com_gallery_label_gallery');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('com_gallery_label_images');?></span>
        </div>
	
	<div class="actions" >
		
	     <?php if($this->layout != 'simple_ajax'){ ?>  
            <a href="<?php echo site_url('components/gallery/images/add');?>"  class="styled add"    ><?php echo lang('label_add');?></a>
            <a href="<?php echo site_url('components/gallery/images/edit');?>" class="styled edit"   ><?php echo lang('label_edit');?></a>
            <a href="<?php echo site_url('components/gallery/images');?>"      class="styled delete" ><?php echo lang('label_delete');?></a>
            <a href="<?php echo site_url();?>"                                 class="styled cancel" ><?php echo lang('label_cancel');?></a>
	    <?php }else{ ?>	    
	    <button class="styled refresh" type="submit" ><?php echo lang('label_refresh');?></button>
	    &nbsp;&nbsp;&nbsp;&nbsp;
	    <a href="#" class="styled select" ><?php echo lang('label_select');?></a>
	    <?php } ?>
		
	</div>
	
    </div id="page_header" >
    <!-- end page header -->
    

    <?php if($this->layout != 'simple_ajax'){ ?>
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <?php } ?>


    <!-- start messages -->
    <?php $good_msg = $this->session->userdata('good_msg');
          $this->session->unset_userdata('good_msg');
          if(!empty($good_msg)){ ?>
          <div class="good_msg" >
              <?php echo $good_msg;?>            
          </div>
    <?php } ?>

    <?php $error_msg = $this->session->userdata('error_msg');
          $this->session->unset_userdata('error_msg');
          if(!empty($error_msg)){ ?>
          <div class="error_msg" >
              <?php echo $error_msg;?>            
          </div>
    <?php } ?>
    <!-- end messages -->


    <!-- start page content -->
    <div id="page_content" >
	
	<div id="filter_content" >
		
            <div class="search" >
                <input type="text" name="filters[search_v]" value="<?php echo isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?php echo lang('label_clear');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[album]" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('com_gallery_label_album');?> - </option>
                    <?php echo create_options_array($albums, isset($filters['album']) ? $filters['album'] : "");?>
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
                <th style="width:3%;"  >&nbsp;</th>
                <th style="width:41%;" class="sortable" id="title_<?php echo Language::getDefault();?>" ><?php echo lang('label_title');?></th>
                <th style="width:12%;" class="sortable" id="album_id"   ><?php echo lang('com_gallery_label_album');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?php echo lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?php echo lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?php echo lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?php echo lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($images as $numb => $image){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?php echo $row_class;?>" >
                <td><?php echo (($numb+1)+($limit*($this->page-1)));?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="images[]" value="<?php echo $image['id'];?>" />
                </td>
                <td>
                    <a href="<?php echo site_url('components/gallery/images/edit/'.$image['id']);?>" >
                        <?php $image_src = base_url('../'.$this->config->item('images_dir').'/'.$image['id'].'.'.$image['ext']); ?>
                        <img class="image" src="<?php echo $image_src.'?'.time();?>" > 
                    </a>
                </td>
                <td style="text-align: left;" >
                    <a href="<?php echo site_url('components/gallery/images/edit/'.$image['id']);?>" >
                        <?php echo $image['title'];?>
                    </a>
                    <?php if(!empty($image['description'])){ ?>
                    <div class="description" >(<span class="head" ><?php echo lang('label_description');?>:</span> <span class="content" ><?php echo strip_tags($image['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td><?php echo $this->Album->getDetails($image['album_id'], 'title');?></td>
                <td>
                    <?php if($image['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?php echo base_url('img/iconActive.png');?>" >
                    <?php }elseif($image['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconBlock.png');?>" >
                    <?php }elseif($image['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($image['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?php echo base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Image->count($image['album_id']);
                            if($image['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?php echo base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?php echo $image['order'];?>
                    </span>
                </td>
                <td><?php echo User::getDetails($image['created_by'], 'user');?></td>
                <td><?php echo ($image['created_on']);?></td>
                <td><?php echo $image['id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($images) == 0){ ?>
            <tr>
                <td colspan="10" ><?php echo lang('msg_no_results_found');?></td>
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