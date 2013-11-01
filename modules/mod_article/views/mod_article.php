
<div>

    <?php if($article['show_title'] == 'yes'){ ?>
    <h4 class="article_title" ><?php echo $article['title'];?></h4>
    <?php } ?>
    
    <div class="article_content" >
        <?php echo  $this->Article->parceText($article['text']);?>
    </div>
    
</div>
