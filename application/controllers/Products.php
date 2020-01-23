<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Products extends MY_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('Categories_model', 'Categories');
			$this->load->model('Media_model', 'Media');
    }

	public function get($tag = '', $from = 0, $how_many = 25) {
		header("Access-Control-Allow-Origin: *");
		echo json_encode(array(
			'success' => true,
			'data' => $this->Products
				->setSearchString($tag)
				->setLimit(array('from' => $from, 'how_many' => $how_many))
				->get()
		));

	}
}