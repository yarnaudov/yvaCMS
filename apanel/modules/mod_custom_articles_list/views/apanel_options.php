
<?php $this->load->model('Article');
      $articles_by_category = $this->Article->getArticlesByCategory(); ?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_articles');?>:</label></th>
    <td>
        <div class="menu_list" >
            <table class="menu_list" cellpadding="0" cellspacing="0" >    
            <?php foreach($articles_by_category as $category => $articles_arr){ ?>

                <tr>
                    <th colspan="2" ><?php echo $category;?></th>
                </tr>
                                                      
                <?php $custom_articles = set_value('params[custom_articles]', isset($params['custom_articles']) ? $params['custom_articles'] : "");
                      foreach($articles_arr as $article_id => $article){ 
                          $checked = "";
                          if(@in_array($article_id, $custom_articles)){
                              $checked = "checked";
                          } ?>

                <tr>
                    <td style="width: 1%;" >
                        <input type="checkbox" class="custom_articles" <?php echo $checked;?> name="params[custom_articles][]" id="custom_article<?php echo $category;?><?php echo $article_id;?>" value="<?php echo $article_id;?>" >
                    </td>
                    <td>
                        <label for="custom_article<?php echo $category;?><?php echo $article_id;?>" ><?php echo $article;?></label>
                    </td>
                </tr>

                <?php } ?>
                
                <tr><td colspan="2" >&nbsp;</td></tr>
                
            <?php } ?>
            </table>
	    
	    <script type="text/javascript">		
		$('.custom_articles').live('click', function(){
		    $('.custom_articles[value='+$(this).val()+']').attr('checked', $(this).is(':checked'));
		});		
	    </script>
	    
        </div>
    </td>
</tr>