

 <div id="page_header" >
	
    <div class="text" >
        <!--
        <img src="<?php echo base_url('img/iconArticles.png');?>" >
        -->
        <span><?php echo lang('label_error');?> 403</span>
    </div>

 </div>

<div id="page_content" >
    You don't have permission  to access <strong><?php echo $this->session->userdata('no_access_page');?></strong>. 
    For more information please contact your system administrator.
</div>
