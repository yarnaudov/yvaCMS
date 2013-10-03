
<?php $contact_form_msg = $this->session->flashdata('contact_form_msg'.$contact_form['id']);
      if(!empty($contact_form_msg)){ ?>

          <?php echo $contact_form_msg;?>
          &nbsp;
          <a href="<?php echo  current_url();?>" ><?php echo lang('label_cf_back_to_form');?></a>

<?php }else{ 
        $captcha = $contact_form['fields']['captcha'];
        unset($contact_form['fields']['captcha']); ?>

	<?php if(!empty($contact_form['text_above'])){ ?>
	<div class="text_above" >
	    <?php echo $this->Article->parceText($contact_form['text_above']);?>
	</div>
	<?php } ?>
	  
	<form method="post" action="<?php echo site_url('contact_forms/' . $contact_form['id']);?>" class="contactForm" id="contactForm_<?php echo $contact_form['id'];?>" >


	    <?php foreach($contact_form['fields'] as $number => $field){ 
		      //print_r($field);
		      $class = '';
		      switch($field['mandatory']){
			  case "yes": 
			      $class = "required";
			    break;

			  case "email":
			      $class = "required email";
			    break;

			  case "date":
			      $class = "required date";
			    break;
		      } 

		  ?>    

	    <div class="row" >
		<label for="field<?php echo $number;?>" >
		    <?php echo $field['label'];?>: 
		    <?php echo $field['mandatory'] != 'no' ? '*' : '';?>
		</label>
		<?php switch($field['type']){ 

			case "text":                
			    echo '<input class="'.$class.'" type="'.$field['type'].'" name="field'.$number.'" value="'.$field['value'].'" >'; 
			  break;

			case "checkbox":
			case "radio":

			    $name_sufix = $field['type'] == 'checkbox' ? '[]' : '';

			    echo '<ul>';
			    foreach($field['options'] as $key => $option){

				$checked = '';
				if($option == 1){
				    $checked = 'checked';
				}

				echo '<li>';
				echo '<input type="'.$field['type'].'" 
					     name="field'.$number.$name_sufix.'" 
					     id="option'.$number.$key.'" 
					     value="'.$key.'" 
					     '.$checked.'
					     class="'.$class.'" >';
				echo '<label for="option'.$number.$key.'" >'.$field['labels'][$key].'</label>';
				echo '</li>';
			    }
			    echo '</ul>';

			  break;

			case "textarea":
			    echo '<textarea class="'.$class.'" name="field'.$number.'" >'.$field['value'].'</textarea>'; 
			  break;

			case "dropdown":

			    echo '<select class="'.$class.'" name="field'.$number.'" >';
			    foreach($field['options'] as $key => $option){

				$selected = '';
				if($option == 1){
				    $selected = 'selected';
				}

				if($field['optgroups'][$key] == 1){
				    if($optgroup == true){
					echo '</optgroup>';
				    }
				    $optgroup = true;
				    echo '<optgroup label="'.$field['labels'][$key].'" >';
				}
				else{
				    echo '<option '.$selected.' value="'.$key.'" >'.$field['labels'][$key].'</option>';   
				}

				if($key+1 == count($field['options']) && $optgroup == true){
				    echo '</optgroup>';
				}

			    }
			    echo '</select>';
			    /*
			    echo '<select class="'.$class.'" name="field'.$number.'" >';
			    //$options = explode(',', $field['value']);
			    foreach($field['options'] as $key => $option){
				echo '<option value="'.$option.'" >'.$field['labels'][$key].'</option>';
			    }
			    echo '</select>';
			    */
			  break;

			case "date":
			    echo '<input type="text" class="'.$class.' datepicker" name="field'.$number.'" value="'.$field['value'].'" >';                    
			  break;
		      } ?>


	    </div>

	    <?php } ?>

	    <?php if($captcha['enabled'] == 'yes'){ ?>
	    <br/>
	    <div class="row captcha" >
		<label>&nbsp;</label>
		<img id="siimage" style="margin-right: 15px;width: 200px;height: 70px;" src="<?php echo base_url();?>/plugins/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" />	
		<object type="application/x-shockwave-flash" data="<?php echo base_url();?>/plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?php echo base_url();?>/plugins/securimage/securimage_play.php" width="19" height="19" >
		    <param name="movie" value="<?php echo base_url();?>/plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?php echo base_url();?>/plugins/securimage/securimage_play.php" />
		</object>		
		&nbsp;
		<a tabindex="-1" style="border-style: none;" href="#" id="refresh_image" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo base_url();?>/plugins/securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
		    <img src="<?php echo base_url();?>plugins/securimage/images/refresh.gif" alt="Reload Image" onclick="this.blur()" align="bottom" border="0" />
		</a>
		<br />

	    </div>

	    <div class="row" >

		<label><?php echo lang('label_cf_enter_code');?>: *</label>
		<input type="text" name="ct_captcha" class="required captcha_input" />

	    </div>
	    <br/>
	    <?php } ?>

	    <div class="buttons">
		<button class="primary" type="submit" name="send" ><?php echo lang('label_cf_send');?></button>
		<button type="reset" ><?php echo lang('label_cf_clear');?></button>
	    </div>

	</form>
	  
	<?php if(!empty($contact_form['text_under'])){ ?>
	<div class="text_under" >
	    <?php echo $this->Article->parceText($contact_form['text_under']);?>
	</div>
	<?php } ?>

<?php } ?>