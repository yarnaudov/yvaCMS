<!-- start messages -->
<?php if(function_exists('validation_errors')){
        $errors = validation_errors();
        if(!empty($errors)){ ?>      
        <div class="error_msg" >
            <ul>
                <?php echo validation_errors('<li>', '</li>');?>
            </ul>
        </div>
<?php } } ?>

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