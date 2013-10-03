
<div>

    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="article_title" >
        <?php echo $article['title'];?>
    </div>
    <?php } ?>
    
    <div class="article_content" >
        <?php echo  $this->Article->parceText($article['text']);?>
    </div>
    
</div>
