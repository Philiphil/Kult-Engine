<?php

namespace kult_engine;

class daoGenerator
{
	/**
	Not ready for use
	**/
	public $_table;
	public $_obj;
	public $_id;

	public function is_loadable($fnord=null)
	{
		if( is_null($fnord) || !class_exists(connector::class, false) || !class_exists(sqlHelper::class) )
		{
			trigger_error('daoGenerator not loadable');
		}
	}

	public function load_obj($obj)
	{
		$fnord = new \ReflectionClass(connector::class);
		$fnord = $fnord->getDefaultProperties();
		foreach ($fnord as $key => $value)
		{
			if( stripos( $key, $obj ) &&  stripos( $key, 'table' ))
			{
				$this->_table = $value;
			}
			if( stripos( $key, $obj ) &&  stripos( $key, 'obj' ))
			{
				$this->_obj = $value;
			}
			if( stripos( $key, $obj ) &&  stripos( $key, 'id' ))
			{
				$this->_id = $value;
			}
		}
	}

	public function __construct($fnord=null)
	{
		$this->is_loadable($fnord);
		if(!is_array($fnord))
		{
			$this->load_obj($fnord);
		}
	}

	public function get($fnord)
	{
		$query = new sqlHelper();
		$query = $query->select_int($this->_table, $this->_id);
		$query = connector::$_db->prepare($query);
        $query->execute([ $fnord ]);
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);
        return isset($query[0]) ? unserialize($query[0][$this->_obj] : false;
	}

}
