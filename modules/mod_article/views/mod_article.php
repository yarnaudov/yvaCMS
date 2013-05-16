
<div>

    <div class="article_title" >
        <?=$article['title'];?>
    </div>
    
    <div class="article_content" >
        <?= $this->Article->parceText($article['text']);?>
    </div>
    
</div>
