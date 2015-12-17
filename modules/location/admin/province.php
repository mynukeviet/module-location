<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 16 Dec 2015 08:12:58 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
	$alias = $nv_Request->get_title( 'get_alias_title', 'post', '' );
	$alias = change_alias( $alias );
	die( $alias );
}

//change status
if( $nv_Request->isset_request( 'change_status', 'post, get' ) )
{
	$provinceid = $nv_Request->get_title( 'provinceid', 'post, get', 0 );
	$content = 'NO_' . $provinceid;

	$query = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid=' . $provinceid;
	$row = $db->query( $query )->fetch();
	if( isset( $row['status'] ) )
	{
		$status = ( $row['status'] ) ? 0 : 1;
		$query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_province SET status=' . intval( $status ) . ' WHERE provinceid=' . $provinceid;
		$db->query( $query );
		$content = 'OK_' . $provinceid;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
	$provinceid = $nv_Request->get_title( 'provinceid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $provinceid;
	if( $new_vid > 0 )
	{
		$sql = 'SELECT provinceid FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid!=' . $db->quote( $provinceid ) . ' ORDER BY weight ASC';
		$result = $db->query( $sql );
		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_province SET weight=' . $weight . ' WHERE provinceid=' . $db->quote( $row['provinceid'] );
			$db->query( $sql );
		}
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_province SET weight=' . $new_vid . ' WHERE provinceid=' . $db->quote( $provinceid );
		$db->query( $sql );
		$content = 'OK_' . $provinceid;
	}
	nv_del_moduleCache( $module_name );
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if ( $nv_Request->isset_request( 'delete_provinceid', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$provinceid = $nv_Request->get_title( 'delete_provinceid', 'get' );
	$countryid = $nv_Request->get_title( 'countryid', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( !empty( $provinceid ) and $delete_checkss == md5( $provinceid . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$weight=0;
		$sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid =' . $db->quote( $provinceid );
		$result = $db->query( $sql );
		list( $weight) = $result->fetch( 3 );

		$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_province  WHERE provinceid = ' . $db->quote( $provinceid ) . ' AND countryid=' . $db->quote( $countryid ) );
		if( $weight > 0)
		{
			$sql = 'SELECT provinceid, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE weight >' . $weight;
			$result = $db->query( $sql );
			while(list( $provinceid, $weight) = $result->fetch( 3 ))
			{
				$weight--;
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_province SET weight=' . $weight . ' WHERE provinceid=' . intval( $provinceid ));
			}
		}
		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&countryid=' . $countryid );
		die();
	}
}

$row = array();
$error = array();
$row['provinceid'] = $nv_Request->get_title( 'provinceid', 'post,get', '' );
$row['countryid'] = $nv_Request->get_title( 'countryid', 'post,get', '' );

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE status=1';
$array_country = nv_db_cache( $sql, 'countryid', $module_name );
if( !isset( $array_country[$row['countryid']] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=country' );
	die();
}

$is_edit = $nv_Request->get_int( 'is_edit', 'post,get', 0 );

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['provinceid'] = $nv_Request->get_title( 'provinceid', 'post,get', '' );
	$row['countryid'] = $nv_Request->get_title( 'countryid', 'post,get', '' );
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['type'] = $nv_Request->get_title( 'type', 'post', '' );
	$row['alias'] = $nv_Request->get_title( 'area_alias', 'post', '', 1 );
	if( empty( $row['alias'] ) )
	{
		$row['alias'] = change_alias( $row['title'] );

		$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid != :provinceid AND alias = :alias' );
		$stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_STR );
		$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
		$stmt->execute();

		if( $stmt->fetchColumn() )
		{
			$weight = $db->query( 'SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_province' )->fetchColumn();
			$weight = intval( $weight ) + 1;
			$row['alias'] = $row['alias'] . '-' . $weight;
		}
	}

	$count = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid=' . $db->quote( $row['provinceid'] ) )->fetchColumn();

	if( empty( $row['provinceid'] ) )
	{
		$error[] = $lang_module['error_required_provinceid'];
	}
	elseif( $count > 0 and !$is_edit )
	{
		$error[] = $lang_module['error_required_provinceid_exist'];
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
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_province (provinceid, countryid, title, alias, type, weight) VALUES (:provinceid, :countryid, :title, :alias, :type, :weight)' );

				$weight = $db->query( 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_province' )->fetchColumn();
				$weight = intval( $weight ) + 1;
				$stmt->bindParam( ':weight', $weight, PDO::PARAM_INT );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_province SET countryid = :countryid, title = :title, alias = :alias, type = :type WHERE provinceid=:provinceid' );
			}
			$stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_STR );
			$stmt->bindParam( ':countryid', $row['countryid'], PDO::PARAM_STR );
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':type', $row['type'], PDO::PARAM_STR );

			$exc = $stmt->execute();
			if( $exc )
			{
				nv_del_moduleCache( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&countryid=' . $row['countryid'] );
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
elseif( $row['provinceid'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_province WHERE provinceid=' . $row['provinceid'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['provinceid'] = '';
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
	$show_view = true;
	$per_page = 10;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( '' . $db_config['prefix'] . '_' . $module_data . '_province' );

	if( ! empty( $q ) )
	{
		$db->where( 'provinceid LIKE :q_provinceid OR title LIKE :q_title OR type LIKE :q_type ' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_provinceid', '%' . $q . '%' );
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_type', '%' . $q . '%' );
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
		$sth->bindValue( ':q_provinceid', '%' . $q . '%' );
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_type', '%' . $q . '%' );
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
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;countryid=' . $row['countryid'];
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
		$view['count'] = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_district WHERE provinceid=' . $db->quote( $view['provinceid'] ) )->fetchColumn();
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ( $i == $view['weight'] ) ? ' selected="selected"' : '') );
			$xtpl->parse( 'main.view.loop.weight_loop' );
		}
		$xtpl->assign( 'CHECK', $view['status'] == 1 ? 'checked' : '' );
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;is_edit=1&amp;countryid=' . $row['countryid'] . '&amp;provinceid=' . $view['provinceid'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;countryid=' . $row['countryid'] . '&amp;delete_provinceid=' . $view['provinceid'] . '&amp;delete_checkss=' . md5( $view['provinceid'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$view['link_district'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=district&amp;provinceid=' . $view['provinceid'];
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

if( !empty( $array_country ) )
{
	foreach( $array_country as $country )
	{
		$country['selected'] = $country['countryid'] == $row['countryid'] ? 'selected="selected"' : '';
		$xtpl->assign( 'COUNTRY', $country );
		$xtpl->parse( 'main.country' );
	}
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

if( empty( $row['provinceid'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}
elseif( $is_edit )
{
	$xtpl->parse( 'main.disabled' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = sprintf( $lang_module['province_country'], $array_country[$row['countryid']]['title'] );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';