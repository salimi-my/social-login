<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Public_init_elements Library
 */
class Public_init_elements
{

  var $CI;
  var $data;

  function __construct()
  {
    $this->CI = &get_instance();
  }

  /*
	 * Initialize elements
	 */
  function init_elements($args = array())
  {
    $this->init_head();
    $this->init_footer();
  }

  /*
	 * Check user login status
	 */
  function is_user_loggedin()
  {
    if (!$this->CI->session->userdata('is_user_login') && $this->CI->session->userdata('user_id') == '') {
      redirect('users/login');
    }
  }

  /*
	 * Head view
	 */
  function init_head()
  {
    $data = array();
    $this->CI->data['head'] = $this->CI->load->view('elements/head', $data, true);
  }

  /*
	 * Footer view
	 */
  function init_footer()
  {
    $data = array();
    $this->CI->data['footer'] = $this->CI->load->view('elements/footer', $data, true);
  }
}
