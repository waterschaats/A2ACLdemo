<?php

class Model_Blog extends ORM implements Acl_Resource_Interface {

	public function get_resource_id()
	{
		return 'blog';
	}

	public function validate(array & $array, $save = FALSE, & $errors)
	{
		$array = Validate::factory($array)
			->filter(TRUE,'trim')
			->rule('text','required');

		return parent::validate($array, $save, $errors);
	}

} // End Blog Model