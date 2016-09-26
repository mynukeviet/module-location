<?php

/**
 * @Project NUKEVIET 4.x
 * @Author hongoctrien (hongoctrien@2mit.org)
 * @Copyright (C) 2015 hongoctrien. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 16 Dec 2015 07:05:25 GMT
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');
    
$location_array_config = array();
$_sql = 'SELECT config_name, config_value FROM ' . $db_config['prefix'] . '_location_config';
$_query = $db->query($_sql);
while (list ($config_name, $config_value) = $_query->fetch(3)) {
    $location_array_config[$config_name] = $config_value;
}

$is_district = false;

if ($nv_Request->isset_request('location_reload', 'post,get')) {    
    $data_config = array(
        'select_countryid' => $nv_Request->get_int('select_countryid', 'post,get', 0),
        'select_provinceid' => $nv_Request->get_int('select_provinceid', 'post,get', 0),
        'allow_country' => $nv_Request->get_title('allow_country', 'post,get', ''),
        'allow_province' => $nv_Request->get_title('allow_province', 'post,get', ''),
        'allow_district' => $nv_Request->get_title('allow_district', 'post,get', ''),
        'allow_ward' => $nv_Request->get_title('allow_ward', 'post,get', ''),
        'multiple_province' => $nv_Request->get_int('multiple_province', 'post,get', 0),
        'multiple_district' => $nv_Request->get_int('multiple_district', 'post,get', 0),
        'multiple_ward' => $nv_Request->get_int('multiple_ward', 'post,get', 0),
        'is_district' => $nv_Request->get_int('is_district', 'post,get', 0),
        'is_ward' => $nv_Request->get_int('is_ward', 'post,get', 0),
        'blank_title_country' => $nv_Request->get_int('blank_title_country', 'post,get', 0),
        'blank_title_province' => $nv_Request->get_int('blank_title_province', 'post,get', 0),
        'blank_title_district' => $nv_Request->get_int('blank_title_district', 'post,get', 0),
        'blank_title_ward' => $nv_Request->get_int('blank_title_ward', 'post,get', 0),
        'name_country' => $nv_Request->get_title('name_country', 'post,get', 'countryid'),
        'name_province' => $nv_Request->get_title('name_province', 'post,get', 'provinceid'),
        'name_district' => $nv_Request->get_title('name_district', 'post,get', 'districtid'),
        'name_ward' => $nv_Request->get_title('name_ward', 'post,get', 'wardid'),
        'index' => $nv_Request->get_int('index', 'post,get', 0),
        'col_class' => $nv_Request->get_title('col_class', 'post,get', 'col-xs-24 col-sm-12 col-md-12')
    );
    $data_config['select_provinceid'] = $data_config['multiple_province'] ? $nv_Request->get_typed_array('select_provinceid', 'post,get', 'int') : $nv_Request->get_int('select_provinceid', 'post,get', 0);
    $data_config['select_districtid'] = $data_config['multiple_district'] ? $nv_Request->get_typed_array('select_districtid', 'post,get', 'int') : $nv_Request->get_int('select_districtid', 'post,get', 0);
    $data_config['select_wardid'] = $data_config['multiple_ward'] ? $nv_Request->get_typed_array('select_wardid', 'post,get', 'int') : $nv_Request->get_int('select_wardid', 'post,get', 0);
    
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/location.class.php';
    
    $location = new Location();
    $location->setSelectCountryid($data_config['select_countryid']);
    $location->setSelectProvinceid($data_config['select_provinceid']);
    $location->setAllowCountry($data_config['allow_country']);
    $location->setAllowProvince($data_config['allow_province']);
    $location->setAllowDistrict($data_config['allow_district']);
    $location->setAllowWard($data_config['allow_ward']);
    $location->setMultipleProvince($data_config['multiple_province']);
    $location->setMultipleDistrict($data_config['multiple_district']);
    $location->setMultipleWard($data_config['multiple_ward']);
    $location->setIsDistrict($data_config['is_district']);
    $location->setIsWard($data_config['is_ward']);
    $location->setBlankTitleCountry($data_config['blank_title_country']);
    $location->setBlankTitleProvince($data_config['blank_title_province']);
    $location->setBlankTitleDistrict($data_config['blank_title_district']);
    $location->setBlankTitleWard($data_config['blank_title_ward']);
    $location->setNameCountry($data_config['name_country']);
    $location->setNameProvince($data_config['name_province']);
    $location->setNameDistrict($data_config['name_district']);
    $location->setNameWard($data_config['name_ward']);
    $location->setIndex($data_config['index']);
    $location->setColClass($data_config['col_class']);
    
    die($location->buildInput());
}

/**
 * nv_location_get_country()
 *
 * @param string $module            
 * @return
 *
 */
function nv_location_get_country($module = 'location')
{
    global $db, $db_config, $site_mods, $nv_Cache;
    
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_country WHERE status=1';
    $array_country = $nv_Cache->db($sql, 'countryid', $module);
    return $array_country;
}

/**
 * nv_location_get_province()
 *
 * @param integer $countryid            
 * @param string $module            
 * @return
 *
 */
function nv_location_get_province($countryid, $module = 'location')
{
    global $db, $db_config, $site_mods, $nv_Cache;
    
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $countryid;
    $array_province = $nv_Cache->db($sql, 'provinceid', $module);
    return $array_province;
}

/**
 * nv_location_get_district()
 *
 * @param integer $districtid            
 * @param string $module            
 * @return
 *
 */
function nv_location_get_district($provinceid, $module = 'location')
{
    global $db, $db_config, $site_mods, $nv_Cache;
    
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $provinceid;
    $array_district = $nv_Cache->db($sql, 'districtid', $module);
    return $array_district;
}

/**
 * nv_location_get_countryid_from_province()
 *
 * @param integer $provinceid            
 * @param string $module            
 * @return
 *
 */
function nv_location_get_countryid_from_province($provinceid, $module = 'location')
{
    global $db, $db_config, $site_mods;
    
    if (empty($provinceid))
        return 0;
    
    $countryid = $db->query('SELECT countryid FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE provinceid=' . $provinceid)->fetchColumn();
    
    return $countryid;
}

/**
 * nv_location_get_province_info()
 *
 * @param string $module            
 * @return
 *
 */
function nv_location_get_province_info($provinceid, $module = 'location')
{
    global $db, $db_config, $site_mods, $location_array_config;
    
    $province_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND provinceid=' . $provinceid)->fetch();
    
    if ($location_array_config['allow_type'] and ! empty($province_info['type'])) {
        $province_info['title'] = $province_info['type'] . ' ' . $province_info['title'];
    }
    
    return $province_info;
}

/**
 * nv_location_get_district_info()
 *
 * @param string $module            
 * @return
 *
 */
function nv_location_get_district_info($districtid, $module = 'location')
{
    global $db, $db_config, $site_mods, $location_array_config;
    
    $district_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND districtid=' . $districtid)->fetch();
    
    if ($location_array_config['allow_type'] and ! empty($district_info['type'])) {
        $district_info['title'] = $district_info['type'] . ' ' . $district_info['title'];
    }
    
    return $district_info;
}

/**
 * nv_location_get_ward_info()
 *
 * @param string $module            
 * @return
 *
 */
function nv_location_get_ward_info($wardid, $module = 'location')
{
    global $db, $db_config, $site_mods, $location_array_config;
    
    $ward_info = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_ward WHERE status=1 AND wardid=' . $wardid)->fetch();
    
    if ($location_array_config['allow_type'] and ! empty($ward_info['type'])) {
        $ward_info['title'] = $ward_info['type'] . ' ' . $ward_info['title'];
    }
    
    return $ward_info;
}

/**
 * nv_location_make_string()
 *
 * @param int $provinceid            
 * @param int $districtid            
 * @param int $wardid            
 * @return
 *
 */
function nv_location_make_string($provinceid = 0, $districtid = 0, $wardid = 0)
{
    global $db, $db_config, $site_mods, $location_array_config;
    
    $string = array();
    
    if (! empty($wardid)) {
        $ward_info = nv_location_get_ward_info($wardid);
        $string[] = $ward_info['title'];
    }
    
    if (! empty($districtid)) {
        $district_info = nv_location_get_district_info($districtid);
        $string[] = $district_info['title'];
    }
    
    if (! empty($provinceid)) {
        $province_info = nv_location_get_province_info($provinceid);
        $string[] = $province_info['title'];
    }
    
    return implode(', ', $string);
}