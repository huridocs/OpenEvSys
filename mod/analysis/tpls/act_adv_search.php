<div class="searchPanel">
    <div class="panel" id="qb-panel">
        <a id="qb-count-toggle"><?php echo _t('COUNT') ?></a>
        <a id="qb-query-toggle">&nbsp;&nbsp;</a>
        <?php if(isset($query)){ ?>
        <strong id="query-name"><?php echo $query->name ?></strong>
        <?php }else{ ?>
        <strong id="query-name"><?php echo _t('UNTITLED_QUERY') ?></strong>
        <?php }?>
        <form class="form-horizontal"  onsubmit="return false">
            <fieldset id="qb-group-by" style="display:none">
                <legend><?php echo _t('COUNT') ?></legend>
                <ol id="qb-group-list"></ol>
            </fieldset>
            <fieldset id="qb-conditions" >
              <!--  <legend><?php echo _t('SEARCH_CONDITIONS') ?></legend> -->
                <ol id="query_builder"></ol>
            </fieldset>
            <div class="clearfix" ></div>
            <button    id="qb-search-but" class="qb-but btn" ><i class="icon-ok"></i> <?php echo _t('SEARCH')?></button>
            <button   id="qb-clear-but" class="qb-but btn" ><i class="icon-remove"></i> <?php echo _t('CLEAR')?></button> 
        </form>
    </div>
</div>
<div class='resultPanel' style="display:none ;">
   <div id="fg-menu" style="float: left;">
		<a tabindex="0" href="#news-items" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="hierarchy"><span class="ui-icon ui-icon-triangle-1-s"></span><?php echo _t('ADD_FIELDS_TO_SEARCH_RESULTS') ?></a>
		<div id="news-items" class="hidden">
    		<ul></ul>	
    	</div>	
	</div>
    <ul id="toolbar">
        <li><a id="tb-save" title="<?php echo _t('SAVE') ?>"><?php echo  _t('SAVE') ?></a></li>
        <li class="separator">&nbsp;<?php echo _t('EXPORT__') ?></li>
        <li><a target="_blank" href="index.php?mod=analysis&amp;act=adv_csv&amp;stream=text" id="tb-ex-cvs" title="<?php echo _t('CSV') ?>"><?php echo _t('CSV') ?></a></li>
        <li><a target="_blank" href="index.php?mod=analysis&amp;act=adv_export_spreadsheet&amp;stream=text" id="tb-ex-ss"><?php echo _t('SPREADSHEET') ?></a></li>
        <li><a target="_blank" href="index.php?mod=analysis&amp;act=adv_report" id="tb-ex-rpt"><?php echo _t('REPORT') ?></a></li>
      <!--  <li class="separator">&nbsp;</li>
        <li><a id="tb-txt" class="selected"><?php echo _t('TEXT') ?></a></li>
        <li><a id="tb-huri" ><?php echo _t('HURICODE') ?></a></li> -->
    </ul>
<div class='clecrfix'></div>
<div id="jqgrid" style="float: left; clear:both;">
		<table id="list123" class="scroll"></table> 
		<div id="pager" class="scroll" style="text-align:center;"></div> 
	</div>
	
	<table id="list1234" class="scroll"></table> 
	<div id="pager1" class="scroll" style="text-align:center;"></div> 
</div>
<div style="display:none">
    <form class="form-horizontal"  id="qb-save-query" action="<?php echo get_url('analysis','save_query',null)?>" onsubmit="return false;">
    <h3><?php echo _t('SAVE_QUERY') ?></h3><br />
    <label><?php echo _t('NAME') ?></label> 
    <input type="text"  id="query_name"  name="query_name" /><br />
    <label><?php echo _t('DESCRIPTION') ?></label> 
    <textarea type="text" name="query_desc" id="query_desc" rows="4" cols="30" ></textarea><br />
    <button id="qb-qs-save" type="button"   class="btn"><i class="icon-ok"></i> <?php echo _t('SAVE') ?></button>
    <button id="qb-qs-cancle" type="button" class="btn" ><i class="icon-stop"></i> <?php echo _t('CANCEL'); ?></button>
     
</div>
<script language="javascript">
    var as = new advSearch();
    as.init();

    var query = '<?php echo $_GET['query'] ?>';

</script>
