
<div>
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="article_title" ><?=$article['title_'.$this->lang_lib->get()];?></div>
    <?php } ?>
    <?= $this->Article->parceText(@$article['text_'.$this->lang_lib->get()]);?>
</div>
