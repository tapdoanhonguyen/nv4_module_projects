<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 12 Jan 2018 09:47:27 GMT
 */
if (!defined('NV_IS_MOD_PROJECT')) die('Stop!!!');



if ($nv_Request->isset_request('id_project', 'post, get')) {
	
	$id_project = $nv_Request->get_int('id_project', 'post, get', 0);
	$phantram = $nv_Request->get_int('phantram', 'post, get', 0);
	
	//die('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET progress = '. $phantram .' WHERE id =' . $id_project);
	
	if($id_project > 0 and $phantram > 0)
	{
		$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET progress = '. $phantram .' WHERE id =' . $id_project);
	}
	
	die();
	
}



if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        nv_projects_delete($id);
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} elseif ($nv_Request->isset_request('delete_list', 'post')) {
    $listall = $nv_Request->get_title('listall', 'post', '');
    $array_id = explode(',', $listall);

    if (!empty($array_id)) {
        foreach ($array_id as $id) {
            nv_projects_delete($id);
        }
        $nv_Cache->delMod($module_name);
        die('OK');
    }
    die('NO');
}

$is_download = false;

// nếu chưa autoload thì include thư viện
if (!class_exists('PHPExcel')) {
    if (file_exists(NV_ROOTDIR . '/includes/class/PHPExcel.php')) {
        include_once NV_ROOTDIR . '/includes/class/PHPExcel.php';
    }
}

if ($nv_Request->isset_request('download', 'post,get') and class_exists('PHPExcel')) {
    $is_download = true;
}

$where = '';
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$per_page = 10;

$page = $nv_Request->get_int('page', 'post,get', 1);

$array_search = array(
    'q' => $nv_Request->get_title('q', 'post,get'),
    'workforceid' => $nv_Request->get_title('workforceid', 'get', 0),
    'duan_dangtrienkhai' => $nv_Request->get_int('duan_dangtrienkhai', 'get', 0),
    'customerid' => $nv_Request->get_int('customerid', 'get', 0),
    'daterange' => $nv_Request->get_string('daterange', 'get', ''),
    'realtime' => $nv_Request->get_string('realtime', 'get', ''),
    'status' => $nv_Request->get_int('status', 'post,get', 0)
);

if (!empty($array_search['q'])) {
    $base_url .= '&q=' . $array_search['q'];
    $where .= ' AND (title LIKE "%' . $array_search['q'] . '%"
        OR url_code LIKE "%' . $array_search['q'] . '%"
        OR content LIKE "%' . $array_search['q'] . '%")';
}
if (!empty($array_search['customerid'])) {
    $base_url .= '&amp;customerid=' . $array_search['customerid'];
    $where .= ' AND customerid=' . $array_search['customerid'];
}

if ($array_search['duan_dangtrienkhai'] == 1) {
    $base_url .= '&amp;duan_dangtrienkhai=1';
    $where .= ' AND status < 4 AND (SELECT COUNT( t2.projectid ) AS count
FROM ' . NV_PREFIXLANG . '_' . $module_data . '_performer t2 WHERE t1.id = t2.projectid AND t2.userid =' . $user_info['userid'] . ') >0';
}



if (!empty($array_search['workforceid'])) {
    $base_url .= '&amp;workforceid= ' . $array_search['workforceid'];
    $where .= ' AND workforceid = ' . $array_search['workforceid'];
}

if ($array_search['status'] > 0) {
    $base_url .= '&amp;status= ' . $array_search['status'];
    $where .= ' AND status = ' . $array_search['status'];
} elseif (!empty($array_config['default_status'])) {
    // $where .= ' AND status IN (' . $array_config['default_status'] . ')';
    $where .= '';
}

if (!empty($array_search['daterange'])) {

    $begin_time = substr($array_search['daterange'], 0, 10);
    $end_time = substr($array_search['daterange'], -10);

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $begin_time, $m)) {

        $begin_time = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $begin_time = 0;
    }
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $end_time, $m)) {

        $end_time = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $end_time = 0;
    }

    $base_url .= '&amp;daterange= ' . $array_search['daterange'];
    $where .= ' AND begintime >= ' . $begin_time . ' AND endtime <= ' . $end_time;
}

if (!empty($array_search['realtime'])) {

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['realtime'], $m)) {

        $_hour = 23;
        $_min = 23;
        $realtime = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $realtime = 0;
    }
    $base_url .= '&amp;realtime= ' . $array_search['realtime'];
    $where .= ' AND realtime = ' . $realtime;
}
if (!empty($array_search['customerid'])) {
    $customer_info = nv_crm_customer_info($array_search['customerid']);
}

$customer_info2 = nv_crm_customer_info_by_userid($user_info['userid']);
if(!empty($customer_info2) and !nv_user_in_groups('1,2,3,10,11,12,13,17,18')){
    // Kiểm tra có phải là khách hàng không, dễ bị xung đột nhóm nên đã loại trừ vài trường hợp
    $where .= ' AND customerid = ' . $customer_info2['customer_id'];
}
else{
    $where .= nv_projects_premission_new($module_name);
}

// Thống kê SQL
$statistical_by = $nv_Request->get_string('statistical_by', 'get', '');
$view_by = $nv_Request->get_string('view_by', 'get', 'week');
if($statistical_by == 'duoc_giao'){
	$where .= ' AND FIND_IN_SET('.$user_info['userid'].', workforceid) and status = 1';
}
elseif($statistical_by == 'quan_ly'){
	$where .= " AND useradd = ".$user_info['userid']."";
}
elseif($statistical_by == 'dang_trien_khai'){
	$where .= " AND (FIND_IN_SET(".$user_info['userid'].", workforceid) or useradd = ".$user_info['userid'].") and status = 2";
}
elseif($statistical_by == 'sap_het_han'){
	$where .= " AND (FIND_IN_SET(".$user_info['userid'].", workforceid) or useradd = ".$user_info['userid'].") and " . NV_CURRENTTIME . " BETWEEN UNIX_TIMESTAMP(FROM_UNIXTIME(endtime) - INTERVAL 2 DAY) AND endtime > 0";
}
elseif($statistical_by == 'qua_han'){
	$where .= " AND (FIND_IN_SET(".$user_info['userid'].", workforceid) or useradd = ".$user_info['userid'].") and endtime > 0 BETWEEN 1 and " . NV_CURRENTTIME . " and status != 3 and status != 5";
}
elseif($statistical_by == 'da_hoan_thanh'){
	$sql_hoanthanh = " AND (FIND_IN_SET(".$user_info['userid'].", workforceid) or useradd = ".$user_info['userid'].") and (status = 3 or status = 5)";
	list($month, $day, $year) = explode('-', nv_date('m-d-Y', NV_CURRENTTIME));
	$timestamp_view = 0;
	if($view_by == 'week'){
		// Theo tuần
		list($month, $day, $year) = explode('-', nv_date('m-d-Y', strtotime("this week") ));
		$timestamp_view = mktime(0, 0, 0, $month, $day, $year);
	}
	elseif($view_by == 'month'){
		// Theo tháng
		$timestamp_view = mktime(0, 0, 0, $month, 1, $year);
	}
	elseif($view_by == 'year'){
		// Theo năm
		$timestamp_view = mktime(0, 0, 0, 1, 1, $year);
	}
	if($timestamp_view){
		$sql_hoanthanh = " AND (FIND_IN_SET(".$user_info['userid'].", workforceid) or useradd = ".$user_info['userid'].") and (status = 3 or status = 5) and realtime BETWEEN " . $timestamp_view . " AND " . NV_CURRENTTIME;
	}
	$where .= $sql_hoanthanh;
}
// Kết thúc thống kê SQL



$db->sqlreset()
    ->select('COUNT(t1.id)')
    ->from(NV_PREFIXLANG . '_' . $module_data . ' t1')
    ->where('1=1' . $where);

$sth = $db->prepare($db->sql());
$sth->execute();
$num_items = $sth->fetchColumn();

/*
============ SQL TÍNH TRUNG BÌNH NHIỀU TABLE ================

SELECT p.id, p.title, IFNULL(AVG(hm.hm_pro), 0) as p_progress
FROM vidoco_vi_projects as p 
LEFT JOIN(
	SELECT hm.id, hm.projectid, IFNULL(AVG(cv.cv_pro), 0) as hm_pro
    FROM vidoco_vi_task_cat as hm 
    LEFT JOIN (
        SELECT catid, IFNULL(progress, 0) as cv_pro
        FROM vidoco_vi_task as cv
    	GROUP BY cv.id
    ) AS cv ON cv.catid = hm.id
    GROUP BY hm.id
) AS hm ON hm.projectid = p.id
GROUP BY p.id

*/

$db->select('t1.*, IFNULL(AVG(hm.hm_pro), 0) as p_progress')
	->join('LEFT JOIN(
				SELECT hm.id, hm.projectid, IF(status = 2, 100, IFNULL(AVG(cv.cv_pro), 0) ) as hm_pro
				FROM vidoco_vi_task_cat as hm 
				LEFT JOIN (
					SELECT catid, IFNULL(progress, 0) as cv_pro
					FROM vidoco_vi_task as cv
					GROUP BY cv.id
				) AS cv ON cv.catid = hm.id
				GROUP BY hm.id
			) AS hm ON hm.projectid = t1.id')
	->group('t1.id')
    ->order('t1.id DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
	
$sth = $db->prepare($db->sql());
$sth->execute();

$customer_info = array();
if (!empty($array_search['customerid'])) {
   $customer_info = nv_crm_customer_info($array_search['customerid']);
}

while ($view = $sth->fetch()) {

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_info WHERE rows_id=' . $view['id'];

    $result = $db->query($sql);
    $custom_fields = $result->fetch();

    foreach ($array_field_config as $field_info) {
        if ($field_info['field_choices'] && $field_info['sql_choices']) {
            $field_info['field_choices'] = [];
            $query = 'SELECT ' . $field_info['sql_choices'][2] . ', ' . $field_info['sql_choices'][3] . ' FROM ' . $field_info['sql_choices'][1];
            if (!empty($field_info['sql_choices'][4]) and !empty($field_info['sql_choices'][5])) {
                $query .= ' ORDER BY ' . $field_info['sql_choices'][4] . ' ' . $field_info['sql_choices'][5];
            }
            $result = $db->query($query);
            while (list ($key, $val) = $result->fetch(3)) {
                $field_info['field_choices'][$key] = $val;
            }
            $view['custom_field'][] = array(
                'title' => $field_info['title'],
                'value' => $field_info['field_choices'][$custom_fields[$field_info['field']]]
            );
        } else {
            $view['custom_field'][] = array(
                'title' => $field_info['title'],
                'value' => $custom_fields[$field_info['field']]
            );
        }
    }

    $view['price'] = number_format($view['price']);
    $view['begintime'] = (empty($view['begintime'])) ? '-' : nv_date('d/m/Y', $view['begintime']);
    $view['endtime'] = (empty($view['endtime'])) ? '-' : nv_date('d/m/Y', $view['endtime']);
    $view['realtime'] = (empty($view['realtime'])) ? '-' : nv_date('d/m/Y', $view['realtime']);
    // $view['status'] = $lang_module['status_select_' . $view['status']];

    // $view['status'] = $array_config['array_status'][$view['status']]['txt'];
    $view['status'] = $array_config['array_status'][$view['status']]['txt'];
	// print_r($array_config['array_status'][$view['status']]);die();


    $view['performer_str'] = array();
    $performer = !empty($view['workforceid']) ? explode(',', $view['workforceid']) : array();
    foreach ($performer as $userid) {
        $view['performer_str'][] = isset($workforce_list[$userid]) ? $workforce_list[$userid]['fullname'] : '-';
    }
    $view['performer_str'] = !empty($view['performer_str']) ? implode(', ', $view['performer_str']) : '';

    if (!isset($array_users[$view['customerid']])) {
        $users = nv_crm_customer_info($view['customerid']);
        if ($users) {
            $view['customer'] = array(
                'fullname' => $users['fullname'],
                'link' => $users['link_view']
            );
            $array_users[$view['customerid']] = $view['customer'];
        } else {
            $view['customer'] = '';
        }
    } else {
        $view['customer'] = $array_users[$view['customerid']];
    }
	
	$view['progress'] = intval($view['p_progress']);

    $array_data[$view['id']] = $view;
    $array_data_down[] = $view;
}

if ($is_download) {
    nv_exams_report_download($lang_module['manager_projects'], $array_data_down);
    die();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LANG_GLOBAL', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('ADD_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content');




$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
$number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
$array_users = array();
if (!empty($array_data)) {
    foreach ($array_data as $view) {
        $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $view['link_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $view['id'];
        $view['number'] = $number++;
        $xtpl->assign('VIEW', $view);

        if (!empty($view['files'])) {
            $xtpl->parse('main.loop.files');
        }
		
		if(nv_user_in_groups('1,2,3,10')){
			// Hiển thị nút sửa, những nhóm tương ứng với sửa dự án
			
			$xtpl->parse('main.loop.allow_edit');
		}
		else{
			$xtpl->parse('main.loop.not_allow_edit');
		}

        $xtpl->parse('main.loop');
    }
}

if (!empty($workforce_list)) {
    foreach ($workforce_list as $user) {
        $user['selected'] = $user['userid'] == $array_search['workforceid'] ? 'selected="selected"' : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.user');
    }
}

$array_action = array(
    'delete_list_id' => $lang_global['delete']
);
foreach ($array_action as $key => $value) {
    $xtpl->assign('ACTION', array(
        'key' => $key,
        'value' => $value
    ));
    $xtpl->parse('main.action_top');
    $xtpl->parse('main.action_bottom');
}

// foreach ($array_status as $index => $value) {
//     $selected = $index == $array_search['status'] ? ' selected = "selected" ' : '';
//     $xtpl->assign('STATUS', array(
//         'index' => $index,
//         'value' => $value,
//         'selected' => $selected
//     ));
//     $xtpl->parse('main.status');
// }

// print_r($array_config['array_status']);die();
// $array_config['array_status'] = unserialize($array_config['array_status']);
foreach ($array_config['array_status'] as $key_status => $value_status) {
	// print_r($value_status);die();
    $xtpl->assign('STATUS', array(
        'index' => $key_status,
        'value' => $value_status['txt'],
        'selected' => $key_status == $array_search['status'] ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.status');
}


if (!empty($customer_info)) {
    $xtpl->assign('CUSTOMER', $customer_info);
    $xtpl->parse('main.customerid');
}

if (class_exists('PHPExcel') and !empty($array_data)) {
    $xtpl->assign('DOWNLOAD_URL', $base_url . '&download');
} else {
    $xtpl->parse('main.btn_disabled');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $module_info['custom_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';