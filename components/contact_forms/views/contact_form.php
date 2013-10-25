
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
	  
	<form method="post" action="<?php echo  current_url(); ?>" class="contactForm" id="contactForm_<?php echo $contact_form['id'];?>" enctype="multipart/form-data" >

		<input type="hidden" name="contact_form_id" value="<?php echo $contact_form['id']; ?>" >
		
	    <?php foreach($contact_form['fields'] as $number => $field){ ?>    

	    <div class="row" >
		<label for="field<?php echo $number;?>" >
		    <?php echo $field['label'];?>: 
		    <?php echo $field['mandatory'] != 'no' ? '*' : '';?>
		</label>
		<?php switch($field['type']){ 

				case "text":                
					echo '<input '.(isset($field['class']) ? 'class="'.$field['class'].'"' : '').' type="'.$field['type'].'" name="field'.$number.'" value="'.set_value('field'.$number, $field['value']).'" >';
				break;

				case "checkbox":
				case "radio":

					$name_sufix = $field['type'] == 'checkbox' ? '[]' : '';

					echo '<ul>';
					foreach($field['options'] as $key => $option){

						$option = $option == 1 ? TRUE : FALSE;
						
						if($field['type'] == 'checkbox'){
							$checked = set_checkbox('field'.$number.$name_sufix, $key, $option);
						}
						else{
							$checked = set_radio('field'.$number, $key, $option);
						}

						echo '<li>';
						echo '<input type="'.$field['type'].'" 
								 name="field'.$number.$name_sufix.'" 
								 id="option'.$number.$key.'" 
								 value="'.$key.'"
								 '.$checked.'
								 '.(isset($field['class']) ? 'class="'.$field['class'].'"' : '').' >';
						echo '<label for="option'.$number.$key.'" >'.$field['labels'][$key].'</label>';
						echo '</li>';

					}
					echo '</ul>';

				  break;

				case "textarea":
					echo '<textarea '.(isset($field['class']) ? 'class="'.$field['class'].'"' : '').' name="field'.$number.'" >'.set_value('field'.$number, $field['value']).'</textarea>'; 
				break;

				case "dropdown":

					echo '<select '.(isset($field['class']) ? 'class="'.$field['class'].'"' : '').' name="field'.$number.'" >';
					foreach($field['options'] as $key => $option){

						$selected = '';
						$option = $option == 1 ? TRUE : FALSE;

						if($field['optgroups'][$key] == 1){
							if($optgroup == true){
							echo '</optgroup>';
							}
							$optgroup = true;
							echo '<optgroup label="'.$field['labels'][$key].'" >';
						}
						else{
							echo '<option '.set_select('field'.$number, $key, $option).' value="'.$key.'" >'.$field['labels'][$key].'</option>';   
						}

						if($key+1 == count($field['options']) && $optgroup == true){
							echo '</optgroup>';
						}

					}
					echo '</select>';

				  break;

				case "date":
				  echo '<input type="text" class="'.(isset($field['class']) ? $field['class'].' ' : '').'datepicker" name="field'.$number.'" value="'.set_value('field'.$number, $field['value']).'" readonly >';
				break;
		  
				case "file":				  
					echo '<input type="file" class="'.(isset($field['class']) ? $field['class'].' ' : '').'file" name="field'.$number.'" data-mimes="'.$field['mimes'].'" data-size="'.$field['max_size'].'" >';                    
					if(!empty($field['allowed_ext'])){
						echo '<span>'.str_replace('|', ', ', $field['allowed_ext']).'</span>';
					}
				break;
		      } ?>
			
			<?php echo form_error('field'.$number); ?>

	    </div>

	    <?php } ?>

	    <?php if($captcha['enabled'] == 'yes'){ ?>
	    <br/>
	    <div class="row captcha" >
			<label>&nbsp;</label>
			<img id="siimage" style="margin-right: 15px;width: 200px;height: 70px;" src="<?php echo base_url();?>plugins/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" />	
			<object type="application/x-shockwave-flash" data="<?php echo base_url();?>plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?php echo base_url();?>/plugins/securimage/securimage_play.php" width="19" height="19" >
				<param name="movie" value="<?php echo base_url();?>plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?php echo base_url();?>/plugins/securimage/securimage_play.php" />
			</object>		
			&nbsp;
			<a tabindex="-1" style="border-style: none;" href="#" id="refresh_image" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo base_url();?>plugins/securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
				<img src="<?php echo base_url();?>plugins/securimage/images/refresh.gif" alt="Reload Image" onclick="this.blur()" align="bottom" border="0" />
			</a>
			<br />
	    </div>

	    <div class="row" >
			<label><?php echo lang('label_cf_enter_code');?>: *</label>
			<input type="text" name="ct_captcha" class="required captcha_input" />
			<?php echo form_error('ct_captcha'); ?>
	    </div>
	    <br/>
	    <?php } ?>

	    <div class="buttons">
			<button class="primary" type="submit" name="send" value="1" ><?php echo lang('label_cf_send');?></button>
			<button type="reset" ><?php echo lang('label_cf_clear');?></button>
	    </div>

	</form>
	  
	<?php if(!empty($contact_form['text_under'])){ ?>
	<div class="text_under" >
	    <?php echo $this->Article->parceText($contact_form['text_under']);?>
	</div>
	<?php } ?>

<?php } ?>