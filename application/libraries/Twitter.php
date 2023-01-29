<?php

/**
 * Twitter OAauth PHP for CodeIgniter 3.x
 *
 * Library for Twitter OAauth PHP . It helps the user to login with their Twitter account
 * in CodeIgniter application.
 *
 * This library requires the Twitter OAauth PHP and it should be placed in libraries folder.
 *
 * It also requires social configuration file and it should be placed in the config directory.
 *
 */

// Load Twitter OAuth lib.
require_once 'twitter-oauth-php/twitteroauth.php';

/**
 * Twitter OAuth class
 */
class Twitter extends TwitterOAuth
{
	public $http_code;
	public $host = "https://api.twitter.com/1.1/";
	public $tw_user_id = null;
	public $tw_user_name = null;
	public $tw_user_info = null;
	public $tw_config = null;
	public $tw_request_token = null;
	public $tw_access_token = null;
	public $tw_status = null;
	public $CI;

	/**
	 * construct Twitter object
	 */
	function __construct($oauth_token = NULL, $oauth_token_secret = NULL)
	{
		$this->CI = &get_instance();
		$this->CI->load->config('social');
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		/* Try to retrieve user access token (permanent) from session */
		$access_token = $this->CI->session->userdata('tw_access_token');
		if ($access_token && isset($access_token['oauth_token']) && isset($access_token['oauth_token_secret'])) {
			parent::__construct($this->CI->config->item('twitter_api_key'), $this->CI->config->item('twitter_api_secret'), $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$this->tw_access_token = $access_token;
			$this->tw_user_id = (isset($access_token['user_id']) ? $access_token['user_id'] : null);
			$this->tw_user_name = (isset($access_token['screen_name']) ? $access_token['screen_name'] : null);
		} else {
			$request_token = $this->CI->session->userdata('tw_request_token');
			if ($request_token && isset($request_token['oauth_token']) && isset($request_token['oauth_token_secret'])) {
				parent::__construct($this->CI->config->item('twitter_api_key'), $this->CI->config->item('twitter_api_secret'), $request_token['oauth_token'], $request_token['oauth_token_secret']);
				$this->tw_request_token = $request_token;
			} else {
				parent::__construct($this->CI->config->item('twitter_api_key'), $this->CI->config->item('twitter_api_secret'));
			}
		}
		$this->tw_config = array('api_key' => $this->CI->config->item('twitter_api_key'), 'api_secret' => $this->CI->config->item('twitter_api_secret'), 'redirect_url' => $this->CI->config->item('twitter_redirect_url'));
		$this->tw_status = $this->CI->session->userdata('tw_status');
	}

	/**
	 * Redirects to Twitter for authetication
	 * $site_redirect_path - site segment where the user will be redirected after authentication
	 */
	public function redirect($type = '')
	{
		$request_token = $this->getRequestToken(site_url($this->tw_config['redirect_url']));
		$token = !empty($request_token['oauth_token']) ? $request_token['oauth_token'] : '';
		$this->CI->session->set_userdata('tw_request_token', $request_token);

		/* if type is 1 then only URL will be returned */
		if ($type == 1) {
			return $this->getAuthorizeURL($token);
		}

		/* If last connection failed don't redirect to Twitter: */
		switch ($this->http_code) {
			case 200:
				redirect($this->getAuthorizeURL($token));
				return true;
				break;
			default:
				/* Return false if cannot connect to Twitter */
				return false;
		}
	}

	/**
	 * Requests permanent access token after callback
	 */
	public function callback()
	{
		$returned_request_token = $this->CI->input->get('oauth_token');
		$request_token = $this->tw_request_token;
		if ($returned_request_token) {
			if ($request_token['oauth_token'] != $returned_request_token) {
				$this->CI->session->unset_userdata('tw_request_token');
				$this->tw_request_token = null;
				$this->tw_status = 'old_token';
				$this->CI->session->set_userdata('tw_status', 'old_token');
				return false;
			} else {
				$access_token = $this->getAccessToken($this->CI->input->get('oauth_verifier'));
				/* If HTTP response is 200 (successful) continue otherwise return false */
				if ($this->http_code == 200) {
					/* Save the access token to session.
										 * Later it should be saved to a database in a user's record. */
					$this->tw_access_token = $access_token;
					$this->tw_user_id = $access_token['user_id'];
					$this->tw_user_name = $access_token['screen_name'];
					$this->CI->session->set_userdata('tw_access_token', $access_token);
					$this->tw_request_token = null;
					$this->CI->session->unset_userdata('tw_request_token');
					$this->tw_status = 'verified';
					$this->CI->session->set_userdata('tw_status', 'verified');
					return true;
				} else {
					$this->tw_status = 'access_error';
					$this->CI->session->set_userdata('tw_status', 'access_error');
					return false;
				}
			}
		} else {
			return false;
		}
	}

	/**
	 * GET request to Twitter API
	 */
	public function tw_get($url, $parameters = array())
	{
		$token = $this->tw_access_token;
		if (isset($token['oauth_token'])	&& isset($token['oauth_token_secret']) && $this->tw_status == 'verified') {
			/* If method is set change API call made. Test is called by default. */
			return $this->get($url, $parameters);
		} else {
			return false;
		}
	}

	/**
	 * POST request to Twitter API
	 */
	public function tw_post($url, $parameters = array())
	{
		$token = $this->tw_access_token;
		if ($token && isset($token['oauth_token']) && isset($token['oauth_token_secret']) && $this->tw_status == 'verified') {
			/* If method is set change API call made. Test is called by default. */
			return $this->post($url, $parameters);
		} else {
			return false;
		}
	}

	/**
	 * Gets all Twitter user info and saves it to $this->tw_user_info
	 */
	public function verify_credentials()
	{
		$this->tw_user_info = $this->get('account/verify_credentials', ['include_email' => 'true']);
		return $this->tw_user_info;
	}
}
