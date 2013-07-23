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
			<input class="required" type="checkbox" style="width:16px;" <?=$checked;?> name="categories[]" id="category<?=$category_id;?>" value="<?=$category_id;?>" >
		    </td>
		    <td>
			<label for="category<?=$category_id;?>" ><?=$category;?></label>
		    </td>
		</tr>

		<?php } ?>
		
            </table>
        </div>
	
        <select name="category" >
            <?=create_options_array($categories_dropdown, set_value('category', isset($category_id) ? $category_id : ""));?>
        </select>

        <script type="text/javascript" >
            
            $(document).ready(function() {
                
                if($('#custom_fields').length == 1){
                    
                    $('select[name=category]').change(function(){

                        $.get('<?=site_url('home/ajax/load_custom_fields');?>?extension=<?=$this->extension;?>&extension_key='+$(this).val(), function(data){
			    
			    if(data.search('script') != -1){
                                parent.$('.required').each(function(){
                                    $(this).removeClass('required');
                                });
                                parent.$('form').submit();
                                return;
                            }
			    
                            $('#custom_fields').css('display', 'none');
                            $('#custom_fields').html(data);
                            $('#custom_fields').toggle('slow');
                        });

                    });
                }
                
            });
            
        </script>
        
    </td>
</tr>