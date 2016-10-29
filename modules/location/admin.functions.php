<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 */
if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN'))
    die('Stop!!!');

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/location.class.php';

$allow_func = array(
    'main',
    'config',
    'country',
    'province',
    'district',
    'ward'
);

/**
 * nv_location_delete_province()
 *
 * @param integer $provinceid            
 * @return
 *
 */
function nv_location_delete_province($provinceid)
{
    global $db, $db_config, $module_data;
    
    // Xoa Tinh/Thanh pho
    $result = $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid=' . $provinceid);
    if ($result) {
        // Xoa Quan/Huyen truc thuoc
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE provinceid=' . $provinceid);
    }
}