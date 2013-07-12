
<form name="list" action="<?=current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconArticles_25.png');?>" >
            <span><?=lang('label_articles');?></span>
        </div>
	
	<div class="actions" >
	
            <?php if($this->layout != 'simple_ajax'){ ?>            
            <a href="<?=site_url('articles/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('articles/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
	    <a href="<?=site_url('articles');?>"      class="styled copy"   ><?=lang('label_copy');?></a>
            <a href="<?=site_url('articles');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"                class="styled cancel" ><?=lang('label_cancel');?></a>
	    <?php }else{ ?>
            <button class="styled refresh" type="submit" ><?=lang('label_refresh');?></button>
            <?php } ?>
            
	</div>
        
    </div>
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
                
                <?php if($this->layout != 'simple_ajax'){ ?>
                <th style="width:3%;"  >&nbsp;</th>
                <?php } ?>
                
                <th style="width:41%;" class="sortable" id="title"       ><?=lang('label_title');?></th>
                <th style="width:12%;" class="sortable" id="category_id" ><?=lang('label_category');?></th>
                
                <?php if($this->layout != 'simple_ajax'){ ?>
                <th style="width:6%;"  class="sortable" id="status"      ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"       ><?=lang('label_order');?></th>
                <?php } ?>
                
                <th style="width:8%;"  class="sortable" id="created_by"  ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on"  ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>
		
            <?php foreach($articles as $numb => $article){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>
		
            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>
                
                <?php if($this->layout != 'simple_ajax'){ ?>
                <td>
                    <input type="checkbox" class="checkbox" name="articles[]" value="<?=$article['id'];?>" />
                </td>
                <?php } ?>
                
                <td style="text-align: left;" >
                    <a href="<?=site_url('articles/edit/'.$article['id']);?>" lang="<?=$article['alias'];?>" >
                        <?=$article['title'];?>
                    </a>
                    <div class="description" >(<span class="head" ><?=lang('label_alias');?>:</span> <span class="content" ><?=$article['alias'];?></span>)</div>
                </td>
                <td><?=$this->Category->getDetails($article['category_id'], 'title');?></td>
                
                <?php if($this->layout != 'simple_ajax'){ ?>
                <td>
                    <?php if($article['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($article['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($article['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($article['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    
                    <span class="order_span" >
                        <?php $max_order = $this->Article->count($article['category_id']);
                            if($article['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>
                    
                    <span class="order_span" >
                    <?=$article['order'];?>
                    </span>
                </td>
                <?php } ?>
                
                <td><?=$this->User->getDetails($article['created_by'], 'user');?></td>
                <td><?=($article['created_on']);?></td>
                <td><?=$article['id'];?></td>
            </tr>
		
            <?php } ?>
	    
            <?php if(count($articles) == 0){ ?>
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