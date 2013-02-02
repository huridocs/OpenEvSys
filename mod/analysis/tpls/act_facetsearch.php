<?php
	global $conf;
?>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
-->
<script type="text/javascript" src="res/jquery/jquery-ui-map/jquery.ui.map.js"></script>
<script type="text/javascript" src="res/markerclusterer/markerclusterer_packed.js"></script>
<script type="text/javascript" src="res/js/jquery.facetsearch.js"></script>
	
<div class="panel">
	
    <div class="facetsearchcontainer"></div>

</div>

  <script type="text/javascript">
jQuery(document).ready(function($) {
  $('.facetsearchcontainer').facetsearch({
    search_url: 'index.php?mod=analysis&act=facetsearchresults&',
    facets: [
        {'field':'year', 'display': 'year'}, 
        {'field':'publisher', 'display': 'publisher'}
    ],
    paging: {
      from: 0,
      size: 10
    }
  });
 
});
  </script>