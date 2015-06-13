<?php


function PageSelectorPrepare($item_cnt){
  global $page,$ipp,$page_cnt,$page_show_all,$units_per_page;
  if($page_show_all=="1") $ipp=$item_cnt;
  else $ipp=$units_per_page;
  if($item_cnt)$page_cnt=$item_cnt<$ipp ? 0 : ceil($item_cnt/$ipp)-1;
  else $page_cnt;
  if(empty($page) || $page<0)  $page=0; elseif ($page>$page_cnt) $page=$page_cnt;
}

function PageSelectorPrint($url,$page_req){
  global $page,$ipp,$page_cnt,$Colors,$page_show_all;

  $page_nxt=5;
  $page_prv=4;

  $page_beg=($page+$page_nxt)<$page_cnt ? ($page-$page_prv) : ($page_cnt-$page_prv-$page_nxt);
  if($page_beg<0) $page_beg=0;
  $page_end=$page>$page_prv ? ($page+$page_nxt) : ($page_prv+$page_nxt);
  if($page_end>$page_cnt) $page_end=$page_cnt;
  for($page_sel="",$i=$page_beg;$i<=$page_end;$i++){
    if($page==$i) $page_sel.="<b>".($i+1)."</b>&nbsp;";
    else $page_sel.="<a href=\"$url?page=$i$page_req\">".($i+1)."</a>&nbsp;";
  }

  //Page selector
  return "<table width=95% border=0 cellspacing=0>
  <tr>
  <td width=110>&nbsp;".( $page!=0 ? "<a href=\"$url?page=0$page_req\">&lt;&lt;&nbsp;begin</a>" : "&lt;&lt;&nbsp;begin" )."&nbsp;&nbsp;".( $page>0 ?  "<a href=\"$url?page=".($page-1).$page_req."\">&lt;&nbsp;prev</a>" : "&lt;&nbsp;prev" )."</td>
  <td nowrap>&nbsp;page&nbsp;&nbsp;$page_sel&nbsp;of&nbsp;".($page_cnt+1)."</td>
  <td align=right nowrap>".( $page_show_all==1 ? "<a href=\"$url?page=0&page_show_all=0$page_req\">collapse</a>" : ( $page_cnt>0 ? "<a href=\"$url?page=0&page_show_all=1$page_req\">expand</a>" : "expand" ))." |
  ".( $page<$page_cnt ?  "<a href=\"$url?page=".($page+1).$page_req."\">next&nbsp;&gt;</a>" : "next&nbsp;&gt;" )."&nbsp;&nbsp;".( $page!=$page_cnt ? "<a href=\"$url?page=".$page_cnt.$page_req."\">end&nbsp;&gt;&gt;</a>" : "end&nbsp;&gt;&gt;" )."&nbsp;</td>
  </tr></table>";
}

?>