<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 */
if (! defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_country";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_district";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_province";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_ward";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_country(
  countryid smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(10) NOT NULL,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (countryid),
  UNIQUE KEY countryid (code)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_district(
  districtid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(5) NOT NULL,
  provinceid varchar(5) NOT NULL,
  title varchar(100) NOT NULL,
  alias varchar(100) NOT NULL,
  type varchar(30) NOT NULL,
  location varchar(30) NOT NULL,
  weight mediumint(8) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (districtid),
  KEY provinceid (provinceid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_province(
  provinceid mediumint(4) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(5) NOT NULL,
  countryid varchar(10) NOT NULL,
  title varchar(100) NOT NULL,
  alias varchar(100) NOT NULL,
  type varchar(30) NOT NULL,
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (provinceid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_ward(
  wardid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  districtid varchar(5) NOT NULL,
  title varchar(100) NOT NULL,
  alias varchar(100) NOT NULL,
  code varchar(5) NOT NULL,
  type varchar(30) NOT NULL,
  location varchar(30) NOT NULL,
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (wardid),
  UNIQUE KEY alias (alias),
  UNIQUE KEY code (code),
  KEY districtid (districtid)
) ENGINE=MyISAM";

$data = array();
$data['allow_type'] = 1;

foreach ($data as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
}