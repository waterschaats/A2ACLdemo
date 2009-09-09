<?php

class Model_Blog extends ORM implements Acl_Resource_Interface {

	public function get_resource_id()
	{
		return 'blog';
	}

	protected $_filters = array(
		TRUE => array(
			'trim' => NULL
		)
	);
	
	protected $_rules = array(
		'text' => array(
			'not_empty' => NULL
		)
	);
} // End Blog Model