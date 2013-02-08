<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="content" >
        <?=$this->Article->parceText(@$article['text']);?>
    </div>
    
</div>