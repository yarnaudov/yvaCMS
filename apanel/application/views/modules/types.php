
<?php if(!empty($error)){ ?>      
    <div class="error_msg" >
        <?=$error;?>
    </div>
<?php } ?>

<!-- start page content -->
<div id="page_content" class="module_types" >
    
    <ul>
    <?php foreach($modules as $module){ ?> 
        
        <li>
            <a href="<?=$module;?>" class="type" >
            	<?=lang('label_'.$module);?>
            </a>
        </li>
        
    <?php } ?>
    </ul>
  
</div>
<!-- end page content -->
