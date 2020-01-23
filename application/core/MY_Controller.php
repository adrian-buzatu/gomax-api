<?php
	class MY_Controller extends CI_Controller {
		private $_defaults = array(
			'header' => array('file' => 'header', 'data' => array()),
			'footer' => array('file' => 'footer', 'data' => array()),
    );
    private $_modelAutoloadEnabled = true;
    public function __construct($modelAutoLoadEnabled = true) {
      parent::__construct();
      $this->_modelAutoloadEnabled = $modelAutoLoadEnabled;
      // $this->_verifyLogin();
      if ($this->_modelAutoloadEnabled === true) {
        $this->load->model(ucfirst(controller()). '_model', ucfirst(controller()));
      }
    }

    private function _verifyLogin() {
    	if (!$this->User_model->verifyUser()) {
    		redirect(base_url(), 'auto');
    	}
    }
	}