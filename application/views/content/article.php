<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="content" >
        <?=$article['text'];?>
    </div>
   
    <?=$this->load->view('content/article_comments');?>
    
</div>