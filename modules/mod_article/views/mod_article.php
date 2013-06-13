
<div>

    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="article_title" >
        <?=$article['title'];?>
    </div>
    <?php } ?>
    
    <div class="article_content" >
        <?= $this->Article->parceText($article['text']);?>
    </div>
    
</div>
