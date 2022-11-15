<?
session_start();
$captcha=$_REQUEST['captcha'];

if (strtolower($captcha)==strtolower($_SESSION['captcha']['code'])){
   print "1";
}else{   print "0";
}
?>