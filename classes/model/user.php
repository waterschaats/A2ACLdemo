<?php

class Model_User extends Model_A1_User implements Acl_Role_Interface {

	public function get_role_id()
	{
		return $this->role;
	}

	/**
	 * Validates and optionally saves a new user record from an array.
	 *
	 * @param  array    values to check
	 * @param  boolean  save the record when validation succeeds
	 * @return boolean
	 */
	public function validate(array & $array, $save = FALSE, & $errors)
	{
		$array = Validate::factory($array)
			->filter(TRUE,'trim')
			->rules('username', array(
				'required'   => NULL,
				'length'     => array(4,127),
				'alpha_dash' => NULL
			))
			->callback('username',array($this, 'username_available'))
			->rule('password', 'length',array(5,42))
			->rule('password_confirm', 'matches',array('password'))
			->rule('role','in_array',array(array('user','admin')));

		if ( ! $this->loaded)
		{
			// This user is new, the password must be provided
			$array->rule('password', 'required');
		}

		return parent::validate($array, $save, $errors);
	}
} // End User Model