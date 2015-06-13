<?php

function addLink($fullurl,$ip){
  global $myDB;
  global $config;
  $randlink = randomLink($config['link_lenght']);
  $query = "INSERT INTO tinylink_websites (fullurl,ip,tinylink,showsplash) VALUES ('$fullurl','$ip','$randlink',1)";
  $add = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $add->Close();
  return $randlink;
}

function checkQueryString($query_string){
  global $myDB;
  global $config;
  $query = "SELECT * FROM tinylink_websites WHERE tinylink='$query_string'" ;
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $wid = $result->Fields("wid");
  if($wid){
    $query2 = "SELECT * FROM tinylink_statistics WHERE wid=$wid";
    $result2 = $myDB->Execute($query2) or DIE($myDB->ErrorMsg());
    $wid2 = $result2->Fields("wid");
    if($wid2) $query3 = "UPDATE tinylink_statistics SET numb='".($result2->Fields("numb")+1)."' WHERE wid=$wid2";
    else $query3 = "INSERT INTO tinylink_statistics (wid,numb) VALUES ($wid,1)";
    $result3 = $myDB->Execute($query3) or DIE($myDB->ErrorMsg());
    $result2->Close();
    $result3->Close();
    $query2 = "INSERT INTO tinylink_total (date) VALUES (NOW())";
    $result2 = $myDB->Execute($query2) or DIE($myDB->ErrorMsg());
    $result2->Close();
    $tmp['fullurl'] = $result->Fields("fullurl");
    $tmp['showsplash'] = $result->Fields("showsplash");
    $result->Close();
  }
  else{
    $tmp = 0;
  }
  return $tmp;
}

function checkAdmin($inadmin){
  global $config;
  if($inadmin!=1) js_redirect($config['url_to_index']);
}

function allUrls($orderby){
  global $myDB,$HTTP_SESSION_VARS;
  global $config;
  global $page,$ipp,$page_cnt,$Colors,$page_show_all,$units_per_page,$pageSelector,$maxNumb;

  $query = "SELECT * FROM tinylink_websites";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $maxNumb = $result->RecordCount();
  $result->Close();
  if($config['limit']<$maxNumb) PageSelectorPrepare($config['limit']);
  else PageSelectorPrepare($maxNumb);
  $pageSelector = PageSelectorPrint("admin.php","");

  if(!$HTTP_SESSION_VARS['s_order']) session_register('s_order');
  if(!$HTTP_SESSION_VARS['s_ip']) session_register('s_ip');
  if(!$HTTP_SESSION_VARS['s_fullurl']) session_register('s_fullurl');
  if(!$HTTP_SESSION_VARS['s_numb']) session_register('s_numb');
  if(!$HTTP_SESSION_VARS['s_showsplash']) session_register('s_showsplash');

  if($orderby){
    switch ($orderby) {
      case 'ip':
        $HTTP_SESSION_VARS['s_ip']=($HTTP_SESSION_VARS['s_ip']=='ip ASC')?'ip DESC':'ip ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_ip'];
        break;
      case 'fullurl':
        $HTTP_SESSION_VARS['s_fullurl']=($HTTP_SESSION_VARS['s_fullurl']=='fullurl ASC')?'fullurl DESC':'fullurl ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_fullurl'];
        break;
      case 'numb':
        $HTTP_SESSION_VARS['s_numb']=($HTTP_SESSION_VARS['s_numb']=='numb ASC')?'numb DESC':'numb ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_numb'];
        break;
      case 'showsplash':
        $HTTP_SESSION_VARS['s_showsplash']=($HTTP_SESSION_VARS['s_showsplash']=='showsplash ASC')?'showsplash DESC':'showsplash ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_showsplash'];
        break;
    }
  }
  elseif(!$HTTP_SESSION_VARS['s_order']) $HTTP_SESSION_VARS['s_order'] = "numb DESC";

  $query = "SELECT * FROM tinylink_websites LEFT JOIN tinylink_statistics ON tinylink_statistics.wid=tinylink_websites.wid ORDER BY {$HTTP_SESSION_VARS['s_order']} LIMIT ".($ipp*$page).",$ipp";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $f = 0;
  $Repeat1__numRows = -1;
  while (($Repeat1__numRows-- != 0) && (!$result->EOF)) {
    $tmp[$f]['n'] = $units_per_page*$page+$f+1;
    $tmp[$f]['fullurl'] = $result->Fields("fullurl");

    if(strlen($tmp[$f]['fullurl'])>30) $tmp[$f]['link'] = substr($tmp[$f]['fullurl'],0,30)."...";
    else $tmp[$f]['link'] = $tmp[$f]['fullurl'];

    $tmp[$f]['ip'] = $result->Fields("ip");
    $tmp[$f]['tinylink'] = $result->Fields("tinylink");
    if($result->Fields("numb")) $tmp[$f]['numb'] = $result->Fields("numb");
    else $tmp[$f]['numb'] = "0";
    $tmp[$f]['showsplash'] = ($result->Fields("showsplash")==1)?'yes':'no';
    $tmp[$f++]['val']=($result->Fields("showsplash")==1)? 0 : 1;
    $result->MoveNext();
  }
  $result->Close();
  return $tmp;
}

function findUrls($surl,$orderby){
  global $myDB,$HTTP_SESSION_VARS;
  global $config;
  global $page,$ipp,$page_cnt,$Colors,$page_show_all,$units_per_page,$pageSelector,$maxNumb;

  $query = "SELECT * FROM tinylink_websites WHERE fullurl LIKE '%$surl%'";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $maxNumb = $result->RecordCount();
  $result->Close();
  PageSelectorPrepare($maxNumb);
  $pageSelector = PageSelectorPrint("admin.php","");

  if(!$HTTP_SESSION_VARS['s_order']) session_register('s_order');
  if(!$HTTP_SESSION_VARS['s_ip']) session_register('s_ip');
  if(!$HTTP_SESSION_VARS['s_fullurl']) session_register('s_fullurl');
  if(!$HTTP_SESSION_VARS['s_numb']) session_register('s_numb');
  if(!$HTTP_SESSION_VARS['s_showsplash']) session_register('s_showsplash');

  if($orderby){
    switch ($orderby) {
      case 'ip':
        $HTTP_SESSION_VARS['s_ip']=($HTTP_SESSION_VARS['s_ip']=='ip ASC')?'ip DESC':'ip ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_ip'];
          break;
      case 'fullurl':
        $HTTP_SESSION_VARS['s_fullurl']=($HTTP_SESSION_VARS['s_fullurl']=='fullurl ASC')?'fullurl DESC':'fullurl ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_fullurl'];
        break;
      case 'numb':
        $HTTP_SESSION_VARS['s_numb']=($HTTP_SESSION_VARS['s_numb']=='numb ASC')?'numb DESC':'numb ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_numb'];
        break;
      case 'showsplash':
        $HTTP_SESSION_VARS['s_showsplash']=($HTTP_SESSION_VARS['s_showsplash']=='showsplash ASC')?'showsplash DESC':'showsplash ASC';
        $HTTP_SESSION_VARS['s_order'] = $HTTP_SESSION_VARS['s_showsplash'];
        break;
    }
  }
  elseif(!$HTTP_SESSION_VARS['s_order']) $HTTP_SESSION_VARS['s_order'] = "numb DESC";

  $query = "SELECT * FROM tinylink_websites LEFT JOIN tinylink_statistics ON tinylink_statistics.wid=tinylink_websites.wid WHERE fullurl LIKE '%$surl%' ORDER BY {$HTTP_SESSION_VARS['s_order']} LIMIT ".($ipp*$page).",$ipp";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $f = 0;
  $Repeat1__numRows = -1;
  while (($Repeat1__numRows-- != 0) && (!$result->EOF)) {
    $tmp[$f]['n'] = $units_per_page*$page+$f+1;
    $tmp[$f]['fullurl'] = $result->Fields("fullurl");

    if(strlen($tmp[$f]['fullurl'])>30) $tmp[$f]['link'] = substr($tmp[$f]['fullurl'],0,30)."...";
    else $tmp[$f]['link'] = $tmp[$f]['fullurl'];

    $tmp[$f]['ip'] = $result->Fields("ip");
    $tmp[$f]['tinylink'] = $result->Fields("tinylink");
    if($result->Fields("numb")) $tmp[$f]['numb'] = $result->Fields("numb");
    else $tmp[$f]['numb'] = "0";
    $tmp[$f]['showsplash'] = ($result->Fields("showsplash")==1)?'yes':'no';
    $tmp[$f++]['val']=($result->Fields("showsplash")==1)? 0 : 1;
    $result->MoveNext();
  }
  $result->Close();
  return $tmp;
}

function changeAdd($val,$change){
  global $myDB;
  global $config;
  $query = "UPDATE tinylink_websites SET showsplash=$val WHERE tinylink='$change'";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $result->Close();
  //js_redirect('admin.php');
}

function clearRating(){
  global $myDB;
  global $config;
  $query = "DELETE from tinylink_statistics";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $result->Close();
  //js_redirect('admin.php');
}

function setAdd($val){
  global $myDB;
  global $config;
  $query = "UPDATE tinylink_websites SET showsplash=$val";
  $result = $myDB->Execute($query) or DIE($myDB->ErrorMsg());
  $result->Close();
  //js_redirect('admin.php');
}

function removeUrls($http_vars){
  global $myDB;
  global $config;
  foreach($http_vars as $tinylink){
    $query1 = "SELECT * FROM tinylink_websites WHERE tinylink='$tinylink'" ;
    $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
    $wid = $result1->Fields("wid");
    $query2 = "DELETE from tinylink_statistics WHERE wid='$wid'";
    $result2 = $myDB->Execute($query2) or DIE($myDB->ErrorMsg());
    $query3 = "DELETE from tinylink_websites WHERE wid='$wid'";
    $result3 = $myDB->Execute($query3) or DIE($myDB->ErrorMsg());
    $result1->Close();
    $result2->Close();
    $result3->Close();
  }
}

function getAd(){
  global $myDB;
  global $config;
  $query1 = "SELECT * FROM tinylink_ad WHERE aid=1" ;
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $ad = $result1->Fields("ad_text");
  $result1->Close();
  return $ad;
}

function updateAd($ad_text){
  global $myDB;
  global $config;
  $ad_text = addslashes($ad_text);
  $query1 = "UPDATE tinylink_ad SET ad_text='$ad_text' WHERE aid=1" ;
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $result1->Close();
}

function getSettings(){
  global $config,$dir_ws;
  $ini = new ini;
  $ini->connect("$dir_ws/inc/config.ini.php");
  $tmp['dbtype']    = $ini->read('DATABASE','dbtype');
  $tmp['dbhost']    = $ini->read('DATABASE','dbhost');
  $tmp['dbname']    = $ini->read('DATABASE','dbname');
  $tmp['dbuser']    = $ini->read('DATABASE','dbuser');
  $tmp['dbpass']    = $ini->read('DATABASE','dbpass');
  $tmp['alogin']    = $ini->read('ADMIN','alogin');
  $tmp['apwd']      = $ini->read('ADMIN','apwd');
  $tmp['url_to_index'] = $ini->read('WEBSITE','url_to_index');
  $tmp['upp']       = $ini->read('WEBSITE','upp');
  $tmp['limit']     = $ini->read('WEBSITE','limit');
  $tmp['refreshrate']   = $ini->read('WEBSITE','refreshrate');
  $tmp['link_lenght']   = $ini->read('WEBSITE','link_lenght');
  $ini->close();
  return $tmp;
}

function updateSettings($alogin,$apwd,$upp,$url_to_index,$limit,$refreshrate,$link_lenght){
  global $config,$dir_ws;
  $ini = new ini;
  $ini->connect("$dir_ws/inc/config.ini.php");
  $ini->write('ADMIN','alogin',$alogin);
  $ini->write('ADMIN','apwd',$apwd);
  $ini->write('WEBSITE','upp',$upp);
  $ini->write('WEBSITE','url_to_index',$url_to_index);
  $ini->write('WEBSITE','limit',$limit);
  $ini->write('WEBSITE','refreshrate',$refreshrate);
  $ini->write('WEBSITE','link_lenght',$link_lenght);
  $ini->close();
}

function getTotal(){
  global $myDB;

  $query1 = "SELECT count(tid) as counter FROM tinylink_total WHERE TO_DAYS(NOW()) - TO_DAYS(date) <= 1";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $day1 = $result1->Fields("counter");
  $result1->Close();

  $query1 = "SELECT count(tid) as counter FROM tinylink_total WHERE TO_DAYS(NOW()) - TO_DAYS(date) <= 7";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $day7 = $result1->Fields("counter");
  $result1->Close();

  $query1 = "SELECT count(tid) as counter FROM tinylink_total WHERE TO_DAYS(NOW()) - TO_DAYS(date) <= 30";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $day30 = $result1->Fields("counter");
  $result1->Close();
  
  $query1 = "SELECT count(tid) as counter FROM tinylink_total WHERE TO_DAYS(NOW()) - TO_DAYS(date) <= 90";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $day90 = $result1->Fields("counter");
  $result1->Close();
  
  $query1 = "SELECT count(tid) as counter FROM tinylink_total";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $dayAll = $result1->Fields("counter");
  $result1->Close();
  
  $arr = array(day1=>$day1,day7=>$day7,day30=>$day30,day90=>$day90,dayAll=>$dayAll);
  return $arr;
}

function getNumb(){
  global $myDB;
  $query1 = "SELECT count(tinylink) as number FROM tinylink_websites";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $number = $result1->Fields("number");
  $result1->Close();
  return $number;
}

function resetAll(){
  global $myDB;
  $query1 = "DELETE FROM tinylink_total";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $result1->Close();
}

function deleteAllRecords(){
  global $myDB;
  $query1 = "DELETE FROM tinylink_websites";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $result1->Close();
  $query1 = "DELETE FROM tinylink_statistics";
  $result1 = $myDB->Execute($query1) or DIE($myDB->ErrorMsg());
  $result1->Close();
}

?>