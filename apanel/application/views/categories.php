<tr>	      			
    <th><label><?=lang('label_category');?>:</label></th>
    <td>
	
	<div class="menu_list" >
            <table class="menu_list" cellpadding="0" cellspacing="0" >
                                                      
		<?php $categories = set_value('categories', isset($categories) ? $categories : "");
		      foreach($categories_dropdown as $category_id => $category){ 
			  $checked = "";
			  if(@in_array($category_id, $categories)){
			      $checked = "checked";
			  } ?>

		<tr>
		    <td style="width: 1%;" >
			<input class="required categories" type="checkbox" <?=$checked;?> name="categories[]" id="category<?=$category_id;?>" value="<?=$category_id;?>" >
		    </td>
		    <td>
			<label for="category<?=$category_id;?>" ><?=$category;?></label>
		    </td>
		</tr>

		<?php } ?>
		
            </table>
        </div>

        <script type="text/javascript" >
         
            $(document).ready(function() {
		
                if($('#custom_fields').length == 1){
                    		    
                    $('input.categories').click(function(){
		
			var posts = new Object();
			posts.extension      = '<?=$this->extension;?>';
			posts.model          = '<?=$this->model;?>';
			posts.element_id     = '<?=$this->element_id;?>';
			posts.extension_keys = new Array();
			
			$('input.categories:checked').each(function(){
			    posts.extension_keys.push($(this).val());
			});
			
                        $.post('<?=site_url('home/ajax/load_custom_fields');?>', posts, function(data){
			    						    
                            $('#custom_fields').css('display', 'none');
			    document.getElementById('custom_fields').innerHTML = data;
                            $('#custom_fields').toggle('slow');

                        });

                    });
                }
                
            });
            
        </script>
        
    </td>
</tr>