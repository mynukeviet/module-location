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
		'select_countyid' => $nv_Request->get_int( 'select_countryid', 'post,get', 0 ),
		'select_provinceid' => $nv_Request->get_int( 'select_provinceid', 'post,get', 0 ),
		'select_districtid' => $nv_Request->get_int( 'select_districtid', 'post,get', 0 ),
		'allow_country' => $nv_Request->get_title( 'allow_country', 'post,get', '' ),
		'allow_province' => $nv_Request->get_title( 'allow_province', 'post,get', '' ),
		'allow_district' => $nv_Request->get_title( 'allow_district', 'post,get', '' ),
		'multiple_province' => $nv_Request->get_bool( 'multiple_province', 'post,get', 0 ),
		'multiple_district' => $nv_Request->get_bool( 'multiple_district', 'post,get', 0 ),
		'is_district' => $nv_Request->get_bool( 'is_district', 'post,get', 0 ),
		'blank_title_country' => $nv_Request->get_bool( 'blank_title_country', 'post,get', 0 ),
		'blank_title_province' => $nv_Request->get_bool( 'blank_title_province', 'post,get', 0 ),
		'blank_title_district' => $nv_Request->get_bool( 'blank_title_district', 'post,get', 0 )
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
 * nv_location_get_district()
 *
 * @param integer $districtid
 * @param string $module
 * @return
 */
function nv_location_get_district( $provinceid, $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $provinceid;
	$array_district = nv_db_cache( $sql, 'districtid', $module );
	return $array_district;
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

	if( empty( $provinceid ) ) return 0;

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
 * nv_location_get_district_info()
 *
 * @param string $module
 * @return
 */
function nv_location_get_district_info( $districtid, $module = 'location' )
{
	global $db, $db_config, $site_mods;

	$district_info = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND districtid=' . $districtid )->fetch();

	return $district_info;
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
		'select_districtid' => isset( $data_config['select_districtid'] ) ? $data_config['select_districtid'] : 0,
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
		'multiple_district' => isset( $data_config['multiple_district'] ) ? $data_config['multiple_district'] : 0,
		// Thêm dòng tiêu đề
		'blank_title_country' => isset( $data_config['blank_title_country'] ) ? $data_config['blank_title_country'] : 0,
		'blank_title_province' => isset( $data_config['blank_title_province'] ) ? $data_config['blank_title_province'] : 0,
		'blank_title_district' => isset( $data_config['blank_title_district'] ) ? $data_config['blank_title_district'] : 0
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
	$result_province = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $first_country . $in . ' ORDER BY weight ASC' );
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
		$result_district = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $first_province . $in . ' ORDER BY weight ASC' );
		while( $row_district = $result_district->fetch() )
		{
			if( is_array( $data_config['select_districtid'] ) )
			{
				$row_district['selected'] = in_array( $row_district['districtid'], $data_config['select_districtid'] ) ? 'selected="selected"' : '';
			}
			else
			{
				$row_district['selected'] = $data_config['select_districtid'] == $row_district['districtid'] ? 'selected="selected"' : '';
			}
			$array_district[$row_district['districtid']] = $row_district;
		}
	}

	include NV_ROOTDIR . '/modules/location/language/admin_' . NV_LANG_INTERFACE . '.php';

	$xtpl = new XTemplate( 'form_input.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $site_mods[$module]['module_file'] );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'ALLOW_COUNTRY', $data_config['allow_country'] );
	$xtpl->assign( 'ALLOW_PROVINCE', $data_config['allow_province'] );
	$xtpl->assign( 'ALLOW_DISTRICT', $data_config['allow_district'] );
	$xtpl->assign( 'IS_DISTRICT', $data_config['is_district'] );
	$xtpl->assign( 'MULTIPLE_PROVINCE', $data_config['multiple_province'] );
	$xtpl->assign( 'MULTIPLE_DISTRICT', $data_config['multiple_district'] );
	$xtpl->assign( 'BLANK_TITLE_COUNTRY', $data_config['blank_title_country'] );
	$xtpl->assign( 'BLANK_TITLE_PROVINCE', $data_config['blank_title_province'] );
	$xtpl->assign( 'BLANK_TITLE_DISTRICT', $data_config['blank_title_district'] );

	if( !empty( $array_country ) )
	{
		if( $i > 1 )
		{
			if( $data_config['blank_title_country'] )
			{
				$xtpl->parse( 'form_input.country.blank_title' );
			}
			foreach( $array_country as $country )
			{
				$xtpl->assign( 'COUNTRY', $country );
				$xtpl->parse( 'form_input.country.loop' );
			}
			$xtpl->parse( 'form_input.country' );
		}
		else
		{
			$xtpl->assign( 'COUNTRYID', $first_country );
			$xtpl->parse( 'form_input.country_hidden' );
		}
	}

	if( !empty( $array_province ) )
	{
		if( $data_config['blank_title_province'] )
		{
			$xtpl->parse( 'form_input.province.blank_title' );
		}
		foreach( $array_province as $province )
		{
			$xtpl->assign( 'PROVINCE', $province );
			$xtpl->parse( 'form_input.province.loop' );
		}
		if( $data_config['multiple_province'] )
		{
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
		if( $data_config['blank_title_district'] )
		{
			$xtpl->parse( 'form_input.district.blank_title' );
		}
		foreach( $array_district as $district )
		{
			$xtpl->assign( 'DISTRICT', $district );
			$xtpl->parse( 'form_input.district.loop' );
		}
		if( $data_config['multiple_district'] )
		{
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