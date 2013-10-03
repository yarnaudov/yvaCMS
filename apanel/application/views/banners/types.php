
<?php if(!empty($error)){ ?>      
    <div class="error_msg" >
        <?php echo $error;?>
    </div>
<?php } ?>

<!-- start page content -->
<div id="page_content" class="module_types" >
    
    <ul>
    <?php foreach($banner_types as $type => $label){ ?> 
        
        <li>
            <a href="<?php echo $type;?>" class="type" >
            	<?php echo lang($label);?>
            </a>
        </li>
        
    <?php } ?>
    </ul>
  
</div>
<!-- end page content -->
