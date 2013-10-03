
<script type="text/javascript" src="<?php echo base_url('modules/mod_poll/js/mod_poll.js');?>" ></script>

<form action="<?php echo site_url('polls/vote');?>" method="post" >

<ul class="poll<?php echo $poll['id'];?>" >

    <li><?php echo $poll['title'];?></li>

    <?php foreach($poll['answers'] as $answer){ ?>
    <li>
        <input type="radio" name="answers<?php echo $poll['id'];?>" id="answer<?php echo $answer['id'];?>" value="<?php echo $answer['id']; ?>" class="<?php echo $poll['id'];?>" >
        <label for="answer<?php echo $answer['id'];?>" ><?php echo $answer['title'];?></label>
    </li>
    <?php } ?>

    <li>
        <div class="actions" >
            <button type="submit" name="vote" disabled ><?php echo lang('label_mod_poll_vote');?></button>
            <a href="<?php echo current_url();?>?show_votes=<?php echo $poll['id'];?>" class="show_votes" ><?php echo lang('label_mod_poll_show_votes');?></a>
        </div>
        <div class="loading" style="display: none;" >
            <span><?php echo lang('label_mod_poll_please_wait');?></span>
            <img src="<?php echo base_url('modules/mod_poll/images/loading.gif');?>" alt="loading..." >
        </div>
    </li>

</ul>

</form>
