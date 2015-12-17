<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 16 Dec 2015 08:12:58 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/*
$re = $db->query( 'select * from nv4_location_district' );
while( $row = $re->fetch() )
{
	$count = $db->query( 'select COUNT(*) from nv4_location_district' )->fetchColumn();
	if( $count == 0 )
	{
		$db->query( 'update nv4_location_district set alias=' . $db->quote( change_alias( $row['title']) ) . ' where districtid=' . $row['districtid'] );
	}
	else {
		$db->query( 'update nv4_location_district set alias=' . $db->quote( change_alias( $row['title']) . '-' . $row['districtid'] ) . ' where districtid=' . $row['districtid'] );
	}
}
die('1');
 *
 */

if ( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
	$alias = $nv_Request->get_title( 'get_alias_title', 'post', '' );
	$alias = change_alias( $alias );
	die( $alias );
}

//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$districtid = $nv_Request->get_title( 'districtid', 'post, get', '' );
	$content = 'NO_' . $districtid;

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid=' . $districtid;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_district SET status=' . intval( $status ) . ' WHERE districtid=' . $districtid;
		$db->query( $query );
		$content = 'OK_' . $districtid;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$provinceid = $nv_Request->get_title( 'provinceid', 'post, get', '' );
	$districtid = $nv_Request->get_title( 'districtid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $districtid;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT districtid FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid!=' . $db->quote( $districtid ) . ' AND provinceid=' . $db->quote( $provinceid ) . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_district SET weight=' . $weight . ' WHERE districtid=' . $db->quote( $row['districtid'] ) . ' AND provinceid=' . $db->quote( $provinceid );
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_district SET weight=' . $new_vid . ' WHERE districtid=' . $db->quote( $districtid ) . ' AND provinceid=' . $db->quote( $provinceid );
		$db->query( $sql );
		$content = 'OK_' . $districtid;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if ( $nv_Request->isset_request( 'delete_districtid', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$districtid = $nv_Request->get_title( 'delete_districtid', 'get' );
	$provinceid = $nv_Request->get_title( 'provinceid', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( !empty( $districtid ) and $delete_checkss == md5( $districtid . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight=0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid =' . $db->quote( $districtid );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );

		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_district  WHERE districtid = ' . $db->quote( $districtid ) . ' AND provinceid=' . $db->quote( $provinceid ) );
		if( $weight > 0)
		{
			$sql = 'SELECT districtid, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $districtid, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_district SET weight=' . $weight . ' WHERE districtid=' . intval( $districtid ));
			}
		}
		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&countryid=' . $countryid );
		die();
	}
}

$row = array();
$error = array();
$row['districtid'] = $nv_Request->get_title( 'districtid', 'post,get', '' );
$row['countryid'] = $nv_Request->get_title( 'countryid', 'post,get', '' );
$row['provinceid'] = $nv_Request->get_title( 'provinceid', 'post,get', '' );

$array_country = nv_location_get_country();
if( !isset( $array_country[$row['countryid']] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=country' );
	die();
}

$array_province = nv_location_get_province( $row['countryid'] );
if( !isset( $array_province[$row['provinceid']] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=province' );
	die();
}

$is_edit = $nv_Request->get_int( 'is_edit', 'post,get', 0 );

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['districtid'] = $nv_Request->get_title( 'districtid', 'post,get', '' );
	$row['countryid'] = $nv_Request->get_title( 'countryid', 'post,get', '' );
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['type'] = $nv_Request->get_title( 'type', 'post', '' );
	$row['location'] = $nv_Request->get_title( 'location', 'post', '' );
	$row['alias'] = $nv_Request->get_title( 'area_alias', 'post', '', 1 );
	if( empty( $row['alias'] ) )
	{
		$row['alias'] = change_alias( $row['title'] );

		$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid != :districtid AND alias = :alias' );
		$stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
		$stmt->execute();

		if( $stmt->fetchColumn() )
		{
			$row['alias'] = $row['alias'] . '-' . $row['districtid'];
		}
	}

	$count = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid=' . $db->quote( $row['districtid'] ) )->fetchColumn();

	if( empty( $row['districtid'] ) )
	{
		$error[] = $lang_module['error_required_districtid'];
	}
	elseif( $count > 0 and !$is_edit )
	{
		$error[] = $lang_module['error_required_districtid_exist'];
	}
	elseif( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}

	if( empty( $error ) )
	{
		try
		{
			if( !$is_edit )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_district (districtid, provinceid, title, alias, type, location, weight) VALUES (:districtid, :provinceid, :title, :alias, :type, :location, :weight)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_district' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_district SET provinceid = :provinceid, title = :title, alias = :alias, type = :type, location = :location WHERE districtid=:districtid' );
			}
			$stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_STR );
			$stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_STR );
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':type', $row['type'], PDO::PARAM_STR );
			$stmt->bindParam( ':location', $row['location'], PDO::PARAM_STR );

			$exc = $stmt->execute();
			if( $exc )
			{
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&countryid=' . $row['countryid'] . '&provinceid=' . $row['provinceid'] );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['districtid'] > 0 )
{
	$countryid = $row['countryid'];
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid=' . $row['districtid'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	$row['countryid'] = $countryid;
}
else
{
	$row['districtid'] = '';
	$row['title'] = '';
	$row['alias'] = '';
	$row['status'] = 1;
	$row['type'] = '';
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$where = '';
	$show_view = true;
	$per_page = 10;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_district' );

	if( ! empty( $q ) )
	{
		$where .= ' AND ( districtid LIKE :q_districtid OR title LIKE :q_title OR type LIKE :q_type OR alias LIKE :q_alias OR location LIKE :q_location)';
	}
	$db->where( 'provinceid=' . $db->quote( $row['provinceid'] ) . $where );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_districtid', '%' . $q . '%' );
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_alias', '%' . $q . '%' );
		$sth->bindValue( ':q_type', '%' . $q . '%' );
		$sth->bindValue( ':q_location', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )
		->order( 'weight ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_districtid', '%' . $q . '%' );
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_alias', '%' . $q . '%' );
		$sth->bindValue( ':q_type', '%' . $q . '%' );
		$sth->bindValue( ':q_location', '%' . $q . '%' );
	}
	$sth->execute();
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_UPLOAD', $module_upload );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'IS_EDIT', $is_edit );
$xtpl->assign( 'Q', $q );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;countryid=' . $row['countryid'] . '&amp;provinceid=' . $row['provinceid'];
	if( ! empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.view.generate_page' );
	}
	$number = $page > 1 ? ($per_page * ( $page - 1 ) ) + 1 : 1;
	while( $view = $sth->fetch() )
	{
		$view['count'] = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE districtid=' . $db->quote( $view['districtid'] ) )->fetchColumn();
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['weight'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}
		$xtpl->assign( 'CHECK', $view['status'] == 1 ? 'checked' : '' );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;is_edit=1&amp;countryid=' . $row['countryid'] . '&amp;provinceid=' . $row['provinceid'] . '&amp;districtid=' . $view['districtid'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;provinceid=' . $row['provinceid'] . '&amp;delete_districtid=' . $view['districtid'] . '&amp;delete_checkss=' . md5( $view['districtid'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$view['link_district'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=district&amp;districtid=' . $view['districtid'];
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

if( !empty( $array_province ) )
{
	foreach( $array_province as $province )
	{
		$province['selected'] = $province['provinceid'] == $row['provinceid'] ? 'selected="selected"' : '';
		$xtpl->assign( 'PROVINCE', $province );
		$xtpl->parse( 'main.province' );
	}
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

if( empty( $row['districtid'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}
elseif( $is_edit )
{
	$xtpl->parse( 'main.disabled' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

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
		'title' => $array_province[$row['provinceid']]['title']
	)
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';