<?php

namespace kult_engine;

abstract class daoableObject
{
	public $id = 0 ;
	public $db_table = null;
	public $db_id = 'id';
	public $db_obj = 'obj';
}