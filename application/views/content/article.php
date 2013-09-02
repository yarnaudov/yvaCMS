<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <h4 class="article_title" ><?=$article['title'];?></h4>
    <?php } ?>
    
    <div class="article_content" >
        <?=$article['text'];?>
    </div>
   
    <?php if(isset($article['params']['show_comments']) && $article['params']['show_comments'] == 'yes'){
	      $this->load->view('content/article_comments');
          } ?>
    
</div>