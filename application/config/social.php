<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Facebook API Configuration
| -------------------------------------------------------------------
|
| To get Facebook app details you have to create a Facebook app
| at Facebook developers panel (https://developers.facebook.com)
|
*/
$config['facebook_app_id']              = 'Add your Facebook App ID here';
$config['facebook_app_secret']          = 'Add your Facebook App Secret here';
$config['facebook_login_redirect_url']  = 'users/login/';
$config['facebook_login_type']          = 'web';
$config['facebook_permissions']         = array('email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;

/*
| -------------------------------------------------------------------
|  Google API Configuration
| -------------------------------------------------------------------
|
| To get Google app details you have to create a Google app
| at Google developers console (https://console.developers.google.com)
|
*/
$config['google_client_id']     = 'Add your Google Client ID here';
$config['google_client_secret'] = 'Add your Google Client Secret here';
$config['google_redirect_url']  = 'users/login/';

/*
| -------------------------------------------------------------------
|  Twitter API Configuration
| -------------------------------------------------------------------
|
| To get Twitter app details you have to create a Twitter app
| at Twitter Application Management panel (https://apps.twitter.com)
|
*/
$config['twitter_api_key']      = 'Add your Twitter API Key here';
$config['twitter_api_secret']   = 'Add your Twitter API Secret here';
$config['twitter_redirect_url'] = 'users/login/';
