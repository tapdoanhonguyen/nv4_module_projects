<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 02 Jan 2018 08:34:29 GMT
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

function nv_projects_premission_new($module, $type = 'where')
{
    global $db, $array_config, $user_info, $workforce_list, $module_name, $module_data, $module_config;

    if($module_name != $module){
        $array_config = $module_config[$module];
    }

    $array_userid = array(); // mảng chứa userid mà người này được quản lý

    // nhóm quản lý thấy tất cả
    $group_manage = !empty($array_config['groups_manage']) ? explode(',', $array_config['groups_manage']) : array();
    $group_manage = array_map('intval', $group_manage);

    if (empty(array_intersect($group_manage, $user_info['in_groups']))) {
        // kiểm tra tư cách trong nhóm (trưởng nhóm / thành viên nhóm)
        $result = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE is_leader=1 AND approved=1 AND userid=' . $user_info['userid']);
        while ($row = $result->fetch()) {
            // lấy danh sách userid thuộc nhóm do người này quản lý
            $_result = $db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE approved=1 AND group_id=' . $row['group_id']);
            while (list ($userid) = $_result->fetch(3)) {
                $array_userid[] = $userid;
            }
			
        }
		$array_userid[] = $user_info['userid'];
        $array_userid = array_unique($array_userid);

        if ($type == 'where') {
            if (!empty($array_userid)) {
                // nếu là trưởng nhóm, thấy nhân viên do mình quản lý
                $array_userid = implode(',', $array_userid);
                return 'AND (SELECT COUNT( t2.projectid ) AS count
FROM ' . NV_PREFIXLANG . '_' . $module_data . '_performer t2
WHERE t1.id = t2.projectid
AND t2.userid
IN (' . $array_userid . ')) >0';
            } else {
                // thành viên nhóm nhìn thấy task cho mình thực hiện, do mình tạo ra
                return ' AND (t2.userid=' . $user_info['userid'] . ' OR useradd=' . $user_info['userid'] . ')';
            }
        } elseif ($type == 'array_userid') {
            return $array_userid;
        }
    } else {
        $array_userid = !empty($workforce_list) ? array_keys($workforce_list) : array(
            0
        );
        if ($type == 'where') {
			
			return ' AND (SELECT COUNT( t2.projectid ) AS count
FROM ' . NV_PREFIXLANG . '_' . $module_data . '_performer t2
WHERE t1.id = t2.projectid
AND t2.userid
IN (' . implode(',', $array_userid) . ')) >0';
            
        } elseif ($type == 'array_userid') {
            return array_keys($workforce_list);
        }
    }
}



function nv_projects_premission($module, $type = 'where')
{
    global $db, $array_config, $user_info, $workforce_list, $module_name, $module_config;

    if($module_name != $module){
        $array_config = $module_config[$module];
    }

    $array_userid = array(); // mảng chứa userid mà người này được quản lý

    // nhóm quản lý thấy tất cả
    $group_manage = !empty($array_config['groups_manage']) ? explode(',', $array_config['groups_manage']) : array();
    $group_manage = array_map('intval', $group_manage);

    if (empty(array_intersect($group_manage, $user_info['in_groups']))) {
        // kiểm tra tư cách trong nhóm (trưởng nhóm / thành viên nhóm)
        $result = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE is_leader=1 AND approved=1 AND userid=' . $user_info['userid']);
        while ($row = $result->fetch()) {
            // lấy danh sách userid thuộc nhóm do người này quản lý
            $_result = $db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE approved=1 AND group_id=' . $row['group_id']);
            while (list ($userid) = $_result->fetch(3)) {
                $array_userid[] = $userid;
            }
        }
        $array_userid = array_unique($array_userid);

        if ($type == 'where') {
            if (!empty($array_userid)) {
                // nếu là trưởng nhóm, thấy nhân viên do mình quản lý
                $array_userid = implode(',', $array_userid);
                return ' AND (workforceid IN (' . $array_userid . ') OR useradd IN (' . $array_userid . '))';
            } else {
                // thành viên nhóm nhìn thấy task cho mình thực hiện, do mình tạo ra
                return ' AND (t2.userid=' . $user_info['userid'] . ' OR useradd=' . $user_info['userid'] . ')';
            }
        } elseif ($type == 'array_userid') {
            return $array_userid;
        }
    } else {
        $array_userid = !empty($workforce_list) ? array_keys($workforce_list) : array(
            0
        );
        if ($type == 'where') {
            return ' AND (workforceid IN (' . implode(',', $array_userid) . ') OR useradd IN (' . implode(',', $array_userid) . '))';
        } elseif ($type == 'array_userid') {
            return array_keys($workforce_list);
        }
    }
}


