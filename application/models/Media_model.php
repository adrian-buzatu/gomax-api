<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends MY_Model {
	private $_fields = array(
    'id' => array(
      'type' => 'INT',
      'constraint' => 11,
      'unsigned' => TRUE,
      'auto_increment' => TRUE
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'constraint' => '255'
    ),
    'friendly_name' => array(
      'type' => 'VARCHAR',
      'constraint' => '255'
    ),
    'type' => array(
      'type' => 'VARCHAR',
      'constraint' => '10',
    ),

    'status' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '1',
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'constraint' => '255'
    ),
    'description' => array(
      'type' => 'TEXT'
    ),
    'description_raw' => array(
      'type' => 'TEXT'
    ),
    '__dt:searchable' => array('name', 'title')
	);

	// private $_relationMap = array(
	// 	'products_media' => array(
	// 		'id' => array(
	//       'type' => 'INT',
	//       'constraint' => 11,
	//       'unsigned' => TRUE,
	//       'auto_increment' => TRUE
	//     ),
	//     'product_id' => array(
	//       'type' =>'INT',
	//       'constraint' => '11'
	//     ),
	//     'media_id' => array(
	//       'type' =>'INT',
	//       'constraint' => '11'
	//     ),
	//     '__comm:baseIdField' => 'product_id',
	//     '__comm:relationIdField' => 'media_id',
	//     '__comm:keys' => array('product_id', 'media_id'),
	//     '__comm:pkeys' => array('id'),
	//     '__comm:tableName' => 'products_media'
	// 	),
	// 	'products_media' => array(
	// 		'id' => array(
	//       'type' => 'INT',
	//       'constraint' => 11,
	//       'unsigned' => TRUE,
	//       'auto_increment' => TRUE
	//     ),
	//     'product_id' => array(
	//       'type' =>'INT',
	//       'constraint' => '11'
	//     ),
	//     'media_id' => array(
	//       'type' =>'INT',
	//       'constraint' => '11'
	//     ),
	//     '__comm:baseIdField' => 'product_id',
	//     '__comm:relationIdField' => 'media_id',
	//     '__comm:keys' => array('product_id', 'media_id'),
	//     '__comm:pkeys' => array('id'),
	//     '__comm:tableName' => 'products_media'
	// 	)
	// );
	private $_relationMap = array();
	private $_keys = array('primary' => array('id'));
	public function __construct()
	{
	  parent::__construct('media', $this->_fields, $this->_keys, $this->_relationMap);
	  // Your own constructor code
	}

}