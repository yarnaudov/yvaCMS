<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="article_title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="article_content" >
        <?=$article['text'];?>
    </div>
   
    <?php if($article['params']['show_comments'] == 'yes'){
	      $this->load->view('content/article_comments');
          } ?>
    
</div>