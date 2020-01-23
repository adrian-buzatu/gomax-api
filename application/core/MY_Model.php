<?php
	class MY_Model extends CI_Model {
		private $_tableName;
		private $_columnOrder;
		private $_fields;
		private $_searchable;
		private $_keys;
		private $_relationMap;
		private $_baseIdField;
		private $_relationIdField;
		private $_linkedTable;
		public function __construct($tableName, $fields, $keys = array(), $relationMap = array())
		{
		  parent::__construct();
		  $this->_tableName = $tableName;
		  $this->_columnOrder = array_keys($fields);
		  $this->_fields = $fields;
		  $this->_keys = $keys;
		  if (isset($relationMap)) {
		  	$this->_relationMap = $relationMap;
		  }
		  $this->_searchable = $fields['__dt:searchable'];
		  $this->_baseIdField = '__comm:baseIdField';
		  $this->_relationIdField = '__comm:relationIdField';
		  $this->_linkedTable = '__comm:linkedTable';
		  unset($this->_fields['__dt:searchable']);
		  // Your own constructor code
		  $this->load->dbforge();
		  $this->_createIfNotExists();
		}
		public function rescanTableBase() {
			$this->_createIfNotExists();
		}
		public function insert($data, $relationCode = false) {
			$table = ($relationCode == false ? $this->_tableName : $relationCode);
			$insert = $this->db->insert_string($table, $data). 'ON DUPLICATE KEY UPDATE id=id'; //on duplicate key, do nothing.
			$this->db->query($insert);
			return $this->db->insert_id();
		}
		public function insertRelation($baseIdValue, $relationIdValue, $relationCode) {
			if (isset($this->_relationMap[$relationCode])) {
				$data = array(
					$this->_relationMap[$relationCode][$this->_baseIdField] => $baseIdValue,
					$this->_relationMap[$relationCode][$this->_relationIdField] => $relationIdValue
				);
				return $this->insert($data, $relationCode);
			}
			return false;
		}
		public function count() {
			return $this->db->count_all($this->_tableName);
		}
		public function basicSelect($where = array('status' => 1), $limit = array('from' => 0, 'how_many' => 25), $order = array('id' => 'DESC')) {
			$this->db->select('*')->from($this->_tableName);
			if (!empty($where) && is_array($where)) {
				$this->db->where($where);
			}
			if (!empty($limit) && isset($limit['from']) && isset($limit['how_many'])) {
				$this->db->limit($limit['how_many'], $limit['from']);
			}
			if (!empty($order) && is_array($order)) {
				$this->db->order_by(key($order), $order[key($order)]);
			}
			$res = $this->db->get()->result_array();
			return $res;
		}

		public function getOne($id) {
			$query = $this->db->get_where($this->_tableName, array('id' => (int)$id), 1);
			$res = $query->result_array();
			return count($res) > 0 ? $res[0] : array();
		}
		public function update($data, $where) {
			if (!empty($where) && is_array($where)) {
				$this->db->update($this->_tableName, $data, $where);
			}
		}
		public function fakeDelete($where) {
			if (!empty($where) && is_array($where)) {
				$this->db->update($this->_tableName, array('status' => 0), $where);
			}
		}

		public function getRelationItemLinks($baseIdValue, $relationCode, $details = false) {
			if (isset($this->_relationMap[$relationCode])) {
				$baseFieldName = $this->_relationMap[$relationCode][$this->_baseIdField];
				$linkIdFieldName = $this->_relationMap[$relationCode][$this->_relationIdField];
				$linkedTbl = $this->_relationMap[$relationCode][$this->_linkedTable];
				$where = array(
					$baseFieldName => $baseIdValue,
				);
				// if ($details === true) {
				// 	echo $this->db->select('*')
				// 		->from($relationCode)
				// 		->join($linkedTbl, $relationCode. ".". $linkIdFieldName ." = ". $linkedTbl. ".id")
				// 		->where($where)
				// 		->get_compiled_select();
				// 	die;
				// }
				$this->db->select('*')->from($relationCode);
				if ($details === true) {
					$this->db->join($linkedTbl, $relationCode. ".". $linkIdFieldName ." = ". $linkedTbl. ".id");
				}
				$this->db->where($where);
				$result = $this->db->get()->result_array();
				if ($details === false) {
					$ids = array();
					foreach ($result as $item) {
						$ids[] = $item[$linkIdFieldName];
					}
					return $ids;
				}
				return $result;
			}
			return array();
		}

		public function deleteRelation($baseIdValue, $relationIdValue, $relationCode) {
			if (isset($this->_relationMap[$relationCode])) {
				$data = array(
					$this->_relationMap[$relationCode][$this->_baseIdField] => $baseIdValue,
					$this->_relationMap[$relationCode][$this->_relationIdField] => $relationIdValue
				);
				$this->db->delete($relationCode, $data);
			}
		}
		public function deleteRelations($baseIdValue, $relationCode) {
			if (isset($this->_relationMap[$relationCode])) {
				$data = array(
					$this->_relationMap[$relationCode][$this->_baseIdField] => $baseIdValue,
				);
				$this->db->delete($relationCode, $data);
			}
		}
		private function _createIfNotExists(){
			if (!$this->db->table_exists($this->_tableName)) {
				$this->_createTable($this->_fields,
					array('key_types' => array('primary', 'non_primary'), 'primary_key_label' => 'primary', 'table_name' => $this->_tableName));
			}
			if (!empty($this->_relationMap) && is_array($this->_relationMap)) {
				foreach ($this->_relationMap as $code => $relation) {
					if (!$this->db->table_exists($code)) {
						$this->_createTable($relation['fields'],
							array(
								'key_types' => array('__comm:pkeys', '__comm:keys'),
								'primary_key_label' => '__comm:pkeys',
								'custom_keyset' => array_merge(
									array('__comm:pkeys' => $relation['__comm:pkeys']),
									array('__comm:keys' => $relation['__comm:keys'])
								),
								'table_name' => $code
							)
						);
					}
				}
			}

		}
		private function _createTable($fields, $config){
			$this->dbforge->add_field($fields);
			$keyset = empty($config['custom_keyset']) ? $this->_keys : $config['custom_keyset'];
			foreach ($config['key_types'] as $keyType) {
				if (!empty($keyset[$keyType]) && is_array($keyset[$keyType])) {
					foreach ($keyset[$keyType] as $key) {
						$this->dbforge->add_key($key, ($keyType === $config['primary_key_label'] ? true : false));
					}
				}
			}
			$this->dbforge->create_table($config['table_name']);
		}
		/* DATATABLES FUNCTIONS*/
		public function countDtFiltered($postData){
			$this->_getDtQuery($postData);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function getDtRows($postData){
      $this->_getDtQuery($postData);
      if($postData['length'] != -1){
        $this->db->limit($postData['length'], $postData['start']);
      }
      $query = $this->db->get();
      return $query->result_array();
	  }

		private function _getDtQuery($postData, $customWhere = array()){
      $this->db->from($this->_tableName);
      if (empty($customWhere) || !is_array($customWhere)) {
      	$this->db->where(array('status' => 1));
      } else {
      	$this->db->where($customWhere);
      }

      $i = 0;
      // loop searchable columns
      foreach($this->_searchable as $item){
        // if datatable send POST for search
        if($postData['search']['value']){
            // first loop
            if($i === 0) {
                // open bracket
                $this->db->group_start();
                $this->db->like($item, $postData['search']['value']);
            }else{
                $this->db->or_like($item, $postData['search']['value']);
            }

            // last loop
            if(count($this->column_search) - 1 == $i){
              // close bracket
              $this->db->group_end();
            }
        }
        $i++;
      }

      if(isset($postData['order'])){
        $this->db->order_by($this->_columnOrder[$postData['order']['0']['column']], $postData['order']['0']['dir']);
      }else if(isset($this->order)){
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
      }
  	}
  	/* END DATATABLES FUNCTIONS*/
	}