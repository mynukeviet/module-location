<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$data = array();
if( $nv_Request->isset_request( 'savesetting', 'post' ) )
{
	$data['allow_type'] = $nv_Request->get_int( 'allow_type', 'post', 0 );

	$sth = $db->prepare( "UPDATE " . $db_config['prefix'] . '_' . $module_data . "_config SET config_value = :config_value WHERE config_name = :config_name" );
	foreach( $data as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid'] );
	$nv_Cache->delMod( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op );
	die();
}

$data['imgposition'] = 0;

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $location_array_config );

$xtpl->assign( 'ck_allow_type', $location_array_config['allow_type'] ? 'checked="checked"' : '' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';