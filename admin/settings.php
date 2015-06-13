<?php


require_once("../inc/config.php");

checkAdmin($HTTP_SESSION_VARS['inadmin']);

if($settings) updateSettings($alogin,$apwd,$upp,$url_to_index,$limit,$refreshrate,$link_lenght);

$smarty = new Smarty;
$smarty->template_dir = "$dir_ws/themes/admin";
$smarty->compile_dir = "$dir_ws/themes_c";
$smarty->assign("sett",getSettings());
$smarty->display("settings.htm");

?>