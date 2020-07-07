<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if (!isset($_SESSION)) {
			session_start();
		}
	}

	public function getterSesion() {
		if ($this->input->is_ajax_request()) {
			if (!isset($_SESSION['rut'])) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			show_404();
		}
	}

}