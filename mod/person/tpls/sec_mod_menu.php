<?php
$action = $_GET['act'];

$id = (isset($_GET['pid'])) ? $_GET['pid'] : $_SESSION['pid'];
if (isset($id)) {
    ?>
    <ul class="nav nav-tabs tabnav"> 
        <li class="<?php if ($action == "person") echo "active" ?>"><a  href="<?php get_url('person', 'person') ?>"><?php echo _t('PERSON_RECORDS_S_') ?></a>
        </li>

        <li class="<?php if ($action == "address_list") echo "active" ?>"><a href="<?php get_url('person', 'address_list', null) ?> " >
                <?php echo _t('PERSON_ADDRESS_ES_') ?>
            </a></li>
        <li class="<?php if ($action == "biography_list") echo "active" ?>"><a href="<?php get_url('person', 'biography_list', null) ?>" >
                <?php echo _t('BIOGRAPHIC_DETAIL_S_') ?>
            </a></li>
        <li class="<?php if ($action == "role_list") echo "active" ?>">	<a href="<?php get_url('person', 'role_list', null) ?>"  >
                <?php echo _t('ROLE_LIST') ?>
            </a></li>
        <li class="<?php if ($action == "audit_log") echo "active" ?>">	<a href="<?php get_url('person', 'audit_log', null) ?>" >
                <?php echo _t('AUDIT_LOG') ?>
            </a></li>
        <?php global $person;
        if ($person->confidentiality == 'y') {
            ?>
            <li class="<?php if ($action == "permissions") echo "active" ?> permission">	<a href="<?php get_url('person', 'permissions', null) ?>" >
            <?php echo _t('PERMISSONS') ?>
                </a> </li>       
    <?php } ?>

    </ul>
    <?php
}
?>
