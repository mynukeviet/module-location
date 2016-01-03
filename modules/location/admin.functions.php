<?php

/**
 * @Project NUKEVIET 4.x
 * @Author hongoctrien (hongoctrien@2mit.org)
 * @Copyright (C) 2015 hongoctrien. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 16 Dec 2015 07:05:25 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_ADMIN', true );

$location_array_config = array();
$_sql = 'SELECT config_name, config_value FROM ' . $db_config['prefix'] . '_' . $module_data . '_config';
$_query = $db->query( $_sql );
while( list( $config_name, $config_value ) = $_query->fetch( 3 ) )
{
	$location_array_config[$config_name] = $config_value;
}

$allow_func = array( 'main', 'config', 'country', 'province', 'district', 'ward' );
require_once NV_ROOTDIR . '/modules/' . $module_file . '/data.functions.php';

/**
 * nv_location_delete_province()
 *
 * @param integer $provinceid
 * @return
 */
function nv_location_delete_province( $provinceid )
{
	global $db, $db_config, $module_data;

	// Xoa Tinh/Thanh pho
	$result = $db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid=' . $provinceid );
	if( $result )
	{
		// Xoa Quan/Huyen truc thuoc
		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE provinceid=' . $provinceid );
	}
}