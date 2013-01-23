<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_article');?>:</label></th>
    <td> 
        <?php $article_id = set_value('params[article_id]', isset($params['article_id']) ? $params['article_id'] : "");
              $article = $article_id != "" ? $this->Article->getDetails($article_id, 'title') : ""; ?>
        <input class="article" type="hidden" name="params[article_id]" id="article"  value="<?=$article_id;?>" >
        <input class="article" type="text" readonly id="article_name" value="<?=$article;?>" style="width: 58%">
        
        <a href  = "<?=site_url('articles/index/simple_ajax');?>" 
           class = "load_jquery_ui_iframe"
           title = "<?=lang('label_select').' '.lang('label_article');?>"
           lang  = "dialog-select-article" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                          class = "clear_jquery_ui_inputs"
                                                                                          lang  = "article" ><?=lang('label_clear');?></a>
           
    </td>
</tr>