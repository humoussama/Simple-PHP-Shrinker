<?php

$dir_ws = str_replace('\\','/',substr(dirname(__FILE__),0,-14));

ini_set("register_globals", '1');
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require_once("$dir_ws/inc/i_INI.php");

$ini = new Ini;
$ini->connect("$dir_ws/inc/config.ini.php");

//DATABASE CONFIGURATION
$config['dbtype'] = $ini->read('DATABASE','dbtype');
$config['dbhost'] = $ini->read('DATABASE','dbhost');
$config['dbname'] = $ini->read('DATABASE','dbname');
$config['dbuser'] = $ini->read('DATABASE','dbuser');
$config['dbpass'] = $ini->read('DATABASE','dbpass');

//ADMIN'S LOGIN AND PASSWORD
$config['alogin'] = $ini->read('ADMIN','alogin');
$config['apwd']   = $ini->read('ADMIN','apwd');

//ROWS PER PAGE
$units_per_page = $config['upp'] = $ini->read('WEBSITE','upp');

//LIMIT OF RECORDS THAT SELECTED FROM DATABASE
$config['limit'] = $ini->read('WEBSITE','limit');

//FREQUENCY OF REFRESHING
$config['refreshrate'] = $ini->read('WEBSITE','refreshrate');

//THE LINKS' LENGHT
$config['link_lenght'] = $ini->read('WEBSITE','link_lenght');

//URL TO INDEX
$config['url_to_index'] = $ini->read('WEBSITE','url_to_index');

$ini->close();


session_start();

function gpc_extract($array, &$target){
  if (!is_array($array)) return FALSE;
  foreach($array as $k=>$v){
    if(is_array($v)) gpc_extract($v, $target[$k]);
    else $target[$k] = $v;
  }
  return TRUE;
}
if(phpversion()>='4.2.0') $a_v = array('_REQUEST','_ENV','_SERVER','_POST','_GET','_COOKIE','_FILES');
else $a_v = array('HTTP_POST_VARS','HTTP_GET_VARS','HTTP_COOKIE_VARS','HTTP_SERVER_VARS','HTTP_ENV_VARS');
foreach($a_v as $_s) if(!empty($$_s)) gpc_extract($$_s, $GLOBALS);
unset($a_v);

require_once("$dir_ws/inc/adodb/adodb.inc.php");
require_once("$dir_ws/inc/smarty/Smarty.class.php");
require_once("$dir_ws/inc/i_PageSelector.php");
require_once("$dir_ws/inc/i_myAPI.php");
require_once("$dir_ws/inc/i_API.php");
require_once("$dir_ws/inc/i_myADODB.php");

?>