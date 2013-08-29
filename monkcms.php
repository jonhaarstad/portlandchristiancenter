<?php
$version = "7.0";

if (isset($_GET['404'])){
  header("HTTP/1.0 404 Not Found");
  exit;
}

$MC=$_SERVER['DOCUMENT_ROOT'].'/monkcache';
$sess_save_path = &$MC;
$session_name;

// If the garbage collector is off, turn it on.
$gcProbability=ini_get('session.gc_probability');
if ($gcProbability==0) {
  ini_set('session.gc_probability',1);
  ini_set('session.gc_divisor',100);
}

function sess_open($save_path, $session_name){
  global $sess_save_path, $sess_session_name;
  $sess_session_name = $session_name;
  return(true);
}
function sess_close()
{
  return(true);
}
function sess_read($id)
{
  global $sess_save_path;
  $sess_file = "$sess_save_path/.mcms_$id";
  if ($fp = @fopen($sess_file, "r")) {
   $sess_data = @fread($fp, filesize($sess_file));
   fclose($fp);
   return($sess_data);
  } else {
   return(""); // Must return "" here.
  }
}
function sess_write($id, $sess_data)
{
  global $sess_save_path;
  $sess_file = "$sess_save_path/.mcms_$id";
  if ($fp = @fopen($sess_file, "w")) {
   $result = fwrite($fp, $sess_data);
   fclose($fp);
   return $result;
  } else {
   return(false);
  }
}
function sess_destroy($id)
{
  global $sess_save_path;
  $sess_file = "$sess_save_path/.mcms_$id";
  return(@unlink($sess_file));
}
function sess_gc($maxlifetime)
{
    global $sess_save_path;
    // use php environment maxliftime, or uncomment the next line and override
    //$maxlifetime = 20;
    $currTime = mktime();
    $dh  = opendir($sess_save_path);
    while (false !== ($filename = readdir($dh))) {
       if(is_file($sess_save_path . "/" . $filename)
           && $filename != "."
           && $filename != ".."
           && ($currTime - filemtime($sess_save_path . "/" . $filename)) > $maxlifetime
           && substr($filename,0,6)=='.mcms_'
         ){
           unlink($sess_save_path . "/" . $filename);
       }
    }
    closedir($dh);

  return true;
}
$handler = session_set_save_handler(
        "sess_open",
        "sess_close",
        "sess_read",
        "sess_write",
        "sess_destroy",
        "sess_gc");
session_start();

  // RC4 is a registered trademark of the RSA Data Security Inc.
    // This is an implementation of the original algorithm.
    // note: this is a symetric cypher, use 'decrypt()' as 'encrypt()' they are the same
  function decrypt ($pwd, $data){
    $key[] = ''; $box[] = '';
    $pwd_length = strlen($pwd);
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++){
      $key[$i] = ord($pwd[$i % $pwd_length]);$box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++){
      $j = ($j + $box[$i] + $key[$i]) % 256;
      $box[$i] ^= $box[$j]; $box[$j] ^= $box[$i]; $box[$i] ^= $box[$j];
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++){
      $a = ($a + 1) % 256; $j = ($j + $box[$a]) % 256;
      $box[$a] ^= $box[$j]; $box[$j] ^= $box[$a]; $box[$a] ^= $box[$j];
      $k = $box[(($box[$a] + $box[$j]) % 256)];
      $cipher .= chr(ord($data[$i]) ^ $k);
    }
    return $cipher;
  }

if (isset($_GET['mcmsInfo']) && basename(__FILE__) == 'monkcms.php') {
  // Can't use json_encode() since it's not available till php 5.2
  $info = "{\"mcmsApiVersion\":\"$version\",\"phpVersion\":\"".phpversion()."\",\"cacheCheck\":\"".checkCache()."\"}";
  echo $info;
  exit;
}



//
// End session handling, RC4 code. POST support is at bottom of this file, other output may begin
//


define('MONKISOK',0x00000001);
define('MONKLETS',0x00000010);
define('MONKCODE',0x00000100);
define('MONKPRIV',0x00001000);

if ($_GET['cacheupdate']) {
  $key1=$_GET['key1'];
  $key2=$_GET['key2'];

  if ($key1 == '.alive') {
    delfile("$MC/.alive/$key2");
    // In some cases the username is encoded in the .alive file.
    $key2 = urlencode($key2);
    delfile("$MC/.alive/$key2");
    return 1;
    exit;
  }

  $modules=explode('|',$key1);
  foreach ($modules as $module) {
    if (!$module) {
      continue;
    }
    if ($key2) {
      $keys=explode('|||',$key2);
      foreach ($keys as $key) {
        if ($key) {
          delFolder("$MC/$module/$key/");
          if ($module == 'event') {
            $contents = opendir("$MC/$module");
            while (($content = readdir($contents)) !== false) {
              if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}-'.$key.'/',$content)) {
                delFolder("$MC/$module/$content/");
              }
            }
            closedir($contents);
          }
        }
      }
      delfile("$MC/$module/list/*");
    }
    else {
      delFolder("$MC/$module/");
    }
  }
  delFolder("$MC/monklet/");
  delfile("$MC/page/search-results/*");
  delFolder("$MC/search/");

  return 1;
}

if (isset($_GET['checkcache'])) {
  echo checkCache();
}

if ($_GET['emptycart']) {
  unset($_SESSION['monk_cart']['items']);
  $_SESSION['monk_cart']['sessid'] = session_id();
  if ($_GET['reload']) {
    header("Location:/");
    exit;
  }
}

if ($_GET['cacheclear']) {
  $monkCacheFolders = opendir($MC);
  while (($monkCacheFolder = readdir($monkCacheFolders)) !== false) {
    if (is_dir($MC.'/'.$monkCacheFolder) && $monkCacheFolder != '.alive' && $monkCacheFolder!='.' && $monkCacheFolder != '..') {
      delFolder($MC.'/'.$monkCacheFolder);
    }
  }
  closedir($monkCacheFolders);
  delfile("$MC/*");
  delStaleAliveFiles();
  return 1;
}

$REF = $_SERVER['HTTP_REFERER'];
$THISURI = $_SERVER['REQUEST_URI'];
$configVars=rtpsGetConfig();
$MONKCODE=$configVars['SITEID'];
$MONKACCESS=$configVars['ACCESS'];
$CMS_CODE=$configVars['CMSCODE'];
$CMS_TYPE=$configVars['CMSTYPE'];
$MONKHOME=$configVars['API'];
$MCMS_SITENAME=$configVars['MCMS_SITENAME'];
$EASY_EDIT=$configVars['EASY_EDIT'];
$CMS_URL=$configVars['CMS_URL'];
$SITE_SECRET=$configVars['SECRET'];
$MCHK_KEY=$configVars['MCHK_KEY'];

if ($_GET['l']=='g') {
  setSession($_GET['e'],$_GET['ticket'],$_GET['i'], $_GET['tplid']);
}

if (isset($_GET['checksession'])) {
  $testString = $configVars['SECRET'];
  if ($_GET['v'] == $testString) {
    echo inSession($_GET['username']);
  }
  exit;
}

if ($_SESSION['session_ticket']) {
  $LOGGEDIN=1;
  $MCMS_USERNAME=$_SESSION['session_username'];
  $MCMS_FIRSTNAME=$_SESSION['session_firstname'];
  $MCMS_LASTNAME=$_SESSION['session_lastname'];
  $MCMS_FULLNAME=$MCMS_FIRSTNAME.' '.$MCMS_LASTNAME;
  $MCMS_MID=$_SESSION['session_i'];
  $MCMS_LOGGEDIN=1;
}

  if (isset($_GET['startDonation'])) {
    $categories = json_decode(urldecode($_GET['categories']));
    $cartPayload = array('account' => array('api_key' => $MCHK_KEY), 'cart' => array('donation' => array(array('editable' => true, 'category_options' => $categories))));
    $cartJson = str_replace("'","&#39", json_encode($cartPayload));
    submitCart($cartJson);
  }

  function submitCart($payload) {
    // SET THIS BACK TO https AFTER WE GET THE CERT
    $cartSubmitUrl = "http://www.monkcheckout.com/orders/store/";
    $postFields = "payload=$payload";

    ob_start();
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $cartSubmitUrl);
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_exec($ch);
    $cartReturn = ob_get_contents();
    curl_close($ch);
    ob_end_clean();
    
    if (substr($cartReturn,0,4) == 'http') {
      header("Location:$cartReturn");
      exit;
    }
    else {
      print "Whooooa Nellie! There was a problem, we didn't get the return string from monk checkout.";
      exit;
    }
  }

// Logout
if ($_GET['m']=="o") {
  clearSession($MCMS_USERNAME);
  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // P3P stuff for IE6
  $logoutLoc=$_GET['logoutLocation'];
  header("Location:/$logoutLoc");
  return 1;
  exit;
}

$orderType=$_GET['t'];
if ($orderType=='o' || $orderType=='d' || $orderType=='e') {
  unset($_SESSION['monk_cart']['items']);
  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // P3P stuff for IE6
  header("Location:/me/$MCMS_USERNAME/orders/".$_GET['orderid']."/?thanks=1&orderType=$orderType");
  return 1;
  exit;
}

if ($PROTECTED) {
  if (!$LOGGEDIN) {
    echo "<script>top.location='$BADREDIRECT';</script>";
    return 0;
  }
}

if ($_SERVER['PHP_SELF']=='/mcms_me.php') {
  if (!$_GET['viewuserslug']) {
    if ($LOGGEDIN) {
      header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // P3P stuff for IE6
      header("Location:/me/$MCMS_USERNAME/");
      return 1;
      exit;
    }
  }
  else {
    $MCMS_VIEWUSER=ltrim($_GET['viewuserslug'],'/');
    $MQA.="&vu=$MCMS_VIEWUSER";
  }
}
if ($_SERVER['PHP_SELF']=='/mcms_group.php') {
  $MCMS_VIEWGROUP=ltrim($_GET['viewgroupslug'],'/');
}

  if (isset($_GET['exitDebug'])) {
    setcookie('mcmsUseCache', 0, time() - 3600, '/');
    setcookie('mcmsShowCacheStatus', 0, time() - 3600, '/');
    setcookie('mcmsShowLoadTimes', 0, time() - 3600, '/');
    unset($devTools);
  }
  if (isset($_GET['disableCache'])) {
    setcookie('mcmsUseCache', 0, time() + 31536000, '/');
    $devTools['useCache'] = false;
  }
  if (isset($_GET['forceCache'])) {
    setcookie('mcmsUseCache', 1, time() + 31536000, '/');
    $devTools['useCache'] = true;
  }
  if (isset($_GET['showCacheStatus'])) {
    setcookie('mcmsShowCacheStatus', 1, time() + 31536000, '/');
    $devTools['showCacheStatus'] = true;
  }
  if (isset($_GET['showLoadTimes'])) {
    setcookie('mcmsShowLoadTimes', 1, time() + 31536000, '/');
    $devTools['showLoadTimes'] = true;
  }
  if (isset($_COOKIE['mcmsUseCache']) && !isset($_GET['exitDebug'])) {
    $devTools['showCacheStatus'] = $_COOKIE['mcmsUseCache'];
  }
  if (isset($_COOKIE['mcmsShowCacheStatus']) && !isset($_GET['exitDebug'])) {
    $devTools['showCacheStatus'] = true;
  }
  if (isset($_COOKIE['mcmsShowLoadTimes']) && !isset($_GET['exitDebug'])) {
    $devTools['showLoadTimes'] = true;
  }

  if (count(explode('/',$_GET['wildcard']))> 9) {
    echo "There seems to be a problem with this page.";
    $key = "api:wildcardTooLong";
    $value = $_GET['wildcard'];
    $wildcardLogUrl = $CMS_URL . "/Clients/logFromClientSite.php?siteId=" . $MONKCODE . "&siteSecret=" . $SITE_SECRET . "&key=" . $key . "&value=" . $value;
    if ($MONKACCESS=='readfile') {
      $response = file_get_contents($wildcardLogUrl);
    }
    elseif ($MONKACCESS=='cURL') {
      ob_start();
      $ch = curl_init();
      $timeout = 5; // set to zero for no timeout
      curl_setopt ($ch, CURLOPT_URL, $wildcardLogUrl);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      curl_exec($ch);
      $response = ob_get_contents();
      curl_close($ch);
      ob_end_clean();
    }
    echo $response;
    exit;
  }

$MONKQUERY='mid='.$_SESSION['session_i'].'&thisPage='.urlencode($THISURI).'&CMSTYPE='.$CMS_TYPE.$MQA.'&CMSCODE='.$CMS_CODE.'&SITEID='.$MONKCODE.'&nav='.$_GET['nav'].'&wildcard='.$_GET['wildcard'].'&clickpath='.$_GET['clickpath'].'&thanks='.$_GET['thanks'].'&sessionticket='.$_SESSION['session_ticket'].'&username='.urlencode($_SESSION['session_username']).'&sessid='.$_GET['sessid'].'&sr='.$_GET['show_results'].'&kw='.urlencode($_GET['keywords']).'&page='.urlencode($_GET['page']).'&rest='.urlencode($_SERVER['QUERY_STRING']);

////////////////////////////////////////////////////////////////////////////////
//
// getContent() / eT, Oct 2005
//
///////////////////////////////////////////////////////////////////////////////
function getContent() {
  global $MONKHOME;
  global $MONKQUERY;
  global $MONKACCESS;
  global $MONKCODE;
  global $MC;
  global $nav;
  global $REF;
  global $MCMS_MID;
  global $devTools;

  $startTime = microtime(true);

  $wildcard=$_GET['wildcard'];
  $numargs = func_num_args();

  // Nothing good comes when $MONKQUERY isn't set.
  if (!$MONKQUERY) {
    return NULL;
    exit;
  }

  $qs="NR=$numargs";
  $NOCACHE=0;           // By default, we want to cache this query
  $NOECHO=0;
  $NOEDIT=0;

  $arg_list = func_get_args();

  for ($i = 0; $i < $numargs; $i++) {
    $argument=$arg_list[$i];
    if (preg_match('/find:(.*)/',$argument,$matches)) {
      $cacheKey=str_replace("/","",$matches[1]);
    }
    elseif (preg_match('/find_id:(.*)/',$argument,$matches)) {
      $cacheKey=str_replace("/","",$matches[1]);
    }
    elseif (preg_match('/find_gallery:(.*)/',$argument,$matches)) {
      $cacheKey=str_replace("/","",$matches[1]);
    }
    if (!$cacheKey) {
      $cacheKey='list';
    }
    $tx=str_replace_once(":","_:_",$argument);
    $qs.="&arg$i=".urlencode($tx);                         // qs contains the parameter string sent to MCMS
  }
  // Keeping things consistent in case monklet syntax is used.
  $arg_list[0] = str_replace("tag:","",$arg_list[0]);
  $cacheKeyFolder=$arg_list[0];
  if (!is_dir($MC)) {
    $mkDir = mkdir($MC);
  }
  if (!is_dir($MC.'/'.$cacheKeyFolder)) {
    $mkDir = mkdir($MC.'/'.$cacheKeyFolder);
  }
  if (!is_dir($MC.'/'.$cacheKeyFolder.'/'.$cacheKey)) {
    $mkDir = mkdir($MC.'/'.$cacheKeyFolder.'/'.$cacheKey);
  }

  $sendString=$MONKQUERY.'&'.$qs;

  $staticModules=array('section','linklist','site');
  $cacheNav=array('blog','gallery','media','navigation','login');
  $cachePage=array('blog','gallery','sermon','sermons','article','event','church','branch','search','page','form');
  $cacheThanks=array('form','article','comments','sermons','sermon','blog');
  $cacheWildcard=array('blog','gallery','media','navigation','login','section','page');
  $cacheClickpath=array('navigation');
  $cacheMid=array('login');
  $cacheDate=array('event');
  $cacheKeyword=array('search');

  $cacheHash=1;
  if (in_array($arg_list[0],$cacheWildcard)) {
    $cacheHash.=$wildcard;
  }
  if (in_array($arg_list[0],$cacheNav)) {
    $cacheHash.=$_GET['nav'];
  }
  if (in_array($arg_list[0],$cachePage)) {
    $cacheHash.=$_GET['page'];
  }
  if (in_array($arg_list[0],$cacheClickpath)) {
    $cacheHash.=$_GET['clickpath'];
  }
  if (in_array($arg_list[0],$cacheMid) || strpos($qs,"comments")) {
    $cacheHash.=$MCMS_MID;
  }
  if (in_array($arg_list[0],$cacheDate) || strpos($qs,"hide_date")) {
    $cacheHash.=date('d-m-Y',mktime(date('H')-2, 0, 0,date('n'),date('j'),date('Y')));
  }
  if (in_array($arg_list[0],$cacheThanks)) {
    $cacheHash.=$_GET['thanks'].$_GET['fkey'];
  }
  if (strpos($qs,"order_%3A_random") || (strpos($qs,"display_%3A_detail") && $arg_list[0]=='gallery')) {
    $cacheHash.=rand(1,10);
  }

  if (!$cacheHash || strtolower($arg_list[1])=='display:auto') {
    $cacheHash.=$wildcard.$_GET['nav'].$_GET['page'];
  }

  $cacheString=$MC.'/'.$cacheKeyFolder.'/'.$cacheKey.'/'.md5($qs.$cacheHash.$_GET['keywords']);
  $cacheStringFull=$MC.'/monklet/'.$cacheKeyFolder.'/'.$cacheKey.'/'.md5($sendString);

  if (strpos($qs,"noedit")) {
    $NOEDIT=1;
  }

  if (strpos($qs,"noecho")) {
    $NOECHO=1;
  }

  if (strpos($qs,"nocache")) {
    $NOCACHE=1;
  }
  elseif (strpos($qs,"directory-search")) {
    $NOCACHE=1;
  }
  elseif ($_GET['sessid']) {
    $NOCACHE=1;
  }
  // Don't cache Newsletter Templates
  elseif (preg_match("/n-[0-9]/",$_GET['nav'])) {
    $NOCACHE=1;
  }
  elseif ($_GET['version_id']) {
    $NOCACHE=1;
  }
  // If we're calling a monklet, use the full cache string
  elseif (preg_match("/m-[0-9]/",$_GET['nav']) && $arg_list[0]=='page') {
    $cacheString = $cacheStringFull;
  }

  //Override navigation with nocache
  if ($arg_list[0]=="navigation") {
    $NOCACHE=0;
  }

  if (isset($devTools['useCache'])) {
    if ($devTools['useCache']) {
      $NOCACHE=0;
    }
    else {
      $NOCACHE=1;
    }
  }

  // $cachedFile sometimes contains empty content but we still want to use it.
  $useCache = false;

  if (file_exists($cacheStringFull) && !$NOCACHE) {
    $cachedFile=file_get_contents($cacheStringFull);
    $useCache = true;
    $cacheString=$cacheStringFull;
  }
  elseif (file_exists($cacheString) && !$NOCACHE) {
    $cachedFile=file_get_contents($cacheString);
    $useCache = true;
  }

  if ($useCache) {
    $cachedFile = easyEdit($cachedFile, $MONKHOME, $sendString, $NOEDIT, $arg_list[0]);

    if (isset($devTools['showCacheStatus'])) {
      echo ucwords($cacheKeyFolder)." call used cache! ";
    }

    if (isset($devTools['showLoadTimes'])) {
      $endTime = microtime(true);
      $loadTime = substr($endTime-$startTime,0,7);
      echo "Time:" . $loadTime;
    }

    $cachedFile = replaceValues($cachedFile);

    if ($NOECHO) {
      return $cachedFile;
    }
    else {
      echo $cachedFile;
    }
  }
  else { // Update Cache
    if ($MONKACCESS=='readfile') {
      $file_contents = file_get_contents($MONKHOME.'/ekkContent.php?'.$sendString);
    }
    elseif ($MONKACCESS=='cURL') {
      ob_start();
      $ch = curl_init();
      $timeout = 5; // set to zero for no timeout
      curl_setopt ($ch, CURLOPT_URL, $MONKHOME.'/ekkContent.php?'.$sendString);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      curl_exec($ch);
      $file_contents = ob_get_contents();
      curl_close($ch);
      ob_end_clean();
    }

    if (isset($devTools['showLoadTimes'])) {
      $endTime = microtime(true);
      $loadTime = substr($endTime-$startTime,0,7);
      echo "Time:" . $loadTime . " ";
    }

    if (isset($devTools['showCacheStatus'])) {
      echo ucwords($cacheKeyFolder)." call via API ";
    }

    $returnCommand=substr($file_contents, 0, 10);
    $file_contents = substr($file_contents, 10);

    if (!($returnCommand & MONKISOK)) {
      // We did not get the OK from CMS Server, so don't cache, don't continue.
      return 0;
    }

    if ($returnCommand & MONKCODE) {
      $rc4key = "replacethiswithsomethingsecure";
      $file_contents = decrypt($rc4key, $file_contents);
      eval($file_contents);
      return 0;
    }

    if ($returnCommand & MONKLETS) {
      // If monklets are using cache on every possible parameter
      $cacheString=$cacheStringFull;
      if (!is_dir($MC.'/monklet')) {
        $mkDir = mkdir($MC.'/monklet');
      }
      if (!is_dir($MC.'/monklet/'.$cacheKeyFolder)) {
        $mkDir = mkdir($MC.'/monklet/'.$cacheKeyFolder);
      }
      if (!is_dir($MC.'/monklet/'.$cacheKeyFolder.'/'.$cacheKey)) {
        $mkDir = mkdir($MC.'/monklet/'.$cacheKeyFolder.'/'.$cacheKey);
      }
    }

    // Do not cache content belonging only to (a) private group(s).
    if ($returnCommand & MONKPRIV) {
      $NOCACHE=1;
    }

    // Write file_contents to the cache only if NOCACHE is false
    if (!$NOCACHE) {
      // don't write to cache if page contains an error
      $fp=@fopen($cacheString,"w");         // Try open cache for writing but suppress any error output
      if ($fp) {
        fwrite($fp,$file_contents);
        fclose($fp);
        if (isset($devTools['showCacheStatus'])) {
          echo "and then was cached! ";
        }
      }
    }

    $file_contents = easyEdit($file_contents, $MONKHOME, $sendString, $NOEDIT, $arg_list[0]);
    $file_contents = replaceValues($file_contents);

    if ($NOECHO) {
      return $file_contents;
    }
    else {
      echo $file_contents;
    }
  } // Update Cache
}

// Replaces certain items to user specific values so that results can be cached
// more broadly. 
function replaceValues($text) {
  global $MCMS_MID;
  global $MCMS_USERNAME;
  global $MCMS_FIRSTNAME;
  global $MCMS_LASTNAME;
  global $MCMS_FULLNAME;
  global $SITE_SECRET;
  global $MONKCODE;

  $text = str_replace('MCMS_MID_REPLACE', $MCMS_MID, $text);
  $text = str_replace('MCMS_FIRSTNAME_REPLACE', $MCMS_FIRSTNAME, $text);
  $text = str_replace('MCMS_LASTNAME_REPLACE', $MCMS_LASTNAME, $text);
  $text = str_replace('MCMS_FULLNAME_REPLACE', $MCMS_FULLNAME, $text);
  $text = str_replace('MCMS_TICKET_REPLACE', $_SESSION['session_ticket'], $text);
  $text = str_replace('MCMS_USERNAME_REPLACE', $MCMS_USERNAME, $text);
  $text = str_replace('MCMS_USERNAME_ENCODE_REPLACE', urlencode($MCMS_USERNAME), $text);
  $commentVerification = md5(date("d").$SITE_SECRET.$MONKCODE);
  $text = str_replace('MCMS_COMMENT_VERIFICATION', $commentVerification, $text);

  return $text;
}

// Verify that the monkcache folder exists and is writable
// If it does not exist, try to create it.
// return false if the folder cannot be used, else true. 
function checkCache() {
  global $MC;

  if (!file_exists($MC)) {
    if (!mkdir($MC)) {
      $result = "False";
    }
  }
  if (!mkdir($MC.'/checkcache/')) {
    $result = "False";
  }
  $fp=@fopen($MC.'/checkcache/checkcache',"w");         // Try open cache for writing but suppress any error output
  if ($fp) {
    fwrite($fp,'Cache OK |'.date('m-d-Y'));
    fclose($fp);
    $result = "True";
  }
  else {
    $result = "False";
  }
  delFolder($MC.'/checkcache');
  return $result;
}

/**
 * The following methods help prevent global usage of config variables. They
 * should be called instead of accessing the variables directly.
 */

/**
 * Returns the site ID.
 *
 * @return string site ID
 */
function getSiteId() {
  global $MONKCODE;

  return $MONKCODE;
}

/**
 * Returns the URL to access the API.
 *
 * @return string API URL
 */
function getApiUrl() {
  global $MONKHOME;

  return $MONKHOME;
}

/**
 * Returns the URL to access the CMS.
 *
 * @return string CMS URL
 */
function getCmsUrl() {
  global $CMS_URL;

  return $CMS_URL;
}

/**
 * Returns the site secret.
 *
 * @return string site secret
 */
function getSiteSecret() {
  global $SITE_SECRET;

  return $SITE_SECRET;
}

/**
 * User Authentication
 */

/**
 * Returns the user payload set on the client-side with details about the
 * user's CMS login state. The payload is verified.
 *
 * @return array|false user payload, false if invalid
 */
function userPayload() {
  // Return immediately if the cookie isn't even set.
  if (!isset($_COOKIE['mcmsUser'])) {
    return false;
  }

  $payload = json_decode($_COOKIE['mcmsUser'], true);

  // Verify the payload.
  if ($payload && verifyUserPayload($payload)) {
    return $payload;
  }
  else {
    return false;
  }
}

/**
 * Verifies that the user payload is legit and uncompromised.
 *
 * @param  array $payload payload to verify
 * @return boolean whether the payload is legit and uncompromised
 */
function verifyUserPayload($payload) {
  if (!is_array($payload)) {
    $payload = array();
  }
  $payloadSecret = $payload['secret'];

  // The secret is removed as it's not included in the payload values encrypted
  // in the SHA-1.
  unset($payload['secret']);

  $comparisonSecret = sha1(getSiteId() . 's' . getSiteSecret() . 'a' . $_SERVER['REMOTE_ADDR'] . 'l' .
                           $_SERVER['HTTP_USER_AGENT'] . 't' . json_encode($payload));

  return $payloadSecret == $comparisonSecret;
}

/**
 * Returns whether the user is logged into the CMS.
 *
 * @return boolean whether the user is logged into the CMS
 */
function isUserLoggedIn() {
  $payload = userPayload();

  return $payload && $payload['is_logged_in'];
}

/**
 * Returns whether the user is logged into the CMS and a site admin.
 *
 * @return boolean whether the user is logged into the CMS and a site admin
 */
function isUserLoggedInSiteAdmin() {
  $payload = userPayload();

  return $payload && $payload['is_logged_in'] && $payload['is_site_admin'];
}

/**
 * Easy Edit
 */

// Only continue with Easy Edit if it's enabled for the site.
if ($EASY_EDIT) {
  // If an Easy Edit query string parameter is set...
  if (changeEasyEditMode()) {
    // Set the appropriate cookie value based on whether Easy Edit is on, off,
    // or closed.
    if (turnEasyEditOn()) {
      setcookie('mcmsEasyEdit', 1, time() + 31536000, '/');
    }
    elseif (turnEasyEditOff()) {
      setcookie('mcmsEasyEdit', 0, time() + 31536000, '/');
    }
    elseif (closeEasyEdit()) {
      setcookie('mcmsEasyEdit', '', time() - 3600, '/');
    }

    // Redirect to same URL, minus Easy Edit query string parameters. This is
    // to prevent the URL from being copy/pasted with Easy Edit parameters.
    redirectEasyEdit();
  }
  elseif (showEasyEdit()) {
    // Start output buffering with a callback that is executed when the buffer
    // is flushed at the end of the request.
    ob_start('easyEditOutput');
  }
}

// PHP 5+ function that's necessary when running PHP 4.
// Source: http://us3.php.net/manual/en/function.stripos.php#65287
if (!function_exists('stripos')) {
  function stripos($haystack, $needle, $offset = 0) {
    return strpos(strtolower($haystack), strtolower($needle), $offset);
  }
}

/**
 * Output buffering callback that does some necessary cleanup of the inserted
 * Easy Edit HTML before output.
 *
 * @param  string $output content of the output buffer
 * @return string content of the output buffer
 */
function easyEditOutput($output) {
  global $MONKHOME;

  // If no output, strip Easy Edit HTML from the location header, since this is
  // most likely a redirect template.
  if (empty($output)) {
    easyEditCleanLocationHeader();

    return $output;
  }

  $isEasyEditOn = isEasyEditOn();

  // Find the start and end position of the <head> tag.
  $headStart = stripos($output, '<head>') + 6;
  $headEnd = stripos($output, '</head>');

  // Clean the <head> HTML only if that tag is present.
  if ($headStart !== false && $headEnd !== false) {
    $headLength = $headEnd - $headStart;

    // Extract the <head> tag and contained HTML.
    $head = substr($output, $headStart, $headLength);

    // Strip Easy Edit HTML from the <head>, where it's invalid.
    if ($isEasyEditOn) {
      $head = removeEasyEditHtml($head);
    }

    $head .= easyEditCss($MONKHOME);

    // Reinsert the altered <head> HTML.
    $output = substr_replace($output, $head, $headStart, $headLength);
  }

  // Clean up Easy Edit HTML that's within another HTML tag by moving it to
  // the beginning of said tag.
  if ($isEasyEditOn) {
    $output = preg_replace('/(<[^>]*)(<div class="mcms_easy_edit">.*?<\/div>)/ims', '\2\1', $output);
  }

  $bodyEnd = stripos($output, '</body>');

  // Append the toggle (whether on or off) to the <body> tag if present. This
  // prevents the toggle from being added to partial HTML (ajax).
  if ($bodyEnd !== false) {
    $insert = easyEditToggleHtml($MONKHOME);

    // JavaScript is only necessary when Easy Edit is on.
    if ($isEasyEditOn) {
      $insert .= easyEditJavaScript();
    }

    $output = substr_replace($output, $insert, $bodyEnd, 0);
  }
  // Otherwise, just append the JavaScript to the partial output.
  elseif ($isEasyEditOn) {
    $output .= easyEditJavaScript();
  }

  return $output;
}

/**
 * Builds the Easy Edit pencil icon HTML that's added to each getContent().
 *
 * @param  string $apiUrl URL of the API server
 * @param  string $queryString existing API query string
 * @return string Easy Edit pencil icon HTML
 */
function easyEditIconHtml($apiUrl, $queryString) {
  $html  = "<div class=\"mcms_easy_edit\">";
  $html .=   "<a href=\"" . $apiUrl . "/ekkContent.php?easyEdit&" . $queryString . "\" ";
  $html .=       "target=\"mcmsEasyEdit\" title=\"Edit content in CMS\">";
  $html .=     "<img src=\"" . $apiUrl . "/easy_edit_icon.png\" />";
  $html .=   "</a>";
  $html .= "</div>";

  return $html;
}

/**
 * Builds the toggle HTML for turning Easy Edit on or off, or closing.
 *
 * @param  string $apiUrl URL of the API server
 * @return string Easy Edit toggle HTML
 */
function easyEditToggleHtml($apiUrl) {
  if (isEasyEditOn()) {
    $class = "mcms_easy_edit_on";
    $url = easyEditOffUrl();
    $title = "Turn off Easy Edit";
    $imgFileName = "easy_edit_toggle_on.png";
  }
  else {
    $class = "mcms_easy_edit_off";
    $url = easyEditOnUrl();
    $title = "Turn on Easy Edit";
    $imgFileName = "easy_edit_toggle_off.png";
    $closeUrl = easyEditCloseUrl();
  }

  $html  = "<div id=\"mcms_easy_edit_toggle\" class=\"" . $class . "\">";
  $html .=   "<a href=\"" . $url . "\" title=\"" . $title . "\">";
  $html .=     "<img src=\"" . $apiUrl . "/" . $imgFileName . "\" />";
  $html .=   "</a>";

  // Show the close link only when Easy Edit is turned off.
  if (isset($closeUrl)) {
    $html .= "<a href=\"" . $closeUrl . "\" id=\"mcms_easy_edit_close\" title=\"Close Easy Edit\">Close</a>";
  }

  $html .= "</div>";

  return $html;
}

/**
 * Returns the necessary Easy Edit CSS, including <style> tag.
 *
 * @return string
 */
function easyEditCss($apiUrl) {
  return "<style type=\"text/css\">
            div#mcms_easy_edit_toggle {
              position: absolute;
              top: 15px;
              right: 15px;
              z-index: 9999;
            }

            div#mcms_easy_edit_toggle.mcms_easy_edit_off {
              opacity: 0.5;
              -webkit-transition: opacity .3s ease;
              -moz-transition: opacity .3s ease;
              -o-transition: opacity .3s ease;
              transition: opacity .3s ease;
            }

            div#mcms_easy_edit_toggle.mcms_easy_edit_off:hover {
              opacity: 1;
            }

            div#mcms_easy_edit_toggle a {
              position: relative;
              outline: none;
              z-index: 10000;
            }

            div#mcms_easy_edit_toggle a#mcms_easy_edit_close {
              position: absolute;
              top: -10px;
              right: -10px;
              width: 24px;
              height: 24px;
              text-indent: -9999px;
              z-index: 10001;
              background-image: url('" . $apiUrl . "/easy_edit_toggle_close.png');
            }

            div.mcms_easy_edit {
              position: absolute;
              margin: -15px 0 0 -15px !important;
              z-index: 9999;
              opacity: 0.75;
              -webkit-transition: opacity .3s ease;
              -moz-transition: opacity .3s ease;
              -o-transition: opacity .3s ease;
              transition: opacity .3s ease;
            }

            div.mcms_easy_edit:hover {
              opacity: 1 !important;
            }

            div.mcms_easy_edit.mcms_easy_edit_no_offset {
              margin: 0 !important;
            }

            div.mcms_easy_edit a {
              background: none !important;
              border: 0 !important;
              display: inline !important;
              height: auto !important;
              margin: 0 !important;
              padding: 0 !important;
              width: auto !important;
              outline: none !important;
            }

            div#mcms_easy_edit_toggle a img,
            div.mcms_easy_edit a img {
              background: none !important;
              width: auto !important;
              height: auto !important;
              border: 0 !important;
              margin: 0 !important;
            }
          </style>";
}

/**
 * Returns the necessary Easy Edit JavaScript (jQuery), including <script> tag.
 *
 * @return string
 */
function easyEditJavaScript() {
  return "<script type=\"text/javascript\">
            jQuery(document).ready(function() {
              jQuery('div.mcms_easy_edit').each(function() {
                if (jQuery(this).parent().css('overflow') == 'hidden') {
                  jQuery(this).addClass('mcms_easy_edit_no_offset');
                }
              });
            });
          </script>";
}

/**
 * Checks whether an Easy Edit change (on, off, close) parameter is in the
 * query string.
 *
 * @return bool
 */
function changeEasyEditMode() {
  return isset($_GET['easyEditOn']) || isset($_GET['easyEditOff']) || isset($_GET['easyEditClose']);
}

/**
 * Checks whether a request to turn on Easy Edit is being made via query string.
 *
 * @return bool
 */
function turnEasyEditOn() {
  return isset($_GET['easyEditOn']);
}

/**
 * Checks whether a request to turn off Easy Edit is being made via query string.
 *
 * @return bool
 */
function turnEasyEditOff() {
  return isset($_GET['easyEditOff']);
}

/**
 * Checks whether a request to close Easy Edit is being made via query string.
 *
 * @return bool
 */
function closeEasyEdit() {
  return isset($_GET['easyEditClose']);
}

/**
 * Checks whether Easy Edit is enabled (on or off) and should display at least
 * the toggle.
 *
 * @return bool
 */
function showEasyEdit() {
  return isset($_COOKIE['mcmsEasyEdit']);
}

/**
 * Checks whether Easy Edit is enabled and currently on.
 *
 * @return bool
 */
function isEasyEditOn() {
  return isset($_COOKIE['mcmsEasyEdit']) && $_COOKIE['mcmsEasyEdit'];
}

/**
 * Checks whether Easy Edit is enabled, but currently off.
 *
 * @return bool
 */
function isEasyEditOff() {
  return isset($_COOKIE['mcmsEasyEdit']) && !$_COOKIE['mcmsEasyEdit'];
}

/**
 * Returns the URL to turn on Easy Edit.
 *
 * @return string URL to turn on Easy Edit
 */
function easyEditOnUrl() {
  return easyEditUrl('easyEditOn');
}

/**
 * Returns the URL to turn off Easy Edit.
 *
 * @return string URL to turn off Easy Edit
 */
function easyEditOffUrl() {
  return easyEditUrl('easyEditOff');
}

/**
 * Returns the URL to close Easy Edit.
 *
 * @return string URL to close Easy Edit
 */
function easyEditCloseUrl() {
  return easyEditUrl('easyEditClose');
}

/**
 * Adds an Easy Edit change parameter to the current URL query string.
 *
 * @param  string $parameter change parameter to add
 * @return string current URL with added Easy Edit change parameter
 */
function easyEditUrl($parameter) {
  $url = $_SERVER['REQUEST_URI'];

  // Determine whether to create or append to the query string.
  if (strpos($url, '?') === false) {
    return $url . "?" . $parameter;
  }
  else {
    return $url . "&" . $parameter;
  }
}

/**
 * Redirects to the current URL minus any Easy Edit query string parameters.
 */
function redirectEasyEdit() {
  list($path, $queryString) = explode('?', $_SERVER['REQUEST_URI'], 2);

  // Strip any Easy Edit parameters from the query string.
  $queryString = preg_replace('/easyEdit(On|Off|Close)&?/i', '', $queryString);

  if (empty($queryString)) {
    $url = $path;
  }
  else {
    $url = $path . "?" . rtrim($queryString, '&');
  }

  header('Location: ' . $url);
  exit;
}

/**
 * Cleans any Easy Edit HTML from the 'Location' (redirect) header to prevent
 * redirect templates from breaking.
 *
 * @return bool whether the 'Location' (redirect) header was cleaned
 */
function easyEditCleanLocationHeader() {
  // Unfortunately, headers_list() is PHP 5+ only. Return if unavailable.
  if (!function_exists('headers_list')) {
    return false;
  }

  // Search through the headers array for the 'Location' (redirect) header.
  $locationHeader = preg_grep('/^Location:/i', headers_list());

  // If header is set, strip Easy Edit HTML and re-set.
  if (!empty($locationHeader)) {
    $locationHeader = array_shift($locationHeader);

    header(removeEasyEditHtml($locationHeader));

    return true;
  }
  else {
    return false;
  }
}

/**
 * Removes any Easy Edit pencil icon HTML from the requested string.
 *
 * @param  string $string string to clean
 * @return string cleaned string
 */
function removeEasyEditHtml($string) {
  return preg_replace('/<div class="mcms_easy_edit">.*?<\/div>/ims', '', $string);
}

/**
 * Adds Easy Edit pencil icon HTML to the requested getContent() output.
 *
 * @param  string $output getContent() output
 * @param  string $apiUrl URL of the API server
 * @param  string $queryString existing API query string
 * @param  bool   $isNoEdit whether 'noedit' was added to the getContent()
 * @param  string $module module name (first line) of getContent()
 * @return string output with added pencil icon HTML
 */
function easyEdit($output, $apiUrl, $queryString, $isNoEdit, $module) {
  if (isEasyEditOn() && !$isNoEdit && !empty($output)) {
    $iconHtml = easyEditIconHtml($apiUrl, $queryString);

    switch ($module) {
      // For navigation, insert the Easy Edit HTML before the first <li>.
      case 'navigation':
        $output = substr_replace($output, $iconHtml, strpos($output, '>') + 1, 0);

        break;

      case 'site':
        if (preg_match('/__tagline__|__trackingcode__/i', $queryString)) {
          break;
        }
        else {
          $output = $iconHtml . $output;
        }

      // Do not add the Easy Edit HTML for the following modules.
      case 'login':
      case 'register':
      case 'search':
        break;

      // By default, prepend the Easy Edit HTML to the output.
      default:
        $output = $iconHtml . $output;

        break;
    }
  }

  return $output;
}

/////////////////////////////////////////////////////////////////////////////////////////
//
// rtpsGetConfig() / eT, Jul 21, 2005
//   required: city=$city&state=$state
//
/////////////////////////////////////////////////////////////////////////////////////////
function rtpsGetConfig() {
  global $DOCROOT;

  $lines=file($_SERVER['DOCUMENT_ROOT'].'/monkcode.txt');

  foreach ($lines as $configline) {
    list($key,$val)=explode("=",$configline);
    $key=trim($key);
    $val=trim($val);
    $conf[$key]=$val;
  }

  if ($conf['API']=='') {
    $conf['API']='http://api.monkcms.com/Clients';
  }
  return $conf;
}

function str_replace_once($needle, $replace, $haystack) {
  // Looks for the first occurence of $needle in $haystack
  // and replaces it with $replace.
  $pos = strpos($haystack, $needle);
  if ($pos === false) {
    // Nothing found
    return $haystack;
  }
  return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function inSession($username) {
  global $MC;

  $loginString=$MC.'/.alive/'.$username;
  return filemtime($loginString);
}

function setSession($username,$ticket,$i, $tplid = NULL) {
  global $MC;
  global $_GET;
  global $SITEID;
  global $configVars;

  $expire=time()+60*60*24*30;

  if (!is_dir($MC.'/.alive/')) {
    $mkDir = mkdir($MC.'/.alive/');
  }
  $loginString=$MC.'/.alive/'.$username;
  if ($tplid) {
    $hashString = $_GET['siteid'] . $_GET['e'] . stripslashes($_GET['tplid']) . $configVars['SECRET'];
    ///print $hashString;
    $tplhash = sha1(md5($hashString));
    if ($_GET['tplkey'] != $tplhash) {
      //print "ERROR IN KEY VERIFICATION";
      return;
    }
    $loginString .= "-" . $tplid;
  }

  if (file_exists($loginString)) {
    $now=time();
    $ftime=filemtime($loginString);
    $lifetime=$now-$ftime;
    $expiry=60*60*24*30;
    if ($lifetime>$expiry || $_GET['t']) {
      clearSession($username);
      $OK=1;
    }
    else {
      $OK=0; // may be replay attack
      //print "REPLAY DECTED";exit;
    }
  }
  else {
    $OK=1;
  }

  if ($OK) {

    $_SESSION['session_username']=$username;
    $_SESSION['session_firstname']=$_GET['fn'];
    $_SESSION['session_lastname']=$_GET['ln'];
    $_SESSION['session_active']='yes';
    $_SESSION['session_ticket']=$ticket;
    $_SESSION['session_i']=$i;
    if ($tplid) {
      $_SESSION['tpl'] = TRUE;
      $_SESSION['tplid'] = $tplid;
    }
    $fp=@fopen($loginString,"w");         // Record that this session is alive
    if ($fp) {
      fwrite($fp,$someSignificantInfoOneDay);
      fclose($fp);
    }

  }

  return $OK;
}

function clearSession($username) {
  global $MC;
  $loginString=$MC.'/.alive/'.$username;
  if ($_SESSION['tpl']) {
    $loginString .= "-" . $_SESSION['tplid'];
  }

  @unlink($loginString);

  $expire=time()-42000;
  //setcookie(session_name(), '', time()-42000, '/');
  setcookie("session_username",'',$expire,'/');
  setcookie("session_active",'',$expire,'/');
  setcookie("session_ticket",'',$expire,'/');
  setcookie("session_i",'',$expire,'/');
  unset($_SESSION['session_username']);
  unset($_SESSION['session_active']);
  unset($_SESSION['session_ticket']);
  unset($_SESSION['session_i']);

  delStaleAliveFiles();
}

// delete files using glob and unlink (eT)
function delfile($str) {
  if (is_array(glob($str))) {
    foreach(glob($str) as $fn) {
      unlink($fn);
    }
  }
}

function delFolder($dir) {
  if (is_dir($dir)) {
    $contents = opendir($dir);
    while (($content = readdir($contents)) !== FALSE) {
      if (is_dir($dir.'/'.$content) && $content!='.' && $content != '..') {
        delFolder($dir.'/'.$content);
      }
      elseif ($content!='.' && $content != '..') {
        unlink($dir.'/'.$content);
      }
    }
    closedir($contents);
    rmdir($dir);
  }
}

function delStaleAliveFiles() {
  global $MC;

  // Look for other stale alive files to remove.
  if (is_dir($MC.'/.alive')) {
    $contents = opendir($MC.'/.alive/');
    while (($content = readdir($contents)) !== FALSE) {
      $currTime = mktime();
      if ($content!='.' && $content != '..' && ($currTime - filemtime($MC.'/.alive/'.$content)) > (60*60*24*30)) {
        unlink($MC.'/.alive/'.$content);
      }
    }
    closedir($contents);
  }
}

  /*
   * Post support, executes some code from getContent and the puts the client back to $_POST['return'];
   *
   * Required post variables:
   *    $_POST['return'] = "/path/file.php"  //full path to the file this should return you to
   *    $_POST['getContent'] = "app,caltype:call, params"  // the getContent call parameters
   *
   */
  if(isset($_POST['getContent'])){

    ob_start(); // turn on output buffering, no output to the user.

    if (!empty($_POST['custom'])) {
      foreach ($_POST['custom'] as $cus) {
        $C.='|'.$cus;
      }
    }
    $codeString=$_POST['getContent'].',custom:'.$C;
    $funcargs = explode(",", $codeString);

    call_user_func_array("getContent", $funcargs);

    ob_end_clean(); // if anything was output, it was captured, erase and end output buffering.

    // look at mcmsStatus to find success/failure
    if($GLOBALS['mcmsStatus']){
      if ($_POST['cartsuccess']) {
        //header("Location:$REF");
        header("Location:/cart/");
      }
      else {
          header("Location: " . $_POST['success']);
      }
    }
    else{
      header("Location: " . $_POST['failure']);
    }

  }


////////////////////////////////////////////////////////////////////////////////////////////////////
$p=parse_url($THISURI);
preg_match("/([a-z0-9\-]+)\/?$/i",$p['path'],$matches);
$MCMS_PAGETITLE=ucwords(str_replace("-"," ",$matches[1]));
if ($MCMS_PAGETITLE=='') {
  $MCMS_PAGETITLE=$MCMS_SITENAME;
}
$MCMS_VALUES=urlencode(serialize($_GET));

?>
