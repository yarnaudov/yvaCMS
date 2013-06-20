
<!--[if IE]><script language="javascript" type="text/javascript" src="<?=base_url('js/jqplot/excanvas.js');?>"></script><![endif]-->

<form name="list" action="<?=current_url(true);?>" method="post" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconArticles_25.png');?>" >
            <span><?=lang('label_articles');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?=lang('label_statistics');?>Statistics</span>
        </div>
	
	<div class="actions" >
            <a href="<?=site_url('articles');?>" class="styled cancel" ><?=lang('label_cancel');?></a>            
	</div>
        
    </div>
    <!-- end page header -->
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    
    <!-- start page content -->
    <div id="page_content" >

	<div id="filter_content" >
		
            <div class="search" >
		<label><?=lang('label_from');?>:</label>
		<input type="text" class="start_date datepicker" name="filters[start_date]" value="<?=$filters['start_date'];?>" >
		<label><?=lang('label_to');?>:</label>
		<input type="text" class="end_date datepicker" name="filters[end_date]" value="<?=$filters['end_date'];?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_show');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[banner]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_banner');?> - </option>
                    <?=create_options_array($banners, isset($filters['banner']) ? $filters['banner'] : "");?>
                </select>

            </div>
		
	</div>
	
	<br/>
	<div id="chart1" style="height:300px;margin-right: -7px;"></div>
	
	<script class="code" type="text/javascript">
	    
	    var line1 = JSON.parse('<?=json_encode($line1);?>');
	    var line2 = JSON.parse('<?=json_encode($line2);?>');
	    //console.log(line1);
	    var plot1 = $.jqplot ('chart1', [line1, line2], {
		seriesColors: [ "#0D82DB", "#EAA228"],
		axes:{
		    xaxis:{
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions:{
                            formatString:'%d/%m',
                            markSize: 1,
			    fontSize: '9pt'
                        },
			min: '<?=$filters['start_date'];?>',
			max: '<?=$filters['end_date'];?>',
			tickInterval: '1 day'
		    },
		    yaxis:{
                        tickOptions:{
                            markSize: 0,
			    fontSize: '9pt'
                        },
                        min: 0,
                        max: <?=($max_views+1);?>
		    }
		},
		series:[
		{
                    lineWidth: 1,
		    label: 'Импресии'
                },
		{
		    lineWidth: 1,
		    label: 'Кликове'
		}],
		highlighter: {
		    show: true,
		    sizeAdjust: 10.0,
		    lineWidthAdjust: 5.5
		},
		cursor: {
		    show: false
		},
                grid: {
                    borderWidth: 0,
                    shadow: false
                },
		legend: {
		    show: true,
		    location: 'ne',     // compass direction, nw, n, ne, e, se, s, sw, w.
		    xoffset: 12,        // pixel offset of the legend box from the x (or x2) axis.
		    yoffset: 12,        // pixel offset of the legend box from the y (or y2) axis.
		}
	    });
	    
	    $(window).on('resize load', function() {
		plot1.replot();
	    });

	    $( ".start_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		  $( ".end_date" ).datepicker( "option", "minDate", selectedDate );
		}
	    });
	    $( ".end_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		maxDate: 0,
		onClose: function( selectedDate ) {
		  $( ".start_date" ).datepicker( "option", "maxDate", selectedDate );
		}
	    });
	    
	    $( ".start_date" ).datepicker( "option", "maxDate", "<?=$filters['end_date'];?>" );
	    $( ".end_date" ).datepicker( "option", "minDate", "<?=$filters['start_date'];?>" );
		    
	</script>
	
        
        <style>
            .search{
                float: right !important;
            }
            .search label{
                float: left;
                display: block;
                margin: 3px 3px 0 10px;
            }
            .filter{
                float: left !important;
            }
        </style>
        
        <br/><br/>
        <table class="list" cellpadding="0" cellspacing="2" >
            
            <tr>
                <th style="width:1%;"  rowspan="2" >#</th>
                <th style="width:10%;" rowspan="2" ><?=lang('label_date');?></th>
                <th style="width:7%;"  rowspan="2" ><?=lang('label_read');?></th>
                <th style="width:82%;" colspan="5" ><?=lang('label_details');?></th>
            </tr>
            
            <tr>
		<th style="width:6%;"  ><?=lang('label_time');?></th>
                <th style="width:10%;" >IP</th>
                <th style="width:12%;" ><?=lang('label_user_agent');?></th>
                <th style="width:30%;" ><?=lang('label_page_url');?></th>
                <th style="width:42%;" ><?=lang('label_user_refferer');?></th>
            </tr>
            
            <?php foreach($statistics as $numb => $statistic){ ?>
            <tr>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=($numb+1);?></td>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=$statistic['date'];?></td>
                <td rowspan="<?=$statistic['views']+1;?>" ><?=$statistic['views'];?></td>
            </tr>
            
		<?php foreach($statistic['details'] as $detail){ ?>
		<tr>
		    <td><?=end(explode(" ", $detail['created_on']));?></td>
		    <td>
                        <a href="http://whatismyipaddress.com/ip/<?=$detail['ip'];?>" target="_blank" ><?=$detail['ip'];?></a>
                    </td>
		    <td><?=$detail['user_agent'];?></td>
		    <td style="text-align: left;" >
                        <a href="<?=$detail['page_url'];?>" target="_blank" ><?=$detail['page_url'];?></a>
                    </td>
		    <td style="text-align: left;" >
                        <a href="<?=$detail['user_referrer'];?>" target="_blank" ><?=$detail['user_referrer'];?></a>
                    </td>
		</tr>
		<?php } ?>
                
            <?php } ?>
		
            <tr>
                <th colspan="2" ><?=lang('label_total');?></th>
                <th><?=$total_views;?></th>
                <th colspan="6" ></th>
            </tr>
                
	    <?php if(count($statistics) == 0){ ?>
	    <tr>
                <td colspan="8" >No data to display</td>
            </tr>
	    <?php } ?>
            
        </table>

    </div>
    <!-- end page content -->
    
</form>