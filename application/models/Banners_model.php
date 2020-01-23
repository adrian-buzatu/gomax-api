<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners_model extends MY_Model {
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
    'description' => array(
      'type' => 'TEXT'
    ),
    'status' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '1',
    ),
    '__dt:searchable' => array('name')
	);

	private $_relationMap = array(
		'banners_media' => array(
			'id' => array(
	      'type' => 'INT',
	      'constraint' => 11,
	      'unsigned' => TRUE,
	      'auto_increment' => TRUE
	    ),
	    'banner_id' => array(
	      'type' =>'INT',
	      'constraint' => '11'
	    ),
	    'media_id' => array(
	      'type' =>'INT',
	      'constraint' => '11'
	    ),
	    'fields' => array(
  			'id' => array(
  	      'type' => 'INT',
  	      'constraint' => 11,
  	      'unsigned' => TRUE,
  	      'auto_increment' => TRUE
  	    ),
  	    'banner_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
  	    'media_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
	    ),
	    '__comm:baseIdField' => 'banner_id',
	    '__comm:relationIdField' => 'media_id',
	    '__comm:keys' => array('banner_id', 'media_id'),
	    '__comm:pkeys' => array('id'),
	    '__comm:linkedTable' => 'media',
	    '__comm:tableName' => 'banners_media'
		)
	);
	private $_keys = array('primary' => array('id'));
	public function __construct()
	{
	  parent::__construct('banners', $this->_fields, $this->_keys, $this->_relationMap);
	  // Your own constructor code
	}

}