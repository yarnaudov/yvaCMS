<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<?php $this->load->view('header'); ?>
  
<div id="main" >
                 
    <?php if($_SESSION['user_id'] == ""){ ?>

    <script type="text/javascript" >    
        parent.location.reload();    
    </script>
      
    <?php }else{ ?>
                 
    <div id="content-simple" >
        <?php echo $content; ?>
    </div>
      
    <?php } ?>
  
</div>

<?php $this->load->view('footer'); ?>