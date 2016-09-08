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
    $countryid = $nv_Request->get_int('countryid', 'post, get', 0);
    $content = 'NO_' . $countryid;
    
    $query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid=' . $countryid;
    $row = $db->query($query)->fetch();
    if (isset($row['status'])) {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET status=' . intval($status) . ' WHERE countryid=' . $countryid;
        $db->query($query);
        $content = 'OK_' . $countryid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $countryid = $nv_Request->get_int('countryid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $countryid;
    if ($new_vid > 0) {
        $sql = 'SELECT countryid FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid!=' . $countryid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++ $weight;
            if ($weight == $new_vid)
                ++ $weight;
            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET weight=' . $weight . ' WHERE countryid=' . $row['countryid'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET weight=' . $new_vid . ' WHERE countryid=' . $db->quote($countryid);
        $db->query($sql);
        $content = 'OK_' . $countryid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('delete_countryid', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $countryid = $nv_Request->get_title('delete_countryid', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if (! empty($countryid) and $delete_checkss == md5($countryid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $weight = 0;
        $sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid =' . $db->quote($countryid);
        $result = $db->query($sql);
        list ($weight) = $result->fetch(3);
        
        // Xoa quoc gia
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_country  WHERE countryid = ' . $db->quote($countryid));
        if ($weight > 0) {
            $sql = 'SELECT countryid, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE weight >' . $weight;
            $result = $db->query($sql);
            while (list ($_countryid, $weight) = $result->fetch(3)) {
                $weight --;
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET weight=' . $weight . ' WHERE countryid=' . intval($_countryid));
            }
        }
        
        // Xoa Tinh/Thanh pho truc thuoc
        $result = $db->query('SELECT provinceid FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE countryid=' . $countryid);
        while (list ($provinceid) = $result->fetch(3)) {
            nv_location_delete_province($provinceid);
        }
        
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array();
$error = array();
$row['countryid'] = $nv_Request->get_int('countryid', 'post,get', 0);

if ($nv_Request->isset_request('submit', 'post')) {
    $row['code'] = $nv_Request->get_title('code', 'post,get', '');
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['alias'] = $nv_Request->get_title('alias', 'post', '');
    
    $row['alias'] = $nv_Request->get_title('area_alias', 'post', '', 1);
    if (empty($row['alias'])) {
        $row['alias'] = change_alias($row['title']);
        
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid !=' . $row['countryid'] . ' AND alias = :alias');
        $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->fetchColumn()) {
            $weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_country')->fetchColumn();
            $weight = intval($weight) + 1;
            $row['alias'] = $row['alias'] . '-' . $weight;
        }
    }
    
    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_title'];
    }
    
    $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid=' . $row['countryid'])->fetchColumn();
    if ($count > 0 and empty($row['countryid'])) {
        $error[] = $lang_module['error_required_countryid_exist'];
    }
    
    if (empty($error)) {
        try {
            if (empty($row['countryid'])) {
                $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_country (code, title, alias, weight) VALUES (:code, :title, :alias, :weight)');
                
                $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_country')->fetchColumn();
                $weight = intval($weight) + 1;
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET code = :code, title = :title, alias = :alias WHERE countryid=' . $row['countryid']);
            }
            $stmt->bindParam(':code', $row['code'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            
            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); // Remove this line after checks finished
        }
    }
} elseif ($row['countryid'] > 0) {
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE countryid=' . $row['countryid'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['title'] = '';
    $row['alias'] = '';
    $row['status'] = 1;
}

$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (! $nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from('' . $db_config['prefix'] . '_' . $module_data . '_country');
    
    if (! empty($q)) {
        $db->where('code LIKE :q_code OR title LIKE :q_title');
    }
    $sth = $db->prepare($db->sql());
    
    if (! empty($q)) {
        $sth->bindValue(':q_code', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();
    
    $db->select('*')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    
    if (! empty($q)) {
        $sth->bindValue(':q_code', '%' . $q . '%');
        $sth->bindValue(':q_title', '%' . $q . '%');
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
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
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
        $view['count'] = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE countryid=' . $db->quote($view['countryid']))
            ->fetchColumn();
        for ($i = 1; $i <= $num_items; ++ $i) {
            $view['code'] = ! empty($view['code']) ? $view['code'] : '-';
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.view.loop.weight_loop');
        }
        $xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;countryid=' . $view['countryid'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_countryid=' . $view['countryid'] . '&amp;delete_checkss=' . md5($view['countryid'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $view['link_province'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=province&amp;countryid=' . $view['countryid'];
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}

if (! empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

if (empty($row['countryid'])) {
    $xtpl->parse('main.auto_get_alias');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['country'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';