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
    
    $location_html = nv_location_build_input($data_config);
    die($location_html);
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

/**
 * nv_location_build_input()
 *
 * @param string $module            
 * @return
 *
 */
function nv_location_build_input($_data_config = array(), $template = 'default', $module = 'location')
{
    global $db, $db_config, $site_mods, $global_config, $lang_module, $location_array_config;
    
    $data_config = array(
        // Quốc gia được chọn
        'select_countryid' => isset($_data_config['select_countryid']) ? $_data_config['select_countryid'] : 0,
        // Tỉnh được chọn
        'select_provinceid' => isset($_data_config['select_provinceid']) ? $_data_config['select_provinceid'] : 0,
        // Quận/Huyện được chọn
        'select_districtid' => isset($_data_config['select_districtid']) ? $_data_config['select_districtid'] : 0,
        // Xã/Phường được chọn
        'select_wardid' => isset($_data_config['select_wardid']) ? $_data_config['select_wardid'] : 0,
        // Quốc gia cho phép
        'allow_country' => isset($_data_config['allow_country']) ? $_data_config['allow_country'] : '',
        // Tỉnh cho phép
        'allow_province' => isset($_data_config['allow_province']) ? $_data_config['allow_province'] : '',
        // Quận/Huyện cho phép
        'allow_district' => isset($_data_config['allow_district']) ? $_data_config['allow_district'] : '',
        // Xã/Phường cho phép
        'allow_ward' => isset($_data_config['allow_ward']) ? $_data_config['allow_ward'] : '',
        // Sử dụng Cấp QUận/Huyện
        'is_district' => isset($_data_config['is_district']) and ! empty($_data_config['is_district']) ? $_data_config['is_district'] : false,
        // Sử dụng Cấp Xã/Phường
        'is_ward' => isset($_data_config['is_ward']) ? $_data_config['is_ward'] : false,
        // Chọn nhiều Tỉnh
        'multiple_province' => isset($_data_config['multiple_province']) ? $_data_config['multiple_province'] : 0,
        // Chọn nhiều Quận/Huyện
        'multiple_district' => isset($_data_config['multiple_district']) ? $_data_config['multiple_district'] : 0,
        // Chọn nhiều Xã/Phường
        'multiple_ward' => isset($_data_config['multiple_ward']) ? $_data_config['multiple_ward'] : 0,
        // Thêm dòng tiêu đề
        'blank_title_country' => isset($_data_config['blank_title_country']) ? $_data_config['blank_title_country'] : 0,
        'blank_title_province' => isset($_data_config['blank_title_province']) ? $_data_config['blank_title_province'] : 0,
        'blank_title_district' => isset($_data_config['blank_title_district']) ? $_data_config['blank_title_district'] : 0,
        'blank_title_ward' => isset($_data_config['blank_title_ward']) ? $_data_config['blank_title_ward'] : 0,
        // Thiết lập tên select name
        'name_country' => isset($_data_config['name_country']) ? $_data_config['name_country'] : 'countryid',
        'name_province' => isset($_data_config['name_province']) ? $_data_config['name_province'] : 'provinceid',
        'name_district' => isset($_data_config['name_district']) ? $_data_config['name_district'] : 'districtid',
        'name_ward' => isset($_data_config['name_ward']) ? $_data_config['name_ward'] : 'wardid',
        // su dung cho nhieu dia diem
        'index' => isset($_data_config['index']) ? $_data_config['index'] : 1,
        'col_class' => isset($_data_config['col_class']) ? $_data_config['col_class'] : 'col-xs-24 col-sm-12 col-md-12'
    );
    
    $in = ! empty($data_config['allow_country']) ? ' AND countryid IN (' . $data_config['allow_country'] . ')' : '';
    $result_country = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_country WHERE status=1' . $in . ' ORDER BY weight ASC');
    $array_country = array();
    $array_province = array();
    $array_district = array();
    $array_ward = array();
    $i = 0;
    
    $first_country = $data_config['select_countryid'];
    while ($row_country = $result_country->fetch()) {
        if ($i == 0 and empty($first_country))
            $first_country = $row_country['countryid'];
        $row_country['selected'] = $data_config['select_countryid'] == $row_country['countryid'] ? 'selected="selected"' : '';
        $array_country[$row_country['countryid']] = $row_country;
        $i ++;
    }
    
    $j = 0;
    $first_province = $data_config['select_provinceid'];
    $in = ! empty($data_config['allow_province']) ? ' AND provinceid IN (' . $data_config['allow_province'] . ')' : '';
    $result_province = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $first_country . $in . ' ORDER BY weight ASC');
    while ($row_province = $result_province->fetch()) {
        if ($j == 0 and empty($first_province))
            $first_province = $row_province['provinceid'];
        
        if (is_array($data_config['select_provinceid'])) {
            $row_province['selected'] = in_array($row_province['provinceid'], $data_config['select_provinceid']) ? 'selected="selected"' : '';
        } else {
            $row_province['selected'] = $data_config['select_provinceid'] == $row_province['provinceid'] ? 'selected="selected"' : '';
        }
        $array_province[$row_province['provinceid']] = $row_province;
        $j ++;
    }
    
    if ($data_config['is_district']) {
        $j = 0;
        $first_district = $data_config['select_districtid'];
        
        $in = ! empty($data_config['allow_district']) ? ' AND districtid IN (' . $data_config['allow_district'] . ')' : '';
        $result_district = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $first_province . $in . ' ORDER BY weight ASC');
        while ($row_district = $result_district->fetch()) {
            if ($j == 0 and empty($first_district))
                $first_district = $row_district['districtid'];
            
            if (is_array($data_config['select_districtid'])) {
                $row_district['selected'] = in_array($row_district['districtid'], $data_config['select_districtid']) ? 'selected="selected"' : '';
            } else {
                $row_district['selected'] = $data_config['select_districtid'] == $row_district['districtid'] ? 'selected="selected"' : '';
            }
            $array_district[$row_district['districtid']] = $row_district;
        }
    }
    
    if ($data_config['is_ward']) {
        $in = ! empty($data_config['allow_ward']) ? ' AND wardid IN (' . $data_config['allow_ward'] . ')' : '';
        $result_ward = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_ward WHERE status=1 AND districtid=' . $first_district . $in . ' ORDER BY wardid DESC');
        while ($row_ward = $result_ward->fetch()) {
            if (is_array($data_config['select_wardid'])) {
                $row_ward['selected'] = in_array($row_ward['wardid'], $data_config['select_wardid']) ? 'selected="selected"' : '';
            } else {
                $row_ward['selected'] = $data_config['select_wardid'] == $row_ward['wardid'] ? 'selected="selected"' : '';
            }
            $array_ward[$row_ward['wardid']] = $row_ward;
        }
    }
    
    include NV_ROOTDIR . '/modules/location/language/admin_' . NV_LANG_INTERFACE . '.php';
    
    $xtpl = new XTemplate('form_input.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $site_mods[$module]['module_file']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CONFIG', $data_config);
    
    if (! empty($array_country)) {
        if ($i > 1) {
            if ($data_config['blank_title_country']) {
                $xtpl->parse('form_input.country.blank_title');
            }
            foreach ($array_country as $country) {
                $xtpl->assign('COUNTRY', $country);
                $xtpl->parse('form_input.country.loop');
            }
            $xtpl->parse('form_input.country');
        } else {
            $xtpl->assign('COUNTRYID', $first_country);
            $xtpl->parse('form_input.country_hidden');
        }
    }
    
    if (! empty($array_province)) {
        if ($data_config['blank_title_province']) {
            $xtpl->parse('form_input.province.blank_title');
        }
        foreach ($array_province as $province) {
            $xtpl->assign('PROVINCE', $province);
            
            if ($location_array_config['allow_type'] and ! empty($province['type'])) {
                $xtpl->parse('form_input.province.loop.type');
            }
            
            $xtpl->parse('form_input.province.loop');
        }
        if ($data_config['multiple_province']) {
            $xtpl->parse('form_input.province.multiple');
        } else {
            $xtpl->parse('form_input.province.none_multiple');
        }
        $xtpl->parse('form_input.province');
    }
    
    if (! empty($array_district)) {
        if ($data_config['blank_title_district']) {
            $xtpl->parse('form_input.district.blank_title');
        }
        foreach ($array_district as $district) {
            $xtpl->assign('DISTRICT', $district);
            
            if ($location_array_config['allow_type'] and ! empty($district['type'])) {
                $xtpl->parse('form_input.district.loop.type');
            }
            
            $xtpl->parse('form_input.district.loop');
        }
        if ($data_config['multiple_district']) {
            $xtpl->parse('form_input.district.multiple');
        } else {
            $xtpl->parse('form_input.district.none_multiple');
        }
        $xtpl->parse('form_input.district');
    }
    
    if (! empty($array_ward)) {
        if ($data_config['blank_title_ward']) {
            $xtpl->parse('form_input.ward.blank_title');
        }
        foreach ($array_ward as $ward) {
            $xtpl->assign('WARD', $ward);
            
            if ($location_array_config['allow_type'] and ! empty($ward['type'])) {
                $xtpl->parse('form_input.ward.loop.type');
            }
            
            $xtpl->parse('form_input.ward.loop');
        }
        if ($data_config['multiple_ward']) {
            $xtpl->parse('form_input.ward.multiple');
        } else {
            $xtpl->parse('form_input.ward.none_multiple');
        }
        $xtpl->parse('form_input.ward');
    }
    
    $xtpl->parse('form_input');
    $form_input = $xtpl->text('form_input');
    
    $xtpl->assign('FORM_INPUT', $form_input);
    
    $xtpl->parse('main');
    return $xtpl->text('main');
}