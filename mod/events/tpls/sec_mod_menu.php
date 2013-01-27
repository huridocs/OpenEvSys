<?php
$action = $_GET['act'];

$id = (isset($_GET['eid'])) ? $_GET['eid'] : $_SESSION['eid'];
if (isset($id)) {
    ?>
    <ul class="nav nav-tabs tabnav"> 

        <li class="<?php if ($action == "get_event") echo "active" ?>"><a  href="<?php get_url('events', 'get_event') ?>"><?php echo _t('EVENT_DESCRIPTION') ?></a>
        </li>

        <li class="<?php if ($action == "vp_list") echo "active" ?>"><a href="<?php get_url('events', 'vp_list', null, array('eid' => $id)) ?>" >
                <?php echo _t('VICTIMS_AND_PERPETRATORS') ?>
            </a></li>
        <li class="<?php if ($action == "src_list") echo "active" ?>"><a href="<?php get_url('events', 'src_list', null, array('eid' => $id)) ?>"  >
                <?php echo _t('SOURCES') ?>
            </a></li>
        <li class="<?php if ($action == "intv_list") echo "active" ?>"><a href="<?php get_url('events', 'intv_list', null, array('eid' => $id)) ?>">
                <?php echo _t('INTERVENTIONS') ?>
            </a></li>
        <li class="<?php if ($action == "coe_list") echo "active" ?>"><a href="<?php get_url('events', 'coe_list', null, array('eid' => $id)) ?>" >
                <?php echo _t('CHAIN_OF_EVENTS') ?>
            </a></li>
        <li class="<?php if ($action == "doc_list") echo "active" ?>"><a href="<?php get_url('events', 'doc_list', null, array('eid' => $id)) ?>" >
                <?php echo _t('DOCUMENTS') ?>
            </a></li>
        <li class="<?php if ($action == "audit") echo "active" ?>"><a href="<?php get_url('events', 'audit', null, array('eid' => $id)) ?>"  >
                <?php echo _t('AUDIT_LOG') ?>
            </a></li>
        <?php
        global $event;
        if ($event->confidentiality == 'y') {
            ?>
            <li class="permission <?php if ($action == "permissions") echo "active" ?>"><a href="<?php get_url('events', 'permissions', null, array('eid' => $id)) ?>"  >
            <?php echo _t('PERMISSIONS') ?>
                </a></li>
    <?php } ?>  
    </ul>
    <?php
}
?>


</ul>
