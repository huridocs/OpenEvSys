<div id="session">
<?php /*
<strong> This is an OpenEvsys Testing Server: lots of things don't work!
Report them <a href="https://bugs.launchpad.net/openevsys/+filebug" >here</a> or email <a href="mailto:tom.longley@huridocs.org" >us</a>
  &nbsp; | &nbsp; </strong>
*/?>
    <strong><?php echo htmlspecialchars($_SESSION['username']) ?></strong><span>&nbsp;|&nbsp;</span>
    <a href="<?php get_url('home','edit_user') ?>"><?php echo _t('MY_PREFERENCES') ?></a><span>&nbsp;|&nbsp;</span>
    <!-- <a id="help_switch_link" href="javascript://" onclick="setHelpStatus();"><?php echo _t('DISABLE_HELP')?></a><span>&nbsp;|&nbsp;</span> -->
    <a href="?act=logout"><?php echo _t('SIGN_OUT')?></a>
</div>
