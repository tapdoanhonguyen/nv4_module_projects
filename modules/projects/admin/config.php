<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];
$groups_list = nv_groups_list();

// $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('vi', " . $db->quote($module_name) . ", 'array_status', '')");

$data = array();
if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['default_status'] = $nv_Request->get_typed_array('default_status', 'post', 'int');
    $data['default_status'] = !empty($data['default_status']) ? implode(',', $data['default_status']) : '';
	$data['groups_director'] = $nv_Request->get_typed_array('groups_director', 'post', 'int');
    $data['groups_director'] = !empty($data['groups_director']) ? implode(',', $data['groups_director']) : '';
    $data['groups_manage'] = $nv_Request->get_typed_array('groups_manage', 'post', 'int');
    $data['groups_manage'] = !empty($data['groups_manage']) ? implode(',', $data['groups_manage']) : '';
	$data['groups_account'] = $nv_Request->get_typed_array('groups_account', 'post', 'int');
    $data['groups_account'] = !empty($data['groups_account']) ? implode(',', $data['groups_account']) : '';
	$data['groups_hr_department'] = $nv_Request->get_typed_array('groups_hr_department', 'post', 'int');
    $data['groups_hr_department'] = !empty($data['groups_hr_department']) ? implode(',', $data['groups_hr_department']) : '';
	$data['groups_sales'] = $nv_Request->get_typed_array('groups_sales', 'post', 'int');
    $data['groups_sales'] = !empty($data['groups_sales']) ? implode(',', $data['groups_sales']) : '';
	$data['groups_maketting'] = $nv_Request->get_typed_array('groups_maketting', 'post', 'int');
    $data['groups_maketting'] = !empty($data['groups_maketting']) ? implode(',', $data['groups_maketting']) : '';
	$data['groups_design'] = $nv_Request->get_typed_array('groups_design', 'post', 'int');
    $data['groups_design'] = !empty($data['groups_design']) ? implode(',', $data['groups_design']) : '';
	$data['groups_technology'] = $nv_Request->get_typed_array('groups_technology', 'post', 'int');
    $data['groups_technology'] = !empty($data['groups_technology']) ? implode(',', $data['groups_technology']) : '';
    $array_status = $nv_Request->get_array('array_status', 'post');
	$array_status_reposition = array();
	$num_status = count($array_status['id']);
	for($i = 0; $i < $num_status; $i++){
		$array_status_reposition[$i]['id'] =  $array_status['id'][$i];
		$array_status_reposition[$i]['txt'] =  $array_status['txt'][$i];
	}
	$data['array_status'] = $array_status_reposition;
    $data['array_status'] = !empty($data['array_status']) ? serialize($data['array_status']) : '';
	$array_project_status = $nv_Request->get_array('array_project_status', 'post');
	$array_project_status_reposition = array();
	$num_array_project_status = count($array_project_status['id']);
	for($i = 0; $i < $num_array_project_status; $i++){
		$array_project_status_reposition[$i]['id'] =  $array_project_status['id'][$i];
		$array_project_status_reposition[$i]['txt'] =  $array_project_status['txt'][$i];
	}
	$data['array_project_status'] = $array_project_status_reposition;
    $data['array_project_status'] = !empty($data['array_project_status']) ? serialize($data['array_project_status']) : '';
    // print_r($data['array_status']);die();

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod('settings');

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);

$groupsd = !empty($array_config['groups_director']) ? explode(',', $array_config['groups_director']) : array();
$groups = !empty($array_config['groups_manage']) ? explode(',', $array_config['groups_manage']) : array();
$groupsa = !empty($array_config['groups_account']) ? explode(',', $array_config['groups_account']) : array();
$groupsh = !empty($array_config['groups_hr_department']) ? explode(',', $array_config['groups_hr_department']) : array();
$groupss = !empty($array_config['groups_sales']) ? explode(',', $array_config['groups_sales']) : array();
$groupsm = !empty($array_config['groups_maketting']) ? explode(',', $array_config['groups_maketting']) : array();
$groupsds = !empty($array_config['groups_design']) ? explode(',', $array_config['groups_design']) : array();
$groupst = !empty($array_config['groups_technology']) ? explode(',', $array_config['groups_technology']) : array();
$groupsc = !empty($array_config['groups_customer']) ? explode(',', $array_config['groups_customer']) : array();
$groupsclb = !empty($array_config['groups_collaborators']) ? explode(',', $array_config['groups_collaborators']) : array();
$groupssh = !empty($array_config['groups_storehouse']) ? explode(',', $array_config['groups_storehouse']) : array();
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groups) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groups');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsd) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsd');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsa) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsa');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsh) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsh');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupss) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupss');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsm) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsm');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsds) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsds');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupst) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupst');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupssh) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupssh');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsclb) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsclb');

}
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groupsc) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groupsc');

}

$default_status = !empty($array_config['default_status']) ? explode(',', $array_config['default_status']) : array();
foreach ($array_status as $index => $value) {
    $sl = in_array($index, $default_status) ? 'checked="checked"' : '';
    $xtpl->assign('STATUS', array(
        'index' => $index,
        'value' => $value['txt'],
        'checked' => $sl
    ));
    $xtpl->parse('main.status');
}


foreach ($array_config['array_status'] as $key_status => $value_status) {
    $xtpl->assign('STATUS_CFG', $value_status);
    $xtpl->parse('main.status_cfg');
}

foreach ($array_config['array_project_status'] as $key_status => $value_status) {
    $xtpl->assign('PROJECTSTATUS_CFG', $value_status);
    $xtpl->parse('main.project_status_cfg');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
