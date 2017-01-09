<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 16 Dec 2015 08:12:58 GMT
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    die($alias);
}

// change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $wardid = $nv_Request->get_int('wardid', 'post, get', 0);
    $content = 'NO_' . $wardid;
    
    $query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid=' . $wardid;
    $row = $db->query($query)->fetch();
    if (isset($row['status'])) {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_ward SET status=' . intval($status) . ' WHERE wardid=' . $wardid;
        $db->query($query);
        $content = 'OK_' . $wardid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('delete_wardid', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $wardid = $nv_Request->get_int('delete_wardid', 'get');
    $provinceid = $nv_Request->get_int('provinceid', 'get', 0);
    $districtid = $nv_Request->get_int('districtid', 'get', 0);
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if (! empty($wardid) and $delete_checkss == md5($wardid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward  WHERE wardid = ' . $wardid);
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&provinceid=' . $provinceid . '&districtid=' . $districtid);
        die();
    }
}

$row = array();
$error = array();
$row['wardid'] = $nv_Request->get_int('wardid', 'post,get', 0);
$row['provinceid'] = $nv_Request->get_int('provinceid', 'post,get', 0);
$row['districtid'] = $nv_Request->get_int('districtid', 'post,get', 0);

$location = new Location();

$array_district = $location->getArrayDistrict('', $row['provinceid']);
if (! isset($array_district[$row['districtid']])) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=province');
    die();
}

$province_info = $location->getProvinceInfo($row['provinceid']);
$row['countryid'] = $province_info['countryid'];

$array_country = $location->getArrayCountry();
if (! isset($array_country[$row['countryid']])) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=country');
    die();
}

$array_province = $location->getArrayProvince('', $row['countryid']);
if (! isset($array_province[$row['provinceid']])) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=province');
    die();
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['code'] = $nv_Request->get_title('code', 'post,get', '');
    $row['districtid'] = $nv_Request->get_int('districtid', 'post,get', 0);
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['type'] = $nv_Request->get_title('type', 'post', '');
    $row['location'] = $nv_Request->get_title('location', 'post', '');
    $row['alias'] = $nv_Request->get_title('area_alias', 'post', '', 1);
    if (empty($row['alias'])) {
        $row['alias'] = change_alias($row['title']);
        
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid != :wardid AND alias = :alias');
        $stmt->bindParam(':wardid', $row['wardid'], PDO::PARAM_STR);
        $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->fetchColumn()) {
            $weight = $db->query('SELECT MAX(wardid) FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid=' . $row['wardid'] . ' AND districtid=' . $row['districtid'])->fetchColumn();
            $weight = intval($weight) + 1;
            $row['alias'] = $row['alias'] . '-' . $weight;
        }
    }
    
    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_title'];
    } elseif (empty($row['districtid'])) {
        $error[] = $lang_module['error_required_districtid_districtid'];
    }
    
    $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid=' . $db->quote($row['wardid']))
        ->fetchColumn();
    if ($count > 0 and $row['wardid'] == 0) {
        $error[] = $lang_module['error_required_wardid_exist'];
    }
    
    if (empty($error)) {
        try {
            if (empty($row['wardid'])) {
                $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_ward (code, districtid, title, alias, type, location) VALUES (:code, :districtid, :title, :alias, :type, :location)');
            } else {
                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_ward SET code = :code, districtid = :districtid, title = :title, alias = :alias, type = :type, location = :location WHERE wardid=' . $row['wardid']);
            }
            $stmt->bindParam(':code', $row['code'], PDO::PARAM_STR);
            $stmt->bindParam(':districtid', $row['districtid'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':type', $row['type'], PDO::PARAM_STR);
            $stmt->bindParam(':location', $row['location'], PDO::PARAM_STR);
            
            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&provinceid=' . $row['provinceid'] . '&districtid=' . $row['districtid']);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); // Remove this line after checks finished
        }
    }
} elseif ($row['wardid'] > 0) {
    $countryid = $row['countryid'];
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid=' . $row['wardid'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
    $row['countryid'] = $countryid;
    $row['provinceid'] = $db->query('SELECT provinceid FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid=' . $row['districtid'])->fetchColumn();
} else {
    $row['code'] = '';
    $row['title'] = '';
    $row['alias'] = '';
    $row['status'] = 1;
    $row['type'] = '';
}

$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (! $nv_Request->isset_request('id', 'post,get')) {
    $where = '';
    $show_view = true;
    $per_page = 10;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from('' . $db_config['prefix'] . '_' . $module_data . '_ward');
    
    if (! empty($q)) {
        $where .= ' AND ( wardid LIKE :q_wardid OR title LIKE :q_title OR type LIKE :q_type OR alias LIKE :q_alias OR location LIKE :q_location)';
    }
    $db->where('districtid=' . $db->quote($row['districtid']) . $where);
    $sth = $db->prepare($db->sql());
    
    if (! empty($q)) {
        $sth->bindValue(':q_wardid', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
        $sth->bindValue(':q_alias', '%' . $q . '%');
        $sth->bindValue(':q_type', '%' . $q . '%');
        $sth->bindValue(':q_location', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();
    
    $db->select('*')
        ->order('wardid ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    
    $sth = $db->prepare($db->sql());
    
    if (! empty($q)) {
        $sth->bindValue(':q_wardid', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
        $sth->bindValue(':q_alias', '%' . $q . '%');
        $sth->bindValue(':q_type', '%' . $q . '%');
        $sth->bindValue(':q_location', '%' . $q . '%');
    }
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('Q', $q);

if ($show_view) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;provinceid=' . $row['provinceid'] . '&amp;districtid=' . $row['districtid'];
    if (! empty($q)) {
        $base_url .= '&q=' . $q;
    }
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (! empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }
    $number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
    while ($view = $sth->fetch()) {
        $view['number'] = $number ++;
        $view['count'] = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_ward WHERE wardid=' . $db->quote($view['wardid']))
            ->fetchColumn();
        $xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;provinceid=' . $row['provinceid'] . '&amp;districtid=' . $row['districtid'] . '&amp;wardid=' . $view['wardid'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;provinceid=' . $row['provinceid'] . '&amp;districtid=' . $row['districtid'] . '&amp;delete_wardid=' . $view['wardid'] . '&amp;delete_checkss=' . md5($view['wardid'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $view['link_ward'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=ward&amp;wardid=' . $view['wardid'];
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}

if (! empty($array_district)) {
    foreach ($array_district as $district) {
        $district['selected'] = $district['districtid'] == $row['districtid'] ? 'selected="selected"' : '';
        $xtpl->assign('DISTRICT', $district);
        $xtpl->parse('main.district');
    }
}

if (! empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

if (empty($row['wardid'])) {
    $xtpl->parse('main.auto_get_alias');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$array_mod_title = array(
    array(
        'title' => $lang_module['main'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
    ),
    array(
        'title' => $array_country[$row['countryid']]['title'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=province&amp;countryid=' . $row['countryid']
    ),
    array(
        'title' => ! empty($array_province[$row['provinceid']]['type']) ? $array_province[$row['provinceid']]['type'] . ' ' . $array_province[$row['provinceid']]['title'] : $array_province[$row['provinceid']]['title'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=district&amp;countryid=' . $row['countryid'] . '&amp;provinceid=' . $row['provinceid']
    ),
    array(
        'title' => ! empty($array_district[$row['districtid']]['type']) ? $array_district[$row['districtid']]['type'] . ' ' . $array_district[$row['districtid']]['title'] : $array_district[$row['districtid']]['title']
    )
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';