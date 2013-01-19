
<div>
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="article_title" ><?=$article['title_'.get_lang()];?></div>
    <?php } ?>
    <?= $this->Article->parceText(@$article['text_'.get_lang()]);?>
</div>
