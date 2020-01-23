<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends MY_Model {
	private $_fields = array(
    'id' => array(
      'type' => 'INT',
      'constraint' => 11,
      'unsigned' => TRUE,
      'auto_increment' => TRUE
    ),
    'from' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'subject' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'content' => array(
      'type' => 'TEXT'
    ),
    'is_read' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '0',
    ),
    'status' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '1',
    ),
    '__dt:searchable' => array('subject', 'from')
	);
	public function __construct()
	{
	  parent::__construct('messages', $this->_fields, array('primary' => array('id')));
	  // Your own constructor code
	}

}