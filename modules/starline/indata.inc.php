<?php	
  global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }	
  $qry="0";
  // search filters
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['lgps_in'];
  } else {
   $session->data['lgps_in']=$qry;
  }
	
  if (!$qry) $qry="1";
  // SEARCH RESULTS
$res=SQLSelect("select  titlename TITLE ,descr DESCRIPTION ,max(typename) tip, max(ign) ign, max(status) status, max(arm) arm,max(etemp) etemp,max(ctemp) ctemp,max(mayak_temp) mayak_temp,max(device_id) device_id, max(svalue) svalue, max(battery) battery from (select titlename,descr,if (tip='typename', VALUE,null) typename,if (tip='alias', VALUE,null) alias,if (tip='battery', VALUE,null) battery, if (tip='device_id', VALUE,null) device_id,if (tip='etemp', VALUE,null) etemp,if (tip='ctemp', VALUE,null) ctemp,if (tip='mayak_temp', VALUE,null) mayak_temp ,if (tip='status', VALUE,null) status ,if (tip='arm', VALUE,null) arm ,if (tip='ign', VALUE,null) ign,if (tip='value', VALUE,null) svalue  from   (SELECT objects.TITLE titlename , objects.DESCRIPTION descr, substring(pvalues.PROPERTY_NAME, position('.' in pvalues.PROPERTY_NAME)+1) tip, pvalues.VALUE fROM `objects`,  `pvalues`WHERE  objects.class_id = (SELECT ID FROM `classes` WHERE title='starline-online') and objects.ID=pvalues.OBJECT_ID     )a    )b       group by  titlename,descr");

//  $res=SQLSelect("SELECT `TITLE`,`DESCRIPTION` FROM `objects` WHERE `CLASS_ID` in(SELECT ID  FROM `classes` WHERE title='starline-online')");
  
	
	if ($res[0]['TITLE']) {
   //paging($res, 100, $out); // search result paging
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required
    $tmp=explode(' ', $res[$i]['UPDATED']);
    $res[$i]['UPDATED']=fromDBDate($tmp[0])." ".$tmp[1];
   }
   $out['RESULT']=$res;
  }
