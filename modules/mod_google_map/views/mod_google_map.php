<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
<script type="text/javascript" >
  var map_settings = JSON.parse('<?=json_encode($params, JSON_HEX_APOS | JSON_HEX_QUOT);?>');
</script>
<script type="text/javascript" src="<?=base_url('modules/mod_google_map/js/map_options.js');?>" ></script>

<div id="map_canvas" style="height: 100%;width: 100%;" ></div>