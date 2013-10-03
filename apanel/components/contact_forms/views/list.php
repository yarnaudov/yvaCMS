<form name="list" action="<?php echo current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('components/contact_forms/img/iconContact_forms_25.png');?>" >
            <span><?php echo lang('label_contact_forms');?></span>
        </div>
	
	<div class="actions" >
	    
            <a href="<?php echo site_url('components/contact_forms/add');?>"  class="styled add"    ><?php echo lang('label_add');?></a>
            <a href="<?php echo site_url('components/contact_forms/edit');?>" class="styled edit"   ><?php echo lang('label_edit');?></a>
            <a href="<?php echo site_url('components/contact_forms');?>"      class="styled delete" ><?php echo lang('label_delete');?></a>
            <a href="<?php echo site_url();?>"                                class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
        
    </div>
    <!-- end page header -->

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
                <input type="text" name="search_v" value="<?php echo isset($search_v) ? $search_v : "";?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?php echo lang('label_clear');?></button>
            </div>
		
            <div class="filter" >

                <select name="status" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('label_status');?> - </option>
                    <?php echo create_options_array($this->config->item('statuses'), isset($status) ? $status : "");?>
                </select>

            </div>
		
	</div>
        
        <table class="list" cellpadding="0" cellspacing="2" >

            <tr>
                <th style="width:3%;"  >#</th>
                <th style="width:3%;"  >&nbsp;</th>
                <th style="width:41%;" class="sortable" id="title"      ><?php echo lang('label_title');?></th>
                <th style="width:6%;"  class="sortable" id="status"     ><?php echo lang('label_status');?></th>
                <th style="width:8%;"  class="sortable" id="order"      ><?php echo lang('label_order');?></th>
                <th style="width:8%;"  class="sortable" id="created_by" ><?php echo lang('label_author');?></th>
                <th style="width:12%;" class="sortable" id="created_on" ><?php echo lang('label_date');?></th>
                <th style="width:5%;"  >ID</th>
            </tr>

            <?php foreach($contact_forms as $numb => $contact_form){ 
                    $row_class = $numb&1 ? "odd" : "even"; ?>

            <tr class="row <?php echo $row_class;?>" >
                <td><?php echo (($numb+1)+($limit*($this->page-1)));?></td>
                <td>
                    <input type="checkbox" class="checkbox" name="contact_forms[]" value="<?php echo $contact_form['id'];?>" />
                </td>
                <td style="text-align: left;" >
                    <a href="<?php echo site_url('components/contact_forms/edit/'.$contact_form['id']);?>" >
                        <?php echo $contact_form['title'];?>
                    </a>
                    <?php if(!empty($contact_form['description'])){ ?>
                    <div class="description" >(<span class="head" ><?php echo lang('label_description');?>:</span> <span class="content" ><?php echo strip_tags($contact_form['description']);?></span>)</div>
                    <?php } ?>
                </td>
                <td>
                    <?php if($contact_form['status'] == 'yes'){ ?>
                    <img class="status_img" alt="no"  src="<?php echo base_url('img/iconActive.png');?>" >
                    <?php }elseif($contact_form['status'] == 'no'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconBlock.png');?>" >
                    <?php }elseif($contact_form['status'] == 'trash'){ ?>
                    <img class="status_img" alt="yes" src="<?php echo base_url('img/iconRecover.png');?>" >
                    <?php } ?>
                </td>
                <td>
                    <?php if($order == 'order'){ ?>
                    <span class="order_span" >
                        <?php if($contact_form['order'] > 1){ ?>
                        <img class="order_img" alt="up" src="<?php echo base_url('img/iconArrowUp.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>

                    <span class="order_span" >
                        <?php $max_order = $this->Contact_form->count();
                            if($contact_form['order'] < $max_order){ ?>
                        <img class="order_img" alt="down" src="<?php echo base_url('img/iconArrowDown.png');?>" >
                        <?php }else{ ?>
                        &nbsp;
                        <?php } ?>
                    </span>
                    <?php } ?>

                    <span class="order_span" >
                    <?php echo $contact_form['order'];?>
                    </span>
                </td>
                <td><?php echo $this->User->getDetails($contact_form['created_by'], 'user');?></td>
                <td><?php echo ($contact_form['created_on']);?></td>
                <td><?php echo $contact_form['contact_form_id'];?></td>
            </tr>

            <?php } ?>

            <?php if(count($contact_forms) == 0){ ?>
            <tr>
                <td colspan="8" ><?php echo lang('msg_no_results_found');?></td>
            </tr>
            <?php } ?>

        </table>

        <?php $this->load->view('paging'); ?>
        
    </div>
    
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