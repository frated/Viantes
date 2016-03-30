<?php

define('FACEBOOK_SDK_V4_SRC_DIR', '/viantes/pvt/pages/_fb/src/Facebook/');
require '/viantes/pvt/pages/_fb/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication('YOUR_APP_ID','YOUR_APP_SECRET');

// Note: The base hostname of the callback URL needs to be defined in the app settings. 
// You'll need to browse to your app in the list of your Facebook apps. 
// Then navigate to the "Settings" tab. In the "App Domains" field, enter any host names you'll be using. 
// Then click, "Add Platform" and choose, "Website". Enter the root URL of your redirect URL in the "Site URL" field.
$helper = new FacebookRedirectLoginHelper('http://yourhost/facebook/' );

try {
	$session = $helper->getSessionFromRedirect();
}
catch( FacebookRequestException $ex ) {
	echo "Error during get session from redirect";
	prinf_r($ex);
} 
catch( Exception $ex ) {
	echo "Error during get session from redirect";
	prinf_r($ex);
}

// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();

  // print data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
} 
else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}

?>