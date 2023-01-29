<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Users Controller
 */
class Users extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('public_init_elements');
    $this->public_init_elements->init_elements();
    $this->load->model('users_m');

    // Load facebook library
    $this->load->library('facebook');

    // Load twitter library
    $this->load->library('twitter');

    // check whether user ID is available in session
    if ($this->session->userdata('is_user_login')) {
      $this->user_id = $this->session->userdata('user_id');
    } else {
      $this->user_id = '';
    }

    // default layout
    $this->layout = 'layout';
  }

  public function index()
  {
    // load dashboard for logged in user, else login page
    if ($this->user_id) {
      $this->dashboard();
    } else {
      $this->login();
    }
  }

  /*
   * Dashboard
   */
  public function dashboard()
  {
    // check user login status
    $this->public_init_elements->is_user_loggedin();
    $data = array();

    // get user info
    $data['user'] = $this->users_m->getRows(array('id' => $this->user_id));

    // set metadata
    $data['title'] = 'Dashboard';
    $data['page'] = 'dashboard';
    $data['description'] = 'This is a CodeIgniter Social Media Login App. This simple social media account authentication web app created for educational purposes only.';

    // load dashboard view
    $this->data['head'] = $this->load->view('elements/head', $data, true);
    $this->data['maincontent'] = $this->load->view('users/dashboard', $data, true);
    $this->load->view($this->layout, $this->data);
  }

  /*
   * Login
   */
  public function login()
  {
    // redirect logged in user to dashboard
    if ($this->user_id) {
      redirect('dashboard');
    }

    // Include the google api php libraries
    include_once APPPATH . "libraries/google-api-php-client/Google_Client.php";
    include_once APPPATH . "libraries/google-api-php-client/contrib/Google_Oauth2Service.php";
    $this->load->config('social');
    // Google API Credentials
    $client_id = $this->config->item('google_client_id');
    $client_secret = $this->config->item('google_client_secret');
    $redirect_url = base_url() . $this->config->item('google_redirect_url');

    // Google Client Configuration
    $google = new Google_Client();
    $google->setApplicationName('Login to Social Login');
    $google->setClientId($client_id);
    $google->setClientSecret($client_secret);
    $google->setRedirectUri($redirect_url);
    $google_oauthV2 = new Google_Oauth2Service($google);

    if (isset($_GET['code']) && !isset($_GET['state'])) {
      $google->authenticate($_GET['code']);
      $this->session->set_userdata('google_access_token', $google->getAccessToken());
      redirect($redirect_url);
    }
    $gp_access_token = $this->session->userdata('google_access_token');
    if (!empty($gp_access_token)) {
      $google->setAccessToken($gp_access_token);
    }

    $data = array();

    // Check if user is logged in
    if ($this->facebook->is_authenticated()) {
      // Get user facebook profile details
      $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture.width(128).height(128)');

      if (!empty($userProfile) && !empty($userProfile['id'])) {
        // Preparing data for database insertion
        $userData['oauth_provider'] = 'facebook';
        $userData['oauth_uid'] = !empty($userProfile['id']) ? $userProfile['id'] : '';
        $userData['first_name'] = !empty($userProfile['first_name']) ? $userProfile['first_name'] : '';
        $userData['last_name'] = !empty($userProfile['last_name']) ? $userProfile['last_name'] : '';
        $userData['email'] = !empty($userProfile['email']) ? $userProfile['email'] : '';
        $userData['gender'] = !empty($userProfile['gender']) ? $userProfile['gender'] : '';
        $userData['link'] = 'https://www.facebook.com/' . $userData['oauth_uid'];
        $userData['picture'] = !empty($userProfile['picture']['data']['url']) ? $userProfile['picture']['data']['url'] : '';

        // Insert or update user data
        $userData = $this->users_m->checkUser($userData);

        // set variables in session
        $this->session->set_userdata('is_user_login', TRUE);
        $this->session->set_userdata('user_id', $userData['id']);
        $this->session->set_userdata('loginType', 'social');

        // redirect to dashboard
        redirect('dashboard');
      } else {
        // Get login URL
        $data['fb_login_url'] =  $this->facebook->login_url();
        $data['gl_login_url'] = $google->createAuthUrl();
        $data['tw_login_url'] = $this->twitter->redirect(1);
      }
    } elseif ($google->getAccessToken()) {
      $userProfile = $google_oauthV2->userinfo->get();

      if (!empty($userProfile) && !empty($userProfile['id'])) {
        // Preparing data for database insertion
        $userData['oauth_provider'] = 'google';
        $userData['oauth_uid'] = !empty($userProfile['id']) ? $userProfile['id'] : '';
        $userData['first_name'] = !empty($userProfile['given_name']) ? $userProfile['given_name'] : '';
        $userData['last_name'] = !empty($userProfile['family_name']) ? $userProfile['family_name'] : '';
        $userData['email'] = !empty($userProfile['email']) ? $userProfile['email'] : '';
        $userData['gender'] = !empty($userProfile['gender']) ? $userProfile['gender'] : '';
        $userData['link'] = !empty($userProfile['link']) ? $userProfile['link'] : '';
        $userData['picture'] = !empty($userProfile['picture']) ? $userProfile['picture'] : '';
        // Insert or update user data
        $userData = $this->users_m->checkUser($userData);
        //set variables in session
        $this->session->set_userdata('is_user_login', TRUE);
        $this->session->set_userdata('user_id', $userData['id']);
        $this->session->set_userdata('loginType', 'social');

        // redirect to dashboard
        redirect('dashboard/');
      } else {
        // Get login URL
        $data['fb_login_url'] =  $this->facebook->login_url();
        $data['gl_login_url'] = $google->createAuthUrl();
        $data['tw_login_url'] = $this->twitter->redirect(1);
      }
    } elseif ($this->twitter->callback()) {
      $userProfile = $this->twitter->verify_credentials();
      if (!empty($userProfile) && !empty($userProfile->id)) {
        // Preparing data for database insertion
        $name = explode(" ", $userProfile->name);
        $userData['oauth_provider'] = 'twitter';
        $userData['oauth_uid'] = !empty($userProfile->id) ? $userProfile->id : '';
        $userData['first_name'] = isset($name[0]) ? $name[0] : '';
        $userData['last_name'] = isset($name[1]) ? $name[1] : '';
        $userData['email'] = !empty($userProfile->email) ? $userProfile->email : $userProfile->screen_name . '@twitter.com';
        $userData['link'] = !empty($userProfile->screen_name) ? 'https://twitter.com/' . $userProfile->screen_name : '';
        $userData['picture'] = !empty($userProfile->profile_image_url) ? $userProfile->profile_image_url : '';
        // Insert or update user data
        $userData = $this->users_m->checkUser($userData);
        // set variables in session
        $this->session->set_userdata('is_user_login', TRUE);
        $this->session->set_userdata('user_id', $userData['id']);
        $this->session->set_userdata('loginType', 'social');

        // redirect to dashboard
        redirect('dashboard/');
      } else {
        // Get login URL
        $data['fb_login_url'] =  $this->facebook->login_url();
        $data['gl_login_url'] = $google->createAuthUrl();
        $data['tw_login_url'] = $this->twitter->redirect(1);
      }
    } else {
      // Get login URL
      $data['fb_login_url'] = $this->facebook->login_url();
      $data['gl_login_url'] = $google->createAuthUrl();
      $data['tw_login_url'] = $this->twitter->redirect(1);
    }

    // set metadata
    $data['title'] = 'Login';
    $data['page'] = 'login';
    $data['description'] = 'This is a CodeIgniter Social Media Login App. This simple social media account authentication web app created for educational purposes only.';

    // load login view
    $this->data['head'] = $this->load->view('elements/head', $data, true);
    $this->data['maincontent'] = $this->load->view('users/login', $data, true);
    $this->load->view($this->layout, $this->data);
  }

  /*
   * User logout
   */
  public function logout()
  {
    // remove session data
    $this->session->unset_userdata('is_user_login');
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('facebook_access_token');
    $this->session->unset_userdata('google_access_token');
    $this->session->unset_userdata('tw_access_token');
    $this->session->sess_destroy();

    // redirect to login page
    redirect('login');
  }
}
