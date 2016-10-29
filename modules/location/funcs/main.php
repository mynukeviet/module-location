<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12-11-2010 20:40
 */
if (! defined('NV_IS_MOD_LOCATION')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('location_reload', 'post,get')) {
    $data_config = array(
        'select_countryid' => $nv_Request->get_int('select_countryid', 'post,get', 0),
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
    
    $data_config['select_provinceid'] = $nv_Request->get_title('select_provinceid', 'post,get', '');
    $data_config['select_districtid'] = $nv_Request->get_title('select_districtid', 'post,get', '');
    $data_config['select_wardid'] = $nv_Request->get_title('select_wardid', 'post,get', '');
    
    if ($data_config['multiple_province']) {
        $data_config['select_provinceid'] = explode(',', $data_config['select_provinceid']);
        $data_config['select_districtid'] = explode(',', $data_config['select_districtid']);
        $data_config['select_wardid'] = explode(',', $data_config['select_wardid']);
    }
    
    $location = new Location();
    $location->set('SelectCountryid', $data_config['select_countryid']);
    $location->set('SelectProvinceid', $data_config['select_provinceid']);
    $location->set('SelectDistrictid', $data_config['select_districtid']);
    $location->set('SelectWardid', $data_config['select_wardid']);
    $location->set('AllowCountry', $data_config['allow_country']);
    $location->set('AllowProvince', $data_config['allow_province']);
    $location->set('AllowDistrict', $data_config['allow_district']);
    $location->set('AllowWard', $data_config['allow_ward']);
    $location->set('MultipleProvince', $data_config['multiple_province']);
    $location->set('MultipleDistrict', $data_config['multiple_district']);
    $location->set('MultipleWard', $data_config['multiple_ward']);
    $location->set('IsDistrict', $data_config['is_district']);
    $location->set('IsWard', $data_config['is_ward']);
    $location->set('BlankTitleCountry', $data_config['blank_title_country']);
    $location->set('BlankTitleProvince', $data_config['blank_title_province']);
    $location->set('BlankTitleDistrict', $data_config['blank_title_district']);
    $location->set('BlankTitleWard', $data_config['blank_title_ward']);
    $location->set('NameCountry', $data_config['name_country']);
    $location->set('NameProvince', $data_config['name_province']);
    $location->set('NameDistrict', $data_config['name_district']);
    $location->set('NameWard', $data_config['name_ward']);
    $location->set('Index', $data_config['index']);
    $location->set('ColClass', $data_config['col_class']);
    
    die($location->buildInput());
}