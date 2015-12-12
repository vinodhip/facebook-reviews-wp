<?php
session_start();
require 'src/config.php';
require 'src/facebook.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => $config['App_ID'],
  'secret' => $config['App_Secret'],
  'cookie' => true
));
$publish = $facebook->api('/362622557223811/ratings', 'GET', array('access_token' => 'CAAVpSPiymFMBABmrUZAZCHvixNmtPL5povigLKS1KXS8ZA9s9S72dG7vgVZBaPZCUxpHdy29sou2HiPzozV8fgXSuGeIOIloZCqIXq03SdmyxY8177mNL8nhH6xwX3FhZCQFnXFHnap6OHBtwzivnfHkEQuKszqycQrUymvbJkfp83C2EZB2luqYXWiZCImYcnGQZD', 'from' => $config['App_ID'] ));
  echo '<pre>';
  print_r($publish);
  echo '</pre>';

?>