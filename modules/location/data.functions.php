<?php

/**
 * @Project NUKEVIET 4.x
 * @Author hongoctrien (hongoctrien@2mit.org)
 * @Copyright (C) 2015 hongoctrien. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 16 Dec 2015 07:05:25 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$is_district = false;

if( $nv_Request->isset_request( 'location_reload', 'post,get' ) )
{
	$data_config = array(
		'select_countyid' => $nv_Request->get_int( 'countryid', 'post,get', 0 ),
		'select_provinceid' => $nv_Request->get_int( 'provinceid', 'post,get', 0 ),
		'allow_country' => $nv_Request->get_int( 'districtid', 'post,get', 0 ),
		'multiple_province' => $nv_Request->get_bool( 'multiple_province', 'post,get', 0 ),
		'multiple_district' => $nv_Request->get_bool( 'multiple_district', 'post,get', 0 )
	);
	$location_html = nv_location_build_input( $data_config );
	die( $location_html );
}

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

	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $countryid;
	$array_province = nv_db_cache( $sql, 'provinceid', $module );
	return $array_province;
}

/**
 * nv_location_get_countryid_from_province()
 *
 * @param integer $provinceid
 * @param string $module
 * @return
 */
function nv_location_get_countryid_from_province( $provinceid, $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$countryid = $db->query( 'SELECT countryid FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE provinceid=' . $provinceid )->fetchColumn();

	return $countryid;
}

/**
 * nv_location_get_province_info()
 *
 * @param string $module
 * @return
 */
function nv_location_get_province_info( $provinceid, $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$province_info = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND provinceid=' . $provinceid )->fetch();

	return $province_info;
}

/**
 * nv_location_build_input()
 *
 * @param string $module
 * @return
 */
function nv_location_build_input( $data_config = array(), $template = 'default', $module = 'location' )
{
	global $db, $db_config, $site_mods, $global_config, $lang_module;

	$data_config = array(
		// Quốc gia được chọn
		'select_countyid' => isset( $data_config['select_countyid'] ) ? $data_config['select_countyid'] : 0,
		// Tỉnh được chọn
		'select_provinceid' => isset( $data_config['select_provinceid'] ) ? $data_config['select_provinceid'] : 0,
		// Quận/Huyện được chọn
		'select_district' => isset( $data_config['select_district'] ) ? $data_config['select_district'] : 0,
		// Quốc gia cho phép
		'allow_country' => isset( $data_config['allow_country'] ) ? $data_config['allow_country'] : '',
		// Tỉnh cho phép
		'allow_province' => isset( $data_config['allow_province'] ) ? $data_config['allow_province'] : '',
		// Quận/Huyện cho phép
		'allow_district' => isset( $data_config['allow_district'] ) ? $data_config['allow_district'] : '',
		// Sử dụng Cấp QUận/Huyện
	 	'is_district' => isset( $data_config['is_district'] ) ? $data_config['is_district'] : false,
	 	// Chọn nhiều Tỉnh
		'multiple_province' => isset( $data_config['multiple_province'] ) ? $data_config['multiple_province'] : 0,
		// Chọn nhiều Quận
		'multiple_district' => isset( $data_config['multiple_district'] ) ? $data_config['multiple_district'] : 0
	);

	$in = !empty( $data_config['allow_country'] ) ? ' AND countryid IN (' . $data_config['allow_country'] . ')' : '';
	$result_country = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_country WHERE status=1' . $in . ' ORDER BY weight ASC' );
	$array_country = array();
	$array_province = array();
	$array_district = array();
	$i = 0;
	$first_country = $data_config['select_countyid'];
	while( $row_country = $result_country->fetch() )
	{
		if( $i == 0 and empty( $first_country ) ) $first_country = $row_country['countryid'];
		$row_country['selected'] = $data_config['select_countyid'] == $row_country['countryid'] ? 'selected="selected"' : '';
		$array_country[$row_country['countryid']] = $row_country;
		$i++;
	}

	$j = 0;
	$first_province = $data_config['select_provinceid'];
	$in = !empty( $data_config['allow_province'] ) ? ' AND provinceid IN (' . $data_config['allow_province'] . ')' : '';
	$result_province = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $first_country . ' ORDER BY weight ASC' );
	while( $row_province = $result_province->fetch() )
	{
		if( $j == 0 and empty( $first_province ) ) $first_province = $row_province['provinceid'];

		if( is_array( $data_config['select_provinceid'] ) )
		{
			$row_province['selected'] = in_array( $row_province['provinceid'], $data_config['select_provinceid'] ) ? 'selected="selected"' : '';
		}
		else
		{
			$row_province['selected'] = $data_config['select_provinceid'] == $row_province['provinceid'] ? 'selected="selected"' : '';
		}
		$array_province[$row_province['provinceid']] = $row_province;
		$j++;
	}

	if( $data_config['is_district'] )
	{
		$in = !empty( $data_config['allow_district'] ) ? ' AND districtid IN (' . $data_config['allow_district'] . ')' : '';
		$result_district = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $first_province . ' ORDER BY weight ASC' );
		while( $row_district = $result_district->fetch() )
		{
			if( is_array( $data_config['select_district'] ) )
			{
				$row_district['selected'] = in_array( $row_district['districtid'], $data_config['select_district'] ) ? 'selected="selected"' : '';
			}
			else
			{
				$row_district['selected'] = $data_config['select_district'] == $row_district['districtid'] ? 'selected="selected"' : '';
			}
			$array_district[$row_district['districtid']] = $row_district;
		}
	}

	$xtpl = new XTemplate( 'form_input.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $site_mods[$module]['module_file'] );
	$xtpl->assign( 'LANG', $lang_module );

	if( !empty( $array_country ) and $i > 1 )
	{
		foreach( $array_country as $country )
		{
			$xtpl->assign( 'COUNTRY', $country );
			$xtpl->parse( 'form_input.country.loop' );
		}
		$xtpl->parse( 'form_input.country' );
	}

	if( !empty( $array_province ) )
	{
		foreach( $array_province as $province )
		{
			$xtpl->assign( 'PROVINCE', $province );
			$xtpl->parse( 'form_input.province.loop' );
		}
		if( $data_config['multiple_province'] )
		{
			$xtpl->assign( 'MULTPLE', $data_config['multiple_province'] );
			$xtpl->parse( 'form_input.province.multiple' );
		}
		else
		{
			$xtpl->parse( 'form_input.province.none_multiple' );
		}
		$xtpl->parse( 'form_input.province' );
	}

	if( !empty( $array_district ) )
	{
		foreach( $array_district as $district )
		{
			$xtpl->assign( 'DISTRICT', $district );
			$xtpl->parse( 'form_input.district.loop' );
		}
		if( $data_config['multiple_district'] )
		{
			$xtpl->assign( 'MULTPLE', $data_config['multiple_district'] );
			$xtpl->parse( 'form_input.district.multiple' );
		}
		else
		{
			$xtpl->parse( 'form_input.district.none_multiple' );
		}
		$xtpl->parse( 'form_input.district' );
	}

	$xtpl->parse( 'form_input' );
	$form_input = $xtpl->text( 'form_input' );

	$xtpl->assign( 'FORM_INPUT', $form_input );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}