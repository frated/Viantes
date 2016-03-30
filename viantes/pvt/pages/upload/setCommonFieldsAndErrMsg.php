<?php
//Campi Da Reimpostare
if ( isset($_POST['catRev']) )  $backUrl .= "&catRev=".urlencode($_POST['catRev']);
if ( isset($_POST['city']) )    $backUrl .= "&city=".urlencode($_POST['city']);
if ( isset($_POST['country']) ) $backUrl .= "&country=".urlencode($_POST['country']);
if ( isset($_POST['locality']) )$backUrl .= "&locality=".urlencode($_POST['locality']);
if ( isset($_POST['site']) )    $backUrl .= "&site=".urlencode($_POST['site']);
if ( isset($_POST['descr']) )   $backUrl .= "&descr=".urlencode($_POST['descr']);
if ( isset($_POST['arrive']) )  $backUrl .= "&arrive=".urlencode($_POST['arrive']);
if ( isset($_POST['warn']) )    $backUrl .= "&warn=".urlencode($_POST['warn']);
if ( isset($_POST['whEat']) )   $backUrl .= "&whEat=".urlencode($_POST['whEat']);
if ( isset($_POST['cook']) )    $backUrl .= "&cook=".urlencode($_POST['cook']);
if ( isset($_POST['whStay']) )  $backUrl .= "&whStay=".urlencode($_POST['whStay']);
if ( isset($_POST['myth']) )    $backUrl .= "&myth=".urlencode($_POST['myth']);
if ( isset($_POST['vote']) )    $backUrl .= "&vote=".urlencode($_POST['vote']);

//Messaggi di errore Da Reimpostare
if ( isset($_POST['catRevErrMsg'])  && trim($_POST['catRevErrMsg']) != "" )  $backUrl .= "&catRevErrMsg=".urlencode(trim($_POST['catRevErrMsg']));
if ( isset($_POST['cityErrMsg'])    && trim($_POST['cityErrMsg']) != "" )    $backUrl .= "&cityErrMsg=".urlencode(trim($_POST['cityErrMsg']));
if ( isset($_POST['countryErrMsg']) && trim($_POST['countryErrMsg']) != "")  $backUrl .= "&countryErrMsg=".urlencode(trim($_POST['countryErrMsg']));
if ( isset($_POST['localityErrMsg'])&& trim($_POST['localityErrMsg']) != "") $backUrl .= "&localityErrMsg=".urlencode(trim($_POST['localityErrMsg']));
if ( isset($_POST['siteErrMsg'])    && trim($_POST['siteErrMsg']) != "")     $backUrl .= "&siteErrMsg=".urlencode(trim($_POST['siteErrMsg']));
if ( isset($_POST['descrErrMsg'])   && trim($_POST['descrErrMsg']) != "")    $backUrl .= "&descrErrMsg=".urlencode(trim($_POST['descrErrMsg']));
if ( isset($_POST['arriveErrMsg'])  && trim($_POST['arriveErrMsg']) != "")   $backUrl .= "&arriveErrMsg=".urlencode(trim($_POST['arriveErrMsg']));
if ( isset($_POST['warnErrMsg'])    && trim($_POST['warnErrMsg']) != "")     $backUrl .= "&warnErrMsg=".urlencode(trim($_POST['warnErrMsg']));
if ( isset($_POST['whEatErrMsg'])   && trim($_POST['whEatErrMsg']) != "")    $backUrl .= "&whEatErrMsg=".urlencode(trim($_POST['whEatErrMsg']));
if ( isset($_POST['cookErrMsg'])    && trim($_POST['cookErrMsg']) != "")     $backUrl .= "&cookErrMsg=".urlencode(trim($_POST['cookErrMsg']));
if ( isset($_POST['whStayErrMsg'])  && trim($_POST['whStayErrMsg']) != "")   $backUrl .= "&whStayErrMsg=".urlencode(trim($_POST['whStayErrMsg']));
if ( isset($_POST['mythErrMsg'])    && trim($_POST['mythErrMsg']) != "")     $backUrl .= "&mythErrMsg=".urlencode(trim($_POST['mythErrMsg']));
?>
