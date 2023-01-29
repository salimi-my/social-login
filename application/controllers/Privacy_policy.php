<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Privacy_policy Controller
 */
class Privacy_policy extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('public_init_elements');
    $this->public_init_elements->init_elements();

    // default layout
    $this->layout = 'layout';
  }

  public function index()
  {
    // set metadata
    $data['title'] = 'Privacy Policy';
    $data['page'] = 'privacy-policy';
    $data['description'] = 'This is a CodeIgniter Social Media Login App. This simple social media account authentication web app created for educational purposes only.';

    // load privacy policy view
    $this->data['head'] = $this->load->view('elements/head', $data, true);
    $this->data['maincontent'] = $this->load->view('privacy_policy', $data, true);
    $this->load->view($this->layout, $this->data);
  }
}
