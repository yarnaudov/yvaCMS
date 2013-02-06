<form name="list" action="<?=current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('components/contact_forms/img/iconContact_forms_25.png');?>" >
            <span><?=lang('label_contact_forms');?></span>
        </div>
	
	<div class="actions" >
	    
            <a href="<?=site_url('components/contact_forms/add');?>"  class="styled add"    ><?=lang('label_add');?></a>
            <a href="<?=site_url('components/contact_forms/edit');?>" class="styled edit"   ><?=lang('label_edit');?></a>
            <a href="<?=site_url('components/contact_forms');?>"      class="styled delete" ><?=lang('label_delete');?></a>
            <a href="<?=site_url();?>"                                class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
        
    </div>
    <!-- end page header -->

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
                <th style="width:6%;"  class="sortable" id="status"     ><?=lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?=lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?=lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?=lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>

            <?php foreach($contact_forms as $numb => $contact_form){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>

            <tr class="row <?=$row_class;?>" >
                <td><?=(($numb+1)+($limit*($this->page-1)));?></td>
                <td>
                    <input type="checkbox" class="checkbox" name="contact_forms[]" value="<?=$contact_form['id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <a href="<?=site_url('components/contact_forms/edit/'.$contact_form['id']);?>" >
                        <?=$contact_form['title'];?>
                    </a>
                    <?php if(!empty($contact_form['description'])){ ?>
                    <div class="description" >(<span class="head" ><?=lang('label_description');?>:</span> <span class="content" ><?=strip_tags($contact_form['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td>
                    <?php if($contact_form['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?=base_url('img/iconActive.png');?>" >
                    <?php }elseif($contact_form['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconBlock.png');?>" >
                    <?php }elseif($contact_form['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?=base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($contact_form['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?=base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>

                    <span class="order_span" >
                        <?php $max_order = $this->Contact_form->count();
                            if($contact_form['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?=base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>

                    <span class="order_span" >
                    <?=$contact_form['order'];?>
                    </span>
                </td>
                <td><?=$this->User->getDetails($contact_form['created_by'], 'user');?></td>
                <td><?=($contact_form['created_on']);?></td>
                <td><?=$contact_form['contact_form_id'];?></td>
            </tr>

            <?php } ?>

            <?php if(count($contact_forms) == 0){ ?>
            <tr>
                <td colspan="8" ><?=lang('msg_no_results_found');?></td>
            </tr>
            <?php } ?>

        </table>

        <?php $this->load->view('paging'); ?>
        
    </div>
    
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