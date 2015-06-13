<?php

require_once("./inc/config.php");
$smarty = new Smarty;
$smarty->template_dir = "$dir_ws/themes";
$smarty->compile_dir = "$dir_ws/themes_c";
$smarty->assign("url_to_index",$config['url_to_index']);

$HTTP_SESSION_VARS['searchurl'] = '';
$randlink = '';

if($_SERVER['QUERY_STRING'] && !$fullurl && !preg_match('/[\W]/',$_SERVER['QUERY_STRING'])){
  //loading index.php with query
  if($res = checkQueryString($_SERVER['QUERY_STRING'])){
    //query found
    if($res['showsplash']){
      $smarty->assign("fullurl",$res['fullurl']);
      $smarty->assign("refreshrate",$config['refreshrate']);
      $smarty->assign("ad",getAd());
      $smarty->assign("records",getNumb());
      $smarty->display("connexion.htm");
    }
    else js_redirect($res['fullurl']);
  }
  else{
    //query did not find
    $smarty->display("erreur.htm");
  }
}
else{

  if($fullurl){
    preg_match("/^((http:\/\/)*)(.*)/i", $fullurl, $matches1);
    preg_match("/^(http:\/\/)*/i", $fullurl, $matches2);

    if($matches1[1]==$matches2[1] and $matches1[3]) $u = parse_url($fullurl);
    else $u['host'] = $fullurl;

    $ip = gethostbyname($u['host']);

    if($ip == $u['host']){
      js_alert ('No such host!');
    }
    else $randlink = addLink($fullurl,$ip);
  }

  //loading index.php
  $smarty->assign("fullurl",$fullurl);
  $smarty->assign("randlink",$randlink);

  $smarty->assign("ad",getAd());
  $smarty->assign("records",getNumb());
  $smarty->display("index.htm");
}

?>