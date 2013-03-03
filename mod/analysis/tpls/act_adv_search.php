<div class="searchPanel">
    <div class="panel" id="qb-panel">
        <a id="qb-count-toggle"><?php echo _t('COUNT') ?></a>
        <a id="qb-query-toggle">&nbsp;&nbsp;</a>
        <?php if (isset($query)) { ?>
            <strong id="query-name"><?php echo $query->name ?></strong>
        <?php } else { ?>
            <strong id="query-name"><?php echo _t('UNTITLED_QUERY') ?></strong>
        <?php } ?>
        <form class="form-horizontal"  onsubmit="return false">
            <fieldset id="qb-group-by" style="display:none">
                <legend><?php echo _t('COUNT') ?></legend>
                <ol id="qb-group-list"></ol>
            </fieldset>
            <fieldset id="qb-conditions" >
              <!--  <legend><?php echo _t('SEARCH_CONDITIONS') ?></legend> -->
                <div id="query_builder"></div>
            </fieldset>
            <div class="clearfix" ></div>
            <button    id="qb-search-but" class="qb-but btn" ><i class="icon-ok"></i> <?php echo _t('SEARCH') ?></button>
            <button   id="qb-clear-but" class="qb-but btn" ><i class="icon-remove"></i> <?php echo _t('CLEAR') ?></button> 
        </form>
    </div>
</div>
<div class='resultPanel' style="display:none ;">

    <div id="toolbar2" style="display:none">
        <div id="fg-menu" style="float: left;" class="span5">
            <a tabindex="0" href="#news-items" class="btn" id="hierarchy"><?php echo _t('ADD_FIELDS_TO_SEARCH_RESULTS') ?> <i class="icon-chevron-down"></i></a>
            <div id="news-items" class="hidden">
                <ul></ul>	
            </div>	
        </div>
        <div class="span7">
            <a id="tb-save" href="#saveModal" data-toggle="modal" role="button" title="<?php echo _t('SAVE') ?>"><?php echo _t('SAVE') ?></a>
            <span style="border-left: 1px solid #999;">&nbsp;</span>
            <?php echo _t('EXPORT__') ?>
            <a target="_blank" href="index.php?mod=analysis&amp;act=adv_csv&amp;stream=text" id="tb-ex-cvs" title="<?php echo _t('CSV') ?>"><?php echo _t('CSV') ?></a>
            <a target="_blank" href="index.php?mod=analysis&amp;act=adv_export_spreadsheet&amp;stream=text" id="tb-ex-ss"><?php echo _t('SPREADSHEET') ?></a>
            <a target="_blank" href="index.php?mod=analysis&amp;act=adv_report" id="tb-ex-rpt"><?php echo _t('REPORT') ?></a>
        </div>
    </div>
    <div class='clecrfix'></div>
    <div class="row-fluid">
        <table style="width:100%" id="datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
            </table> 
        <div id="pager" class="scroll" style="text-align:center;"></div> 
    </div>

    <table id="list1234" class="scroll"></table> 
    <div id="pager1" class="scroll" style="text-align:center;"></div> 
</div>
<div style="display:none" id="saveModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal"  id="qb-save-query" action="<?php echo get_url('analysis', 'save_query', null) ?>" onsubmit="return false;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php echo _t('SAVE_QUERY') ?></h3>
        </div>
        <div class="modal-body">
            <p>
                <div class="control-group">
    <label class="control-label"><?php echo _t('NAME') ?></label>
    <div class="controls"><input type="text"  id="query_name"  name="query_name" /></div>
  </div>
  <div class="control-group">
    <label class="control-label"><?php echo _t('DESCRIPTION') ?></label>
    <div class="controls"> <textarea type="text" name="query_desc" id="query_desc" rows="4" cols="30" ></textarea> </div>
  </div>
        </div>
        <div class="modal-footer">

            <button id="qb-qs-cancle" type="button" class="btn" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove-circle"></i> <?php echo _t('CANCEL'); ?></button>
            <button id="qb-qs-save" type="button" role="button"  class="btn btn-primary"><i class="icon-ok icon-white"></i> <?php echo _t('SAVE') ?></button>
        </div>
</div>
<script language="javascript">
    var as = advSearch.getInstance();
    as.init();

    var query = '<?php echo $_GET['query'] ?>';

</script>
