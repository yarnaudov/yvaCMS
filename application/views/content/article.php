<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="content" >
        <?=$article['text'];?>
    </div>
   
    <?php if($article['params']['show_comments'] == 'yes'){
	      $this->load->view('content/article_comments');
          } ?>
    
</div>