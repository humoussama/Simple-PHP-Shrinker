<?php

if(!isset($PHP_SELF)) $PHP_SELF=getenv("SCRIPT_NAME");
if(!isset($QUERY_STRING)) $QUERY_STRING = "";
if(!isset($REQUEST_URI)) $REQUEST_URI = $PHP_SELF;

$ADO_HOSTNAME = $config['dbhost'];
$ADO_DBTYPE   = $config['dbtype'];
$ADO_DATABASE = $config['dbname'];
$ADO_USERNAME = $config['dbuser'];
$ADO_PASSWORD = $config['dbpass'];

$QUB_Caching = false;

ADOLoadCode($ADO_DBTYPE);

$myDB = &ADONewConnection($ADO_DBTYPE);
if($ADO_DBTYPE == "access" || $ADO_DBTYPE == "odbc") $myDB->PConnect($ADO_DATABASE, $ADO_USERNAME,$ADO_PASSWORD);
elseif($ADO_DBTYPE == "ibase") $myDB->PConnect($ADO_HOSTNAME.":".$ADO_DATABASE,$ADO_USERNAME,$ADO_PASSWORD);
else $myDB->PConnect($ADO_HOSTNAME,$ADO_USERNAME,$ADO_PASSWORD,$ADO_DATABASE);

?>