<?php
/**
* https://starline-online.ru/
* author Sannikov Dmitriy sannikovdi@yandex.ru
* support page 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 09:04:00 [Apr 04, 2016])

*/
//
//
ini_set ('display_errors', 'off');
class starline extends module {
/**
*
* Module class constructor
*
* @access private
*/
function starline() {
  $this->name="starline";
  $this->title="starline-online.ru";
  $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
 function edit_classes(&$out, $id) {
  require(DIR_MODULES.$this->name.'/classes_edit.inc.php');
 }

function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }

	if (IsSet($this->dev)) {
  $p["dev"]=$this->dev;
 }
	
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}



/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  global $dev;	
global $upd_PROPERTY_NAME;		
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
	
if (isset($dev)) {
   $this->dev=$dev;
  }
	
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
$out['dev']=$this->dev;
    $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 $this->getConfig();

//        if ((time() - gg('cycle_livegpstracksRun')) < $this->config['TLG_TIMEOUT']*2 ) {
        if ((time() - gg('cycle_starlineRun')) < 360*30 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}

 
// $out['STARLINELOGIN'] = $this->config['STARLINELOGIN'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINELOGIN'");
$out['STARLINELOGIN']=$cmd_rec['VALUE'];
	
// $out['STARLINEPWD']=$this->config['STARLINEPWD'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINEPWD'");
$out['STARLINEPWD']=$cmd_rec['VALUE'];	
	
/// $out['STARLINETOKEN']=$this->config['STARLINETOKEN'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINETOKEN'");
$out['STARLINETOKEN']=$cmd_rec['VALUE'];	


//if (strlen($cmd_rec['VALUE']==0)) 
if (!$cmd_rec['VALUE']) 

{$out['NOTOKEN']="1";} else 
{$out['NOTOKEN']="0";}


	
 //$out['STARLINESESID']=$this->config['STARLINESESID'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINESESID'");
$out['STARLINESESID']=$cmd_rec['VALUE'];	

// $out['STARLINECOOKIES']=$this->config['STARLINECOOKIES'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINECOOKIES'");
$out['STARLINECOOKIES']=$cmd_rec['VALUE'];	
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='USERAGENTID'");
$out['USERAGENTID']=$cmd_rec['VALUE'];		



//$out['DEV']=$this->config['DEV'];	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='DEV'");
$out['DEV']=$cmd_rec['VALUE'];
	
	

//$out['STARLINEDEBUG']=$this->config['STARLINEDEBUG'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINEDEBUG'");
$out['STARLINEDEBUG']=$cmd_rec['VALUE'];

if (strpos($cmd_rec['VALUE'], 'Captcha needed')>10) 

{$out['NEEDCAPTCHA']="1";} else 
{$out['NEEDCAPTCHA']="0";}

	
	
//$out['EVERY']=$this->config['EVERY'];
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='EVERY'");
$out['EVERY']=$cmd_rec['VALUE'];
	

	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='MSG_LEVEL'");
$out['MSG_LEVEL']=$cmd_rec['VALUE'];
	
 
 if (!$out['UUID']) {
	 $out['UUID'] = md5(microtime() . rand(0, 9999));
	 $this->config['UUID'] = $out['UUID'];
	 $this->saveConfig();
 }
 
 if ($this->view_mode=='update_settings') {
	global $starlinelogin;
//	$this->config['STARLINELOGIN']=$starlinelogin;	 
SQLexec("update starline_config set value='$starlinelogin' where parametr='STARLINELOGIN'");		 	 	 
	 

	global $starlinepwd;
//$this->config['STARLINEPWD']=$starlinepwd;
SQLexec("update starline_config set value='$starlinepwd' where parametr='STARLINEPWD'");		 	 	 	 

	global $starlinetoken;
//	$this->config['STARLINETOKEN']=$starlinetoken;	 
SQLexec("update starline_config set value='$starlinetoken' where parametr='STARLINETOKEN'");	 
	 
global $every;
SQLexec("update starline_config set value='$every' where parametr='EVERY'");		 

global $starlinesesid;
//$this->config['STARLINESESID']=$starlinesesid;	 
SQLexec("update starline_config set value='$starlinesesid' where parametr='starlinesesid'");		 	 


	global $starlinecookies;
//	$this->config['STARLINECOOKIES']=$starlinecookies;	 
SQLexec("update starline_config set value='$starlinecookies' where parametr='STARLINECOOKIES'");		 	 	 

	global $starlinedebug;
//$this->config['STARLINEDEBUG']=$starlinedebug;	 
SQLexec("update starline_config set value='$starlinedebug' where parametr='STARLINEDEBUG'");		 	 	 	 

 	global $dev;
//	$this->config['dev']=$dev;	 
SQLexec("update starline_config set value='$dev' where parametr='dev'");		 	 	 	 	 


   
   $this->saveConfig();
//   $this->redirect("?");
   $this->redirect("?tab=settings");
 }
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 
// if ($this->tab=='' || $this->tab=='outdata') {
//   $this->outdata_search($out);
// }  

 if ($this->tab=='' || $this->tab=='indata') {
    $this->indata_search($out); 
 }
 if ($this->view_mode=='login') {
setGlobal('cycle_starlineControl','start');  	 
		$this->login();
$this->redirect("?tab=settings");
 }

 if ($this->view_mode=='get') {
setGlobal('cycle_starlineControl','start');  
		$this->getdatefnc();
 }

 if ($this->view_mode=='startign') {
$this->startign2($this->dev);
 }
	
 if ($this->view_mode=='stopign') {
$this->stopign2($this->dev);

 }
	
 if ($this->view_mode=='alarmengine') {
$this->alarmengine();
 }	
	
 if ($this->view_mode=='nowstate') {
$this->saystate();
 }		
	
if ($this->view_mode=='alarmstate') {
$this->alarmstate();
 }		
	
   if ($this->view_mode=='upd_PROPERTY_NAME')
        {
            $this->upd_PROPERTY_NAME();
        }	
	
	
	
	

}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
 
 function indata_search(&$out) {	 
  require(DIR_MODULES.$this->name.'/indata.inc.php');
 }

 function processCycle() {

            debmes('run processcycle ','starline');
   $this->getConfig();

   //$every=$this->config['EVERY'];

$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='EVERY'");
$every=$cmd_rec['VALUE'];

//$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='LATEST_UPDATE'");
//$tdev=$cmd_rec['VALUE'];


   $tdev = time()-$this->config['LATEST_UPDATE'];
   $has = $tdev>$every*60;

debmes('every: '.$every.' tdev: '.$tdev. '  has: '.$has,'starline');
   if ($tdev < 0) {
		$has = true;
            debmes('has=true!!! ','starline');
   }
   
   if ($has) {  
$this->getdatefnc();   
		 
	$this->config['LATEST_UPDATE']=time();
	$this->saveConfig();
   } 
  }

 function sendData() {

 }
 
 

function upd_PROPERTY_NAME() {	
	
$sqlQuery = "SELECT pvalues.*, objects.TITLE as OBJECT_TITLE, properties.TITLE as PROPERTY_TITLE
               FROM pvalues
               JOIN objects ON pvalues.OBJECT_ID = objects.id
               JOIN properties ON pvalues.PROPERTY_ID = properties.id
              WHERE pvalues.PROPERTY_NAME != CONCAT_WS('.', objects.TITLE, properties.TITLE)";
$data = SQLSelect($sqlQuery);
$total = count($data);
for ($i = 0; $i < $total; $i++)
{
   $objectProperty = $data[$i]['OBJECT_TITLE'] . "." . $data[$i]['PROPERTY_TITLE'];
   if ($data[$i]['PROPERTY_NAME'])
      echo "Incorrect: " . $data[$i]['PROPERTY_NAME'] . " should be $objectProperty" . PHP_EOL;
   else
      echo "Missing: " . $objectProperty . PHP_EOL;
   $sqlQuery = "SELECT *
                  FROM pvalues
                 WHERE ID = '" . $data[$i]['ID'] . "'";
   $rec = SQLSelectOne($sqlQuery);
   $rec['PROPERTY_NAME'] = $data[$i]['OBJECT_TITLE'] . "." . $data[$i]['PROPERTY_TITLE'];
   SQLUpdate('pvalues', $rec);
}
}	
	
function login() {
$cookie_file = ROOT . 'cached/starline_cookie.txt'; 
$this->getConfig();
//sg('test.starline','login:'.$this->config['STARLINELOGIN']);
//sg('test.starline','login:'.$this->config['STARLINEPWD']);
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINELOGIN'");
$login=$cmd_rec['VALUE'];

$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINEPWD'");
$pwd=$cmd_rec['VALUE'];
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINECOOKIES'");
$STARLINECOOKIES=$cmd_rec['VALUE'];	

	
	
$url = 'https://starline-online.ru/user/login';
$fields = array(
'LoginForm[login]' =>$login, 
'LoginForm[rememberMe]' => 'on', 
'LoginForm[pass]' => $pwd,
'captcha[code]'=>'',
'captcha[sid]'=>''
);



$fields_string = '';
foreach ($fields as $key => $value) {    $fields_string .= urlencode($key) . '=' . urlencode($value) . '&';}
rtrim($fields_string, '&');
//$this->config['STARLINEDEBUG']=$fields_string;
//SQLexec("update starline_config set value='$fields_string' where parametr='STARLINEDEBUG'");		
//sg('test.starline','login:'.$fields_string);

//sg('test.starline',$this->config['COOKIES']);
//$cdata=$this->config['STARLINECOOKIES'];
$cdata=$STARLINECOOKIES;	
	 
$ch = curl_init();
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//curl_setopt($ch, CURLOPT_POST, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0',
'Accept: application/json, text/javascript, */*; q=0.01',
'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
'X-Requested-With: XMLHttpRequest'

//'User-Agent\': \'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
//'Accept\': \'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//'Accept-Language\': \'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
//'Connection\': \'keep-alive'


//'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
//'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
//'Connection: keep-alive'

));


$result = curl_exec($ch);

$result = urldecode($result);

$info = curl_getinfo($ch);
//$this->config['STARLINEDEBUG']=$result;
SQLexec("update starline_config set value='$result' where parametr='STARLINEDEBUG'");	
	
	

//sg('test.starline','ch:'.$ch);
//sg('test.starline','result:'.$result);


//sg('test.starline','reqestheader:'.json_encode($info));
//sg('test.starline','reqestheade_ifo:'.$info['request_header']);

//$headers=array();

debmes('result:'.$result,'starline');
$data=explode("\n",$result);
//$headers['status']=$data[0];

//array_shift($data);

debmes('data:'.$data,'starline');


foreach($data as $part){
$par=substr ($part,0,10);

//debmes('strpos(part,PHPSESSID):'.strpos($part,'PHPSESSID'),'starline');

//sg('test.starline','part:'.$part);
if (strpos($part,'PHPSESSID')>0) {
debmes('PART:'.$part,'starline');

$sesid=explode('=',  $part);
$sesid2=explode(';',  $sesid[1]);

debmes('sesid2:'.$sesid2,'starline');
//sg('test.starline_PHPSESSID',$sesid2[0]);
//$this->config['STARLINESESID']=$sesid2[0];
SQLexec("update starline_config set value='$sesid2[0]' where parametr='STARLINESESID'");		 	 	 		
}

//if (strpos($part,': t=')>0) {
if (strpos($part,'token')>0) {
//$token=explode('=',  $part);



debmes('this token:'.$part,'starline');
//$match=';\w{0,}:\d{0,2}:"(.+?)";}';
//$match='s:32:"(.+?)";}';


$re = '/token\S{2}\w{0,}\S\d{0,}\S{2}(.+?)\S{2}}/';


preg_match($re, $part, $matches, PREG_OFFSET_CAPTURE, 0);


print_r($matches);
$token=$matches[1][0];

debmes('matches:'.$matches,'starline');

debmes('token:'.$token,'starline');
SQLexec("update starline_config set value='$token' where parametr='STARLINETOKEN'");		 	 	 	
}

if (strpos($part,'starline.ru')>0) {
//$token=explode('=',  $part);
//$token2=explode(';',  $token[1]);
//sg('test.starline_cookies',$part);
//$this->config['STARLINECOOKIES']=$part;



debmes('STARLINECOOKIES: '. urlencode($part),'starline');

SQLexec("update starline_config set value='".urlencode($part)."' where parametr='STARLINECOOKIES'");		 	 	 		
	
}


if (strpos($part,'userAgentId')>0) {

$re = '/userAgentId=(.+?);/m';

preg_match($re, $part, $matches, PREG_OFFSET_CAPTURE, 0);


//print_r($matches);

$useragentid=$matches[1][0];


debmes('userAgentpart: '. $part,'starline');

debmes( $matches,'starline');

debmes('userAgentId: '. $useragentid,'starline');

SQLexec("update starline_config set value='".$useragentid."' where parametr='USERAGENTID'");		 	 	 		
	
}



//sg('test.starline','part:'.$part);
if (strpos($part,'Captcha')>0) {
//sg('test.starline_Captcha',$part);
}else 
{
//sg('test.starline_Captcha','no need');
}

if (strpos($part,'Cookies')>0) {
//sg('test.starline_Cookies',$part);
}
}


//sg('test.starline',$matches);
//sg('test.starline',$cookies);
curl_close($ch);
$this->saveConfig();
 }








///////////////////////////////////

function  getdatefnc(){
$this->getConfig();

//$cdata=$this->config['STARLINECOOKIES'];
//$token=gg('starlinecfg.token');
//$sesid=gg('test.starline_PHPSESSID');
//$token=$this->config['STARLINETOKEN'];
//$sesid=$this->config['STARLINESESID'];
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINELOGIN'");
$login=$cmd_rec['VALUE'];

$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINETOKEN'");
$token=$cmd_rec['VALUE'];


	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINESESID'");
$sesid=$cmd_rec['VALUE'];	

$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINECOOKIES'");
$cookies=$cmd_rec['VALUE'];	



$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='USERAGENTID'");
$useragentid=$cmd_rec['VALUE'];	


$ck='cookie:_ym_uid=1550205745797125950;_ym_d=1550205745;__utma=219212379.1511068578.1550205745.1550205745.1550205745.1;__utmc=219212379;__utmz=219212379.1550205745.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);__utmt=1;__utmb=219212379.1.10.1550205745;_ym_isad=2;_ym_visorc_20868619=w;PHPSESSID='.$sesid.';738a10a47cba25be2f5ef6b525fea42a=20edac0fed905f5972513c846e74d543fb0de142a:4:{i:0;s:6:"183613";i:1;s:11:"'.$login.'";i:2;i:2592000;i:3;a:13:{s:8:"slid_uid";s:6:"182877";s:5:"login";s:11:"'.$login.'";s:10:"first_name";s:14:"";s:9:"last_name";s:16:"";s:11:"middle_name";s:0:"";s:12:"company_name";s:0:"";s:3:"sex";s:1:"M";s:4:"lang";s:2:"ru";s:3:"gmt";s:2:"+5";s:6:"avatar";a:2:{s:8:"standard";s:46:"https://id.starline.ru/avatar/default.standart";s:9:"thumbnail";s:47:"https://id.starline.ru/avatar/default.thumbnail";}s:8:"contacts";s:15:"auth_contact_id";N;s:5:"roles";a:1:{i:0;s:4:"user";}}};userAgentId='.$useragentid.';lang=ru';

$cookies=urldecode($ck);
	
	
//
//echo $token.":".$sesid;

//eS = date / 1000;
//	eS = eS.toString().replace(".","");
//	path: '/device?tz=360&_='+eS, //list

$url = 'https://starline-online.ru/device?tz=300&_=1512134458324'; 

$charray=
array(
':authority:starline-online.ru',
':method:GET',
':path:/device?tz=300&_=1513105401911',
':scheme:https',
'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'accept-encoding:gzip, deflate, br',
'accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'Referer: https://starline-online.ru/site/map',
'Cookie:'.$cookies,
//'Cookie: PHPSESSID='.$sesid.'; t='.$token.'; lang=ru;',
'upgrade-insecure-requests:1',
'user-agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Mobile Safari/537.36',
'Connection: keep-alive'
);

debmes($charray, 'starline');


//$url = 'https://starline-online.ru/device?tz=360&_='.eS; 
   $ch = curl_init();   
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, $charray);




$result = curl_exec($ch);
//$this->config['STARLINEDEBUG']=$result;
//sg('test.starline2','all:'.$result);

debmes('result:'.$result, 'starline');

$data=explode("\n",$result);
//$headers['status']=$data[0];

//array_shift($data);

foreach($data as $part){
$par=substr ($part,0,10);

//sg('test.starline2','part:'.$part);
if (strpos($part,'PHPSESSID')>0) {
$sesid=explode('=',  $part);
$sesid2=explode(';',  $sesid[1]);
//sg('test.starline2_PHPSESSID',$sesid2[0]);
}
/*
if (strpos($part,'t=')>0) {
//sg('test.starline2_token',$part);
}



//sg('test.starline2','part:'.$part);
if (strpos($part,'Captcha')>0) {
//sg('test.starline2_Captcha',$part);
}else 
{
//sg('test.starline2_Captcha','no need');
}

if (strpos($part,'Cookies')>0) {
//sg('test.starline2_Cookies',$part);
}
*/
}

   curl_close($ch);

//sg('test.starline',$result);
//SaveFile(ROOT . 'cached/dialog_result.txt', $result); // сохранять в файл не обязательно, это я делаю просто для того чтобы посмотреть что внутри

//@unlink($cookie_file);


$data=json_decode($result,true);

$names=$data['answer']['devices'];

foreach ($names as $key=> $value ) {


 foreach ($value as $key2=> $value2 ) {   
  if ($key2=='alias' )  {

   //$devicename=str_replace(" ","_",$value2);
	  
$devicename=str_replace(' ', '_',$value2);   
//sg('test.starline',$devicename);
//   if (gg($devicename."."."alias")=$devicename) {
//}else {
 
   addClassObject('starline-online',$devicename);
//}
   }
  if (is_array($value2))
  {
foreach ($value2 as $key3=> $value3 ) { 
sg($devicename.'.'.$key3,$value3);  
///                                       
  if (is_array($value3))
  {
foreach ($value3 as $key4=> $value4 ) { 
sg($devicename.'.'.$key4,$value4);  
}}
                                       
///                                       
                                       
}
    
   
  } else {
sg($devicename.'.'.$key2,$value2);
sg($devicename.'.updated',date('d.m.Y H:i:s'));
sg($devicename.'.json',$result);

   
  }
}
$url = BASE_URL . '/gps.php?latitude=' . gg($devicename.'.y')
        . '&longitude=' . gg($devicename.'.x')
        . '&altitude=' . gg($devicename.'.altitude')
        . '&accuracy=' . gg($devicename.'.gpsaccuracy') 
        . '&provider=' . gg($devicename.'.gsm_lvl') 
        . '&speed='  .gg($devicename.'.speed') 
        . '&battlevel=' . gg($devicename.'.battery') 
        . '&charging=' . gg($devicename.'.charging') 
        . '&deviceid=' .gg($devicename.'.imei')  ;

getURL($url, 0);   
     
		
	 
	 
//end main function 
}
//$this->saveConfig();

      $this->upd_PROPERTY_NAME();

}
	
 
 function alarmengine() {
$objn='AlarmClock'.AlarmIndex22();	 
addClassObject('AlarmClock',$objn);	 
sg($objn.'.days','1111111');
sg($objn.'.once','0');	 
sg($objn.'.method','code');	 	 
sg($objn.'.AlarmTime','07:00');	 	 
sg($objn.'.AlarmOn','1');	 	 
sg($objn.'.code','include_once(DIR_MODULES . "starline/starline.class.php"); $sl = new $starline(); $sl->startign2(); ');	 	 	 
sg($objn.'.linked_method','');	 	 	 	 
SQLUpdate('objects', array("ID"=>get_id22($objn), "DESCRIPTION"=>"starline_startengine"));   	 
} 
 function alarmstate() {
$objn='AlarmClock'.AlarmIndex22();	 
addClassObject('AlarmClock',$objn);	 
sg($objn.'.days','1111111');
sg($objn.'.once','0');	 
sg($objn.'.method','code');	 	 
sg($objn.'.AlarmTime','21:00');	 	 
sg($objn.'.AlarmOn','1');	 	 
sg($objn.'.code','include_once(DIR_MODULES . "starline/starline.class.php"); $sl = new $starline(); $sl->saystate(); ');	 	 	 	 
sg($objn.'.linked_method','');	 	 	 	 
SQLUpdate('objects', array("ID"=>get_id22($objn), "DESCRIPTION"=>"starline_state"));   	 
} 	
   
	
function startign2($dev)
{
$this->getConfig();

//$token=$this->config['STARLINETOKEN'];
//$sesid=$this->config['STARLINESESID'];	

$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINETOKEN'");
$token=$cmd_rec['VALUE'];
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINESESID'");
$sesid=$cmd_rec['VALUE'];	

	
//
//eS = date / 1000;
//	eS = eS.toString().replace(".","");
//	path: '/device?tz=360&_='+eS, //list

//$url = 'https://starline-online.ru/device?tz=300&_=1512134458324'; 
//$url = 'https://starline-online.ru/device?tz=360&_='.eS; 

$url = 'https://starline-online.ru/device/'.$dev.'/executeCommand';  
//echo $url;
$fields = array(
    'value' => '1', 
    'action' => 'ign', 
 'password' =>  ''
 );
$fields_string = '';
foreach ($fields as $key => $value) {
    $fields_string .= urlencode($key) . '=' . urlencode($value) . '&';
}


   $ch = curl_init();   
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
':authority:starline-online.ru',
':method:GET',
':path:/device?tz=300&_=1513105401911',
':scheme:https',
'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'accept-encoding:gzip, deflate, br',
'accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'Referer: https://starline-online.ru/site/map',
'Cookie: PHPSESSID='.$sesid.'; t='.$token.'; lang=ru;',
'upgrade-insecure-requests:1',
'user-agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Mobile Safari/537.36',
'Connection: keep-alive'
));

   $result = curl_exec($ch);

//sg('test.starline_ign',''.$result);
   curl_close($ch);


}
	
	
function stopign2($dev)
{
$this->getConfig();

//$token=$this->config['STARLINETOKEN'];
//$sesid=$this->config['STARLINESESID'];	
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINETOKEN'");
$token=$cmd_rec['VALUE'];
	
$cmd_rec = SQLSelectOne("SELECT VALUE FROM starline_config where parametr='STARLINESESID'");
$sesid=$cmd_rec['VALUE'];	
	

//
//eS = date / 1000;
//	eS = eS.toString().replace(".","");
//	path: '/device?tz=360&_='+eS, //list

//$url = 'https://starline-online.ru/device?tz=300&_=1512134458324'; 
//$url = 'https://starline-online.ru/device?tz=360&_='.eS; 

$url = 'https://starline-online.ru/device/'.$dev.'/executeCommand';  
//echo $url;
$fields = array(
    'value' => '0', 
    'action' => 'ign', 
 'password' =>  ''
 );
$fields_string = '';
foreach ($fields as $key => $value) {
    $fields_string .= urlencode($key) . '=' . urlencode($value) . '&';
}


   $ch = curl_init();   
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
':authority:starline-online.ru',
':method:GET',
':path:/device?tz=300&_=1513105401911',
':scheme:https',
'accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'accept-encoding:gzip, deflate, br',
'accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
'Referer: https://starline-online.ru/site/map',
'Cookie: PHPSESSID='.$sesid.'; t='.$token.'; lang=ru;',
'upgrade-insecure-requests:1',
'user-agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Mobile Safari/537.36',
'Connection: keep-alive'
));

   $result = curl_exec($ch);

//sg('test.starline_ign',''.$result);
   curl_close($ch);


}
	
 
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
SQLExec('DROP TABLE IF EXISTS starline_config');	 
SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id = (select id from classes where title = 'starline-online')))");
SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'starline-online'))");
SQLExec("delete from objects where class_id = (select id from classes where title = 'starline-online')");
SQLExec("delete from methods where class_id = (select id from classes where title = 'starline-online')");	 
SQLExec("delete from classes where title = 'starline-online'");	 
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data) {
$classname='starline-online';
addClass($classname); 
addClassMethod($classname,'OnChange','SQLUpdate("objects", array("ID"=>$this->id, "DESCRIPTION"=>gg("sysdate")." ".gg("timenow"))); ');

$prop_id=addClassProperty($classname, 'arm', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='на охране'; //   <-----------
SQLUpdate('properties',$property); }


$prop_id=addClassProperty($classname, 'battery', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Уровень заряда АКБ'; //   <-----------
SQLUpdate('properties',$property); } 

$prop_id=addClassProperty($classname, 'ctemp', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Температура в салоне'; //   <-----------
SQLUpdate('properties',$property); } 

$prop_id=addClassProperty($classname, 'etemp', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Температура двигателя'; //   <-----------
SQLUpdate('properties',$property); } 

$prop_id=addClassProperty($classname, 'gsm_lvl', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Уровень сигнала GSM'; //   <-----------
SQLUpdate('properties',$property); } 

$prop_id=addClassProperty($classname, 'ign', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Двигатель заведен'; //   <-----------
SQLUpdate('properties',$property); } 


$prop_id=addClassProperty($classname, 'value', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='Остаток средств на счете'; //   <-----------
SQLUpdate('properties',$property); } 


$prop_id=addClassProperty($classname, 'y', 10);
if ($prop_id) {$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['DESCRIPTION']='GPS координаты'; //   <-----------
SQLUpdate('properties',$property); } 



$prop_id=addClassProperty($classname, 'x', 10);
if ($prop_id) {
$property=SQLSelectOne("SELECT * FROM properties WHERE ID=".$prop_id);
$property['ONCHANGE']='OnChange'; //   <-----------
$property['DESCRIPTION']='GPS координаты'; //   <-----------
SQLUpdate('properties',$property);} 

setGlobal('cycle_starlineAutoRestart','1');	 	 
	 
  $data = <<<EOD
 starline_config: ID int(10) unsigned NOT NULL auto_increment
 starline_config: parametr varchar(10000)
 starline_config: value varchar(10000)  
EOD;
   parent::dbInstall($data);	 

$par=SQLSElectOne("select * from  starline_config where parametr='EVERY'");
	 
$par['parametr'] = 'EVERY';
$par['value'] = 30;		 

if (!$par['ID']) SQLInsert('starline_config', $par);

$par=SQLSElectOne("select * from  starline_config where parametr='MSG_LEVEL'");

$par['parametr'] = 'MSG_LEVEL';
$par['value'] = "2";		 
if (!$par['ID']) SQLInsert('starline_config', $par);
	 
$par=SQLSElectOne("select * from  starline_config where parametr='LASTCONDITION'");

$par['parametr'] = 'LASTCONDITION';
$par['value'] = "0";		 
if (!$par['ID']) SQLInsert('starline_config', $par);
	 

$par=SQLSElectOne("select * from  starline_config where parametr='STARLINELOGIN'");
$par['parametr'] = 'STARLINELOGIN';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);


$par=SQLSElectOne("select * from  starline_config where parametr='STARLINEPWD'");
$par['parametr'] = 'STARLINEPWD';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);

$par=SQLSElectOne("select * from  starline_config where parametr='STARLINETOKEN'");	 
$par['parametr'] = 'STARLINETOKEN';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);


$par=SQLSElectOne("select * from  starline_config where parametr='STARLINESESID'");	 
$par['parametr'] = 'STARLINESESID';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);
	 

$par=SQLSElectOne("select * from  starline_config where parametr='DEV'");
$par['parametr'] = 'DEV';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);
	 
$par=SQLSElectOne("select * from  starline_config where parametr='UUID'");
$par['parametr'] = 'UUID';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);

$par=SQLSElectOne("select * from  starline_config where parametr='STARLINEDEBUG'");
$par['parametr'] = 'STARLINEDEBUG';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);


$par=SQLSElectOne("select * from  starline_config where parametr='STARLINECOOKIES'");
$par['parametr'] = 'STARLINECOOKIES';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);

$par=SQLSElectOne("select * from  starline_config where parametr='USERAGENTID'");
$par['parametr'] = 'USERAGENTID';
$par['value'] = "";		 
if (!$par['ID']) SQLInsert('starline_config', $par);




	 
	 
 }
// --------------------------------------------------------------------

//////
function getaddrfromcoord($x,$y)
{
$url='http://maps.googleapis.com/maps/api/geocode/xml?latlng='.$x.',' .$y.'&sensor=false&language=ru'; 
  $fields = array(
   	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3',
	'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',	'Connection: keep-alive',	'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36'     );
foreach($fields as $key=>$value)
{ $fields_string .= $key.'='.urlencode($value).'&'; }
rtrim($fields_string, '&');
   $ch = curl_init();   
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_POST, count($fields));   
   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
   $result = curl_exec($ch);
 curl_close($ch);
$xml = simplexml_load_string($result);
$otvet=$xml->result->formatted_address; 
$spl=explode(',',$otvet) ;
return $spl[0] ;
//return $url;
} 



function saystate(){

$objects = getObjectsByClass("starline-online");
foreach ($objects as $obj) {
    $ar2[] = $obj['TITLE'];

//$objn="kia ceed";
$objn=$obj['TITLE'];

	
$lu=gg($objn.".updated");
$luts=gg($objn."..timestamp");
$diff=(gmdate('i',trim(time()-$luts)));

$pattern = "|\b[0]+([1-9][\d]*)|is"; 
$diff2= preg_replace($pattern, "\\1", $diff); 

//$status .= "Информация об автомобиле была обновлена  " .$lu." ". $diff . " минут назад.";
$status = "Информация об автомобиле ".$objn." была обновлена  "  .$diff2 . " минут назад.";
//echo gg('kia ceed.ign');

if (gg($objn.'.ign')=='1') {$status =$status." Двигатель запущен, "; }
else   {$status=$status." Двигатель остановлен,";}


if (gg($objn.".arm")==1)  {$status =$status." охрана включена, "; }
else {$status =$status." охрана выключена,";}


$status .= " температура двигателя ".round(gg($objn.".etemp"))." градусов, температура в салоне  ".round(gg($objn.".ctemp"))." градусов.";

$status .= " Напряжение аккумуляторной батареи ".gg($objn.".battery")." вольт. ";
if (gg($objn.".battery")<12.4) {$status = $status." Внимание, аккумулятор сильно разряжен, рекомендуется зарядить как можно скорее!";}


$status .= " Баланс сим карты  ".round(gg($objn.".value"))." рублей. ";
if (gg($objn.".value")<50) {$status = $status." Не забудьте пополнить баланс телефона.";}
if (gg($objn.".short_address")<>""){
$status .= " По данным системы мониторинга автомобиль находится на ".  gg($objn.".short_address");}

say($status,2);
}

//say("test123",2);
	
	
}
}


/*
*
* TW9kdWxlIGNyZWF0ZWQgQXByIDA0LCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/

 function AlarmIndex22() {
	 
    $objects=getObjectsByClass('AlarmClock');
    $index=0;
    $total = count($objects);
    for ($i = 0; $i < $total; $i++) {
        if (preg_match('/(\d+)/',$objects[$i]['TITLE'],$m)) {
            $current_index=(int)$m[1];
            if ($current_index>$index) {
                $index=$current_index;
            }
        }
    }
    $index++;
    if ($index<10) {
        $index='0'.$index;
    }
    return $index;
    
}

function get_id22($prop)
{
$sql='SELECT distinct id id FROM `objects`  WHERE TITLE ="'.$prop.'"';
$rec = SQLSelect($sql); 
return $rec[0][id];
}
