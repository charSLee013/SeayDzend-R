<?php
/*********************/
/*                   */
/*  Dezend for PHP5  */
/*         NWS       */
/*      Nulled.WS    */
/*                   */
/*********************/

header( "cache-control: no-store, no-cache, must-revalidate" );
ob_start( );
include_once( "inc/update.php" );
include_once( "inc/session.php" );
session_start( );
$RandomData = rand( 1000, 20000 );
$_SESSION['KEY_RANDOMDATA'] = $RandomData;
include_once( "inc/cache/cache.php" );
include_once( "inc/utility_all.php" );
if ( get_client_ip( ) == $_SERVER['SERVER_ADDR'] && !file_exists( $ROOT_PATH."inc/td_install.php" ) )
{
    file_put_contents( $ROOT_PATH."inc/td_install.php", strtoupper( dechex( time( ) ) ) );
    $query = "SELECT PASSWORD from USER where USER_ID='admin'";
    $cursor = exequery( $connection, $query );
    if ( $ROW = mysql_fetch_array( $cursor ) )
    {
        $PASSWORD = $ROW['PASSWORD'];
        if ( crypt( "", $PASSWORD ) == $PASSWORD )
        {
            $TIPS = "<div>".sprintf( _( "欢迎使用%s，登录帐号%s，密码为空" ), "<a href=\"http://".$TD_MYOA_WEB_SITE."\" target=\"_blank\">".$TD_MYOA_PRODUCT_NAME."</a>", "<a href=\"javascript:;\"  onclick=\"form1.UNAME.value='admin';\">admin</a>" )."</div>";
        }
    }
    $query = "SELECT TASK_URL from OFFICE_TASK where TASK_CODE='get_external_data'";
    $cursor = exequery( $connection, $query );
    if ( $ROW = mysql_fetch_array( $cursor ) )
    {
        $TASK_URL = $ROW['TASK_URL'];
        include_once( "inc/itask/itask.php" );
        itask( array( "EXEC_HTTP_TASK ".$TASK_URL ) );
    }
}
$SYS_INTERFACE = $td_cache->get( "SYS_INTERFACE" );
if ( is_array( $SYS_INTERFACE ) )
{
    cache_interface( );
}
$IE_TITLE = $SYS_INTERFACE['IE_TITLE'];
$ATTACHMENT_ID1 = $SYS_INTERFACE['ATTACHMENT_ID1'];
$ATTACHMENT_NAME1 = $SYS_INTERFACE['ATTACHMENT_NAME1'];
$TEMPLATE = $SYS_INTERFACE['TEMPLATE'];
$PARA_ARRAY = get_sys_para( "LOGIN_KEY,SEC_USER_MEM,MIIBEIAN" );
$PARA_VALUE = each( &$PARA_ARRAY )[1];
$PARA_NAME = each( &$PARA_ARRAY )[0];
while ( each( &$PARA_ARRAY ) )
{
    $$PARA_NAME = $PARA_VALUE;
}
if ( istouchdevice( ) && $t != "PC" )
{
    ob_clean( );
    $device = $_COOKIE['TD_MOBILE_DEVICE'];
    if ( $device )
    {
        $url = $device === "pad" ? "/pda/pad/" : "/pda/";
        header( "location:".$url );
    }
    echo "<!DOCTYPE html>\r\n<html>\r\n<head>\r\n\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gbk\">\r\n\t<meta name=\"viewport\" content=\"width=device-width, minimum-scale=1, maximum-scale=1\">\r\n\t<meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\" />\r\n\t<title>";
    echo $IE_TITLE;
    echo "</title>\r\n<style>\r\nhtml,body,div,span,a,img{margin:0;padding:0;border:0;outline:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;vertical-align:baseline;}\r\nbody {font-size:14px;-webkit-user-select:none;-webkit-text-size-adjust:none;font-family:Helvetica,Arial,sans-serif;}\r\n.mobiInfo{padding:0px 10px;}\r\n.mobiTips{line-height:19px;color:#666;}\r\n.mobiwrapper{text-align:center;margin:0px auto;}\r\n.mobiBox{background:#efefef url(\"/images/mobile_pc.png\") no-repeat 0 0;background-size:48px 120px;-o-background-size:48px 120px;-webkit-background-size:48px 120px;color:#0052E9;height:40px;line-height:40px;display:inline-block;border:2px solid #D9D9D9;padding:10px 10px 10px 50px;border-radius:5px;box-shadow:1px 1px 1px 0px #999;-moz-box-shadow:1px 1px 1px 0px #999;-webkit-border-shadow:1px 1px 1px 0px #999;font-size:20px;font-weight:bold;}\r\n.mobiwrapper a{margin-left:15px;}\r\n.mobiwrapper a:first-child{margin-left:0px;}\r\n.mobiBox{color:#0052E9;text-decoration:none;display:inline-block;}\r\n.mobiwrapper .pc{background-position:0 10px;}\r\n.mobiwrapper .mobi{background-position:0 -55px;}\r\n.mobiwrapper .pad{background-position:0 -55px;}\r\n#mobiMask{background: rgba(0,0,0,0.5);width:100%;height:100%;position:absolute;top:0;left:0;}\r\n#mobiConfirm{position:absolute;top:50%;left:50%;margin-left:-200px;margin-top:-30px;}\r\n.mobiConfirm-wrapper{position:relative;line-height:30px;background:#fff;border:1px solid #ccc;width:400px;height:160px;color:#666;}\r\n.mobiConfirm-mid{position:absolute;top:0;width:100%;padding: 10px 20px;\t}\r\n.mobiConfirm-btm{position:absolute;bottom:0;border-top:1px solid #ccc;height:40px;width:100%;\t}\r\n.mobiConfirm-btm .cancel,.mobiConfirm-btm .ok{width:49%;border:none;border-left:1px solid #ccc;background:#fff;display:inline-block;text-decoration:none;text-align:center;color:#919191;height: 40px;line-height: 40px;}\r\n.mobiConfirm-btm .cancel{border:none;}\r\n#mobiConfirm-msg b {color:#658CD5;}\r\n#mobiConfirm-tip {color:#999; font-size:12px;}\r\n#mobiConfirm-tip b{color:#555; font-size:12px;}\r\n.out{opacity:0;visibility: hidden;}\r\n.in{opacity:1;visibility: visible;}\r\n</style>\r\n<head>\r\n<body>\r\n<div class=\"mobiInfo\">\r\n   <p class=\"mobiTips\">";
    echo _( "使用“手机版”可节省流量并适配手机屏幕，使用“Pad HD版”可在平板设备上获得更佳体验，使用“电脑版”将和使用电脑访问展现的效果一样。" );
    echo "</p>\r\n   <div class=\"mobiwrapper\">\r\n      <a onclick=\"return mobi('mobi');\" href=\"/pda/\" class=\"mobiBox mobi\">";
    echo _( "手机版" );
    echo "</a>\r\n      <a onclick=\"return mobi('pad');\" href=\"/pda/pad\" class=\"mobiBox pad\">";
    echo _( "Pad HD版" );
    echo "</a>\r\n      <a href=\"/?t=PC\" class=\"mobiBox pc\">";
    echo _( "电脑版" );
    echo "</a>\r\n   </div>  \r\n</div>\r\n\r\n\r\n<div id=\"mobiMask\" class='out'>\r\n</div>\r\n<div id=\"mobiConfirm\" class='out'>\r\n\t<div class=\"mobiConfirm-wrapper\">\r\n\t\t\r\n\t\t<div class=\"mobiConfirm-top\">\r\n\t\t</div>\r\n\r\n\t\t<div class=\"mobiConfirm-mid\">\r\n\t\t\t\t<p id=\"mobiConfirm-msg\"></p>\r\n\t\t\t\t<p id=\"mobiConfirm-tip\">";
    echo sprintf( _( "您可以在 %s %s %s 中取消选择。" ), "<b>"._( "控制面板" )."</b>", "->", "<b>"._( "登录时记住终端类型" )."</b>" );
    echo "</p>\r\n\t\t</div>\r\n\r\n\t\t<div class=\"mobiConfirm-btm\">\r\n\t\t\t<a  href=\"javascript:void(0);\"  class=\"cancel\" onclick='mobi.cancel();'>";
    echo _( "否，直接登录" );
    echo "</a>\r\n\t\t\t<a  href=\"javascript:void(0);\"  class=\"ok\" onclick='mobi.ok();'>";
    echo _( "是，记住并登录" );
    echo "</a>\r\n\t\t</div>\r\n\t\t\r\n\t</div>\r\n</div>\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>\r\n<script>\t\t\t//cookie记录用户终端类型 by JinXin @ 2012/9/12\r\nvar lang = {\r\n\tmobi: '";
    echo _( "手机版" );
    echo "',\r\n\tpad: '";
    echo _( "Pad HD版" );
    echo "',\r\n\tconfirmMes: '";
    echo _( "是否记住我的选择： " );
    echo "'\r\n};\r\n\r\n\r\nfunction mobi(type){\r\n\tdocument.getElementById('mobiMask').className = 'in';\r\n\tdocument.getElementById('mobiConfirm').className = 'in';\r\n\tdocument.getElementById('mobiConfirm-msg').innerHTML = lang.confirmMes + '<b>' +  lang[type] + '</b>';\r\n\t\r\n\tmobi.ok = function(){\r\n\t\tdocument.getElementById('mobiMask').className = 'out';\r\n\t\tdocument.getElementById('mobiConfirm').className = 'out';\r\n\t\tlocation.href = type === 'mobi' ? '/pda/?save=1' : '/pda/pad/?save=1';\t\r\n\t};\r\n\tmobi.cancel = function(){\r\n\t\tdocument.getElementById('mobiMask').className = 'out';\r\n\t\tdocument.getElementById('mobiConfirm').className = 'out';\r\n\t\tlocation.href = type === 'mobi' ? '/pda/' : '/pda/pad/';\t\r\n\t};\t\r\n\t\r\n\treturn false;\r\n}\r\n\r\n</script>\r\n";
    exit( );
}
$LANGUAGE = "";
if ( $MYOA_IS_UN == 1 )
{
    include_once( "inc/utility.php" );
    $LABEL_USER = _( "用户名：" );
    $LABEL_PASSWORD = _( "密　码：" );
    $LABEL_LANGUAGE = _( "语　言：" );
    $LABEL_SUBMIT = _( "登录" );
    $LANGUAGE = "<select name=\"LANGUAGE\" onchange=\"ChgLang();\" class=\"language\">";
    $LANG_ARRAY = get_lang_array( );
    foreach ( $LANG_ARRAY as $LANG => $LANG_DESC )
    {
        $LANGUAGE .= "<option value=\"".$LANG."\"".( $LANG == $_COOKIE['LANG_COOKIE'] ? " selected" : "" ).">".$LANG_DESC."</option>";
    }
    $LANGUAGE .= "</select>";
}
if ( $MIIBEIAN != "" )
{
    $MIIBEIAN = "<a href=\"http://www.miibeian.gov.cn/\" target=\"_blank\">".$MIIBEIAN."</a>";
}
if ( stristr( $ATTACHMENT_NAME1, ".swf" ) )
{
    $LOGO_TYPE = "swf";
}
if ( $ATTACHMENT_ID1 != "" && $ATTACHMENT_NAME1 != "" )
{
    $ATTACHMENT_PATH = $ATTACH_PATH.$ATTACHMENT_ID1."/".$ATTACHMENT_NAME1;
    if ( file_exists( $ATTACHMENT_PATH ) )
    {
        $LOGO_PATH = "/inc/attach_logo.php";
        $IMG_ATTR = getimagesize( $ATTACHMENT_PATH );
    }
}
else if ( $TEMPLATE == "2008" )
{
    $LOGO_PATH = "templates/2008/logo.png";
    $IMG_ATTR = getimagesize( "templates/2008/logo.png" );
}
$LOGO_WIDTH = $IMG_ATTR[0];
$LOGO_HEIGHT = $IMG_ATTR[1];
if ( 800 < $LOGO_WIDTH )
{
    $LOGO_WIDTH = 800;
}
if ( 600 < $LOGO_HEIGHT )
{
    $LOGO_HEIGHT = 600;
}
$AUTOCOMPLETE = "autocomplete=\"off\"";
$USER_NAME_COOKIE = $SEC_USER_MEM == "1" ? $_COOKIE['USER_NAME_COOKIE'] : "";
if ( $USER_NAME_COOKIE == "" )
{
    $FOCUS = "UNAME";
}
else
{
    $FOCUS = "PASSWORD";
}
if ( $LOGIN_KEY == "1" && !stristr( $_SERVER['HTTP_USER_AGENT'], "Firefox" ) )
{
    $JAVA_SCRIPT .= "<script src=\"/inc/tdPass.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar $ = function(id) {return document.getElementById(id);};\r\nvar userAgent = navigator.userAgent.toLowerCase();\r\nvar is_opera = userAgent.indexOf(\"opera\") != -1 && opera.version();\r\nvar is_ie = (userAgent.indexOf(\"msie\") != -1 && !is_opera) && userAgent.substr(userAgent.indexOf(\"msie\") + 5, 3);\r\nfunction CheckForm()\r\n{\r\n   var theDevice=document.getElementById(\"tdPass\");\r\n   KeySN=READ_SN(theDevice);\r\n   Digest=COMPUTE_DIGEST(theDevice,".$RandomData.");\r\n   Key_UserID=READ_KEYUSER(theDevice);\r\n   document.form1.KEY_SN.value=KeySN;\r\n   document.form1.KEY_DIGEST.value=Digest;\r\n   document.form1.KEY_USER.value=Key_UserID;\r\n\r\n   return true;\r\n}\r\nfunction showTdPassObject()\r\n{\r\n   document.getElementById(\"tdPassObject\").innerHTML='<object id=\"tdPass\" name=\"tdPass\" CLASSID=\"clsid:0272DA76-96FB-449E-8298-178876E0EA89\"\tCODEBASE=\"/inc/tdPass_".( stristr( $_SERVER['HTTP_USER_AGENT'], "x64" ) ? "x64" : "x86" ).".cab#version=1,2,12,1023\" BORDER=\"0\" VSPACE=\"0\" HSPACE=\"0\" ALIGN=\"TOP\" HEIGHT=\"0\" WIDTH=\"0\"></object>';\r\n   document.getElementById(\"installTdPass\").style.display=\"none\";\r\n}\r\nfunction showInstallObject()\r\n{\r\n   try{\r\n      var tdPass=new ActiveXObject(\"FT_ND_SC.ePsM8SC.1\");\r\n      showTdPassObject();\r\n   }\r\n   catch(e){//alert(e.description)\r\n      if(document.getElementById(\"installTdPass\"))\r\n         document.getElementById(\"installTdPass\").style.display=\"\";\r\n   }\r\n}\r\n".( $MYOA_IS_UN ? "\r\nfunction ChgLang()\r\n{\r\n   document.cookie = \"LANG_COOKIE=\" + document.form1.LANGUAGE.value;\r\n   location=\"index.php\";\r\n}" : "" )."\r\nif(is_ie)\r\n   window.attachEvent(\"onload\", showInstallObject);\r\nelse\r\n   window.addEventListener(\"load\", showInstallObject,false);\r\n</script>";
    $USB_KEY_OBJECT = "\r\n  <input type=\"hidden\" name=\"KEY_SN\" value=\"\">\r\n  <input type=\"hidden\" name=\"KEY_USER\" value=\"\">\r\n  <input type=\"hidden\" name=\"KEY_DIGEST\" value=\"\">\r\n  <div id=\"tdPassObject\"></div>";
    $USB_KEY_OPTION = "<a id=\"installTdPass\" href=\"javascript:showTdPassObject();\" style=\"display:none;\">"._( "安装USB Key插件" )."</a>";
}
else
{
    $JAVA_SCRIPT .= "\r\n<script type=\"text/javascript\">\r\nfunction CheckForm()\r\n{\r\n   return true;\r\n}\r\n".( $MYOA_IS_UN ? "\r\nfunction ChgLang()\r\n{\r\n   document.cookie = \"LANG_COOKIE=\" + document.form1.LANGUAGE.value;\r\n   location=\"index.php\";\r\n}" : "" )."\r\n</script>";
}
$ON_SUBMIT = "return CheckForm();";
if ( $LOGO_TYPE == "swf" )
{
    $LOGO_IMG = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"/inc/swflash.cab\" WIDTH=\"".$LOGO_WIDTH."\" HEIGHT=\"".$LOGO_HEIGHT."\">\r\n         <PARAM NAME=\"movie\" VALUE=\"".$LOGO_PATH."\">\r\n         <PARAM NAME=\"quality\" VALUE=\"high\">\r\n         <EMBED src=\"".$LOGO_PATH."\" quality=\"high\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"></EMBED>\r\n        </OBJECT>";
}
else
{
    $LOGO_IMG = "<img src=\"".$LOGO_PATH."\" width=\"".$LOGO_WIDTH."\" height=\"".$LOGO_HEIGHT."\">";
}
$ANTIVIRUS_SCRIPT = file_get_contents( $ROOT_PATH."inc/antivirus.txt" );
if ( file_exists( $ROOT_PATH.( "templates/".$TEMPLATE."/index.html" ) ) )
{
    $OUTPUT_HTML = file_get_contents( $ROOT_PATH.( "templates/".$TEMPLATE."/index.html" ) );
}
else
{
    $OUTPUT_HTML = file_get_contents( $ROOT_PATH."templates/default/index.html" );
}
$OUTPUT_HTML = str_replace( array( "{charset}", "{title}", "{javascript}", "{focus_filed}", "{autocomplete}", "{form_submit}", "{logo_img}", "{username_cookie}", "{usbkey_object}", "{antivirus_script}", "{usb_key_option}", "{tips}", "{miibeian}", "{update_tips}", "{language}", "{label_user}", "{label_password}", "{label_language}", "{label_submit}" ), array( $MYOA_CHARSET, $IE_TITLE, $JAVA_SCRIPT, $FOCUS, $AUTOCOMPLETE, $ON_SUBMIT, $LOGO_IMG, $USER_NAME_COOKIE, $USB_KEY_OBJECT, $ANTIVIRUS_SCRIPT, $USB_KEY_OPTION, $TIPS, $MIIBEIAN, $UPDATE_TIPS, $LANGUAGE, $LABEL_USER, $LABEL_PASSWORD, $LABEL_LANGUAGE, $LABEL_SUBMIT ), $OUTPUT_HTML );
echo $OUTPUT_HTML;
?>
