
<script type="text/javascript" src="<?php echo base_url('modules/mod_poll/js/mod_poll.js');?>" ></script>

<ul class="poll<?php echo $poll['id'];?>" >
	
    <li><?php echo $poll['title'];?></li>

    <?php foreach($poll['answers'] as $key => $answer){ ?>
    <li>
        <span class="number" ><?php echo $key+1;?>.</span>
        <?php echo $answer['title'];?> - 
        <?php echo $votes == 0 ? 0 : round(($answer['votes']*100)/$votes, 1);?>%
    </li>
    <?php } ?>
	  
    <li><?php echo lang('label_mod_poll_total_votes');?>: <?php echo $votes;?></li>

    <?php if($answer_id != false){ ?>
    <li>         
        <?php echo lang('label_mod_poll_you_already_voted_for');?>:
        <span class="poll_answer" ><?php echo poll::getAnswer($answer_id, 'title');?></span>
    </li>
    <?php }else{ ?>
    <li>	      
        <div class="actions" >
            <a href="<?php echo current_url();?>" class="hide_votes" ><?php echo lang('label_mod_poll_back_to_poll');?></a>
        </div>
        <div class="loading" style="display: none;" >
            <span><?php echo lang('label_mod_poll_please_wait');?></span>
            <img src="<?php echo base_url('modules/mod_poll/images/loading.gif');?>" alt="loading..." >
        </div>
    </li>
    <?php } ?>
	  
</ul>
