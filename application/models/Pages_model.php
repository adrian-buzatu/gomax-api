<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends MY_Model {
	private $_fields = array(
    'id' => array(
      'type' => 'INT',
      'constraint' => 11,
      'unsigned' => TRUE,
      'auto_increment' => TRUE
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'slug' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'description' => array(
      'type' => 'TEXT'
    ),
    'description_raw' => array(
      'type' => 'TEXT'
    ),
    'status' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '1',
    ),
    '__dt:searchable' => array('name')
	);
	public function __construct()
	{
	  parent::__construct('pages', $this->_fields, array('primary' => array('id')));
	  // Your own constructor code
	}

}