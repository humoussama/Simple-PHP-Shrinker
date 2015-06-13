<?php

require_once("../inc/config.php");

checkAdmin($HTTP_SESSION_VARS['inadmin']);

if($ad) updateAd($ad_text);

$smarty = new Smarty;
$smarty->template_dir = "$dir_ws/themes/admin";
$smarty->compile_dir = "$dir_ws/themes_c";
$smarty->assign("ad",getAd());
$smarty->display("ad.htm");

?>