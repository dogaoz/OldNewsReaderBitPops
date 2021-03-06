<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
require 'functions.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication('1593784980943909','fb467b582ab2039d77b7c87cf554cfc7');
$helper = new FacebookRedirectLoginHelper('http://bitpops.com/fbconfig.php');
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest ( $session, 'GET', '/me?fields=email,first_name,last_name' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfirstname = $graphObject->getProperty('first_name'); // To Get Facebook full name
		$fblastname = $graphObject->getProperty('last_name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
		checkuser($fbid,$fbfirstname,$fblastname,$femail);
		$userId = getUserIDusingFID($fbid); 
	/* ---- Session Variables -----*/
		$_SESSION['USERID'] = $userId;   
	    $_SESSION['FBID'] = $fbid;           
        $_SESSION['FULLNAME'] = $fbfirstname .' '.$fblastname;
		$_SESSION['FIRSTNAME'] = $fbfirstname;
		$_SESSION['LASTNAME'] = $fblastname;
	    $_SESSION['EMAIL'] =  $femail;

    /* ---- header location after session ----*/
	  header("Location: home.php");

} else {
	$loginUrl = $helper->getLoginUrl(array('scope' => 'email'));
 header("Location: ".$loginUrl);
}
?>

