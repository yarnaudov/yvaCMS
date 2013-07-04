
<script type="text/javascript" src="<?=base_url('modules/mod_poll/js/mod_poll.js');?>" ></script>

<form action="<?=site_url('polls/vote');?>" method="post" >

<ul class="poll<?=$poll['id'];?>" >

    <li><?=$poll['title'];?></li>

    <?php foreach($poll['answers'] as $answer){ ?>
    <li>
        <input type="radio" name="answers<?=$poll['id'];?>" id="answer<?=$answer['id'];?>" value="<?=$answer['id']; ?>" class="<?=$poll['id'];?>" >
        <label for="answer<?=$answer['id'];?>" ><?=$answer['title'];?></label>
    </li>
    <?php } ?>

    <li>
        <div class="actions" >
            <button type="submit" name="vote" disabled ><?=lang('label_mod_poll_vote');?></button>
            <a href="<?=current_url();?>?show_votes=<?=$poll['id'];?>" class="show_votes" ><?=lang('label_mod_poll_show_votes');?></a>
        </div>
        <div class="loading" style="display: none;" >
            <span><?=lang('label_mod_poll_please_wait');?></span>
            <img src="<?=base_url('modules/mod_poll/images/loading.gif');?>" alt="loading..." >
        </div>
    </li>

</ul>

</form>
