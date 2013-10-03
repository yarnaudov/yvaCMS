
<div>

    <?php if(isset($error)){ ?>
    <div class="error" >
        <?php echo $error;?>
    </div>
    <?php } ?>
    
 
    <?php if(isset($data)){
	      print_r($data);
	  } ?>
    
</div>
