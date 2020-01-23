<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends MY_Model {
  private $_searchString;
  private $_limit = array('from' => 0, 'how_many' => 25);
  private $_order = array('id' => 'DESC');
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
    'code' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'manufacturer_code' => array(
      'type' => 'VARCHAR',
      'constraint' => '255',
      'unique' => TRUE,
    ),
    'price' => array(
      'type' =>'INT',
      'constraint' => '5'
    ),
    'stock' => array(
      'type' =>'INT',
      'constraint' => '5',
      'default' => '100',
    ),
    'status' => array(
      'type' =>'INT',
      'constraint' => '1',
      'default' => '1',
    ),
    'tags' => array(
      'type' => 'VARCHAR',
      'constraint' => '255'
    ),
    'description' => array(
      'type' => 'TEXT'
    ),
    'description_raw' => array(
      'type' => 'TEXT'
    ),
    '__dt:searchable' => array('name', 'code')
	);

	private $_relationMap = array(
		'products_categories' => array(
			'id' => array(
	      'type' => 'INT',
	      'constraint' => 11,
	      'unsigned' => TRUE,
	      'auto_increment' => TRUE
	    ),
	    'product_id' => array(
	      'type' =>'INT',
	      'constraint' => '11'
	    ),
	    'category_id' => array(
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
  	    'product_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
  	    'category_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
	    ),
	    '__comm:baseIdField' => 'product_id',
	    '__comm:relationIdField' => 'category_id',
	    '__comm:linkedTable' => 'categories',
	    '__comm:keys' => array('product_id', 'category_id'),
	    '__comm:pkeys' => array('id'),
	    '__comm:tableName' => 'products_categories'
		),
		'products_media' => array(
			'id' => array(
	      'type' => 'INT',
	      'constraint' => 11,
	      'unsigned' => TRUE,
	      'auto_increment' => TRUE
	    ),
	    'product_id' => array(
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
  	    'product_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
  	    'media_id' => array(
  	      'type' =>'INT',
  	      'constraint' => '11'
  	    ),
	    ),
	    '__comm:baseIdField' => 'product_id',
	    '__comm:relationIdField' => 'media_id',
	    '__comm:keys' => array('product_id', 'media_id'),
	    '__comm:pkeys' => array('id'),
	    '__comm:linkedTable' => 'media',
	    '__comm:tableName' => 'products_media'
		)
	);
	private $_keys = array('primary' => array('id'));
	public function __construct()
	{
	  parent::__construct('products', $this->_fields, $this->_keys, $this->_relationMap);
	  // Your own constructor code
	}
  public function setSearchString($string) {
    $this->_searchString = $string;
    return $this;
  }
  public function setLimit($limit) {
    $this->_limit = $limit;
    return $this;
  }
  public function setOrder($order) {
    $this->_order = $order;
    return $this;
  }
	public function formaProductCategoriesForForm($productId, $categories) {
		$productCategoryIds = $this->getRelationItemLinks($productId, 'products_categories');
		array_walk($categories, function(&$category, &$key) use ($productCategoryIds) {
			$category['selected'] = false;
	    if (in_array($category['id'], $productCategoryIds)) {
	    	$category['selected'] = true;
	    }
		});
		return $categories;
	}
  public function get() {
    $productList = $this->basicSelect($this->_setSelectWhere(), $this->_limit, $this->_order);
    foreach ($productList as &$product) {
      $product['categories'] = $this->getRelationItemLinks($product['id'], 'products_categories', true);
      $product['media'] = $this->getRelationItemLinks($product['id'], 'products_mmedia', true);
    }
    return $productList;
  }

  private function _setSelectWhere() {
    if ($this->_searchString !== "") {
      return "`status` = '1' AND
        (
          `code` LIKE '%". $this->_searchString ."%' OR
          `name` LIKE '%". $this->_searchString ."%' OR
          `description_raw` LIKE '%". $this->_searchString ."%'
        )
      ";
    }
    return "`status` = '1'";
  }
}