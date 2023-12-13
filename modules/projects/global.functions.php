<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 02 Jan 2018 08:34:29 GMT
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

$array_config = $module_config[$module_name];
$array_config['array_status'] = unserialize($array_config['array_status']);
$array_config['array_project_status'] = unserialize($array_config['array_project_status']);
foreach($array_config['array_status'] as $status){
	$array_status[$status['id']] = $status;
}
$array_config['array_status'] = $array_status;
// print_r($array_config['array_status']);die();


$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_customer_units ORDER BY tid DESC';
$array_customer_units = $nv_Cache->db($_sql, 'tid', $module_name);
