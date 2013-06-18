
<!--[if IE]><script language="javascript" type="text/javascript" src="<?=base_url('js/jqplot/excanvas.js');?>"></script><![endif]-->

<div id="chart1" style="height:300px; width:100%;"></div>

<script class="code" type="text/javascript">
  var line1= JSON.parse('<?=json_encode($line1);?>');
  console.log(line1);
  var plot1 = $.jqplot ('chart1', [line1], {
      axes:{
	  xaxis:{
	      renderer: $.jqplot.DateAxisRenderer,
	      tickOptions:{
		  formatString:'%b&nbsp;%#d'
	      } 
          },
	  yaxis:{
          }
      },
      highlighter: {
          show: true,
          sizeAdjust: 7.5
      },
      cursor: {
          show: false
      }
  });
</script>