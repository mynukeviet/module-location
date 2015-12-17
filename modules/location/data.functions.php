<?php

/**
 * @Project NUKEVIET 4.x
 * @Author hongoctrien (hongoctrien@2mit.org)
 * @Copyright (C) 2015 hongoctrien. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 16 Dec 2015 07:05:25 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_location_get_country()
 *
 * @param string $module
 * @return
 */
function nv_location_get_country( $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_country WHERE status=1';
	$array_country = nv_db_cache( $sql, 'countryid', $module );
	return $array_country;
}

/**
 * nv_location_get_province()
 *
 * @param integer $countryid
 * @param string $module
 * @return
 */
function nv_location_get_province( $countryid, $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $db->quote( $countryid );
	$array_province = nv_db_cache( $sql, 'provinceid', $module );
	return $array_province;
}