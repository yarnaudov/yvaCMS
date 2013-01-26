
<form name="list" action="<?=current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('components/gallery/img/iconAlbums_25.png');?>" >
            <span><?=lang('com_gallery_label_gallery');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?=lang('com_gallery_label_albums');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="<?=site_url('components/gallery/albums/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('components/gallery/albums/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('components/gallery/albums');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"                                 class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
            </div>
		
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
                <th style="width:41%;" class="sortable" id="title"      ><?=lang('label_title');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_images');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($albums as $numb => $album){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>	
                <td>
                    <input type="checkbox" class="checkbox" name="albums[]" value="<?=$album['album_id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <a href="<?=site_url('components/gallery/albums/edit/'.$album['album_id']);?>" >
                        <?=$album['title'];?>
                    </a>
                    <?php if(!empty($album['description'])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($album['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td>
                    <a href="<?=site_url('components/gallery/images?album='.$album['album_id']);?>" >
                        <?=count(image::getImages(array('album_id' => $album['album_id'])));?>
                    </a>                    
                </td>
                <td>
                    <?php if($album['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($album['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($album['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($album['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Album->count();
                            if($album['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$album['order'];?>
                    </span>
                </td>
                <td><?=User::getDetails($album['created_by'], 'user');?></td>
                <td><?=($album['created_on']);?></td>
                <td><?=$album['album_id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($albums) == 0){ ?>
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