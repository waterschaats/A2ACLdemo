<?php

class Model_User extends Model_A1_ORM_User implements Acl_Role_Interface {

	// default filters
	protected $_filters = array(
		TRUE => array(
			'trim' => NULL
		)
	);

	// default rules
	protected $_rules = array(
		'username' => array(
			'not_empty'  => NULL,
			'min_length' => array(4),
			'max_length' => array(32),
			'alpha_dash' => NULL,
		),
		'password' => array
		(
			'not_empty'  => NULL,
			'min_length' => array(5),
			'max_length' => array(42),
		),
		'password_confirm'	=> array
		(
			'matches' => array('password'),
		)
	);

	protected $_callbacks = array(
		'password' => array('username_available')
	);

	// Columns to ignore
	protected $_ignored_columns = array('password_confirm');

	public function get_role_id()
	{
		return $this->role;
	}

	/**
	 * Tests if a username exists in the database. This can be used as a
	 * Valdidation callback.
	 *
	 * @param   object    Validate object
	 * @param   string    Field
	 * @param   array     Array with errors
	 * @return  array     (Updated) array with errors
	 */
	public function username_available(Validate $array, $field)
	{
		if ($this->loaded() AND $this->_object[$field] === $array[$field])
		{
			// This value is unchanged
			return TRUE;
		}

		if( ORM::factory($this->_user_model)->where($field,'=',$array[$field])->find_all(1)->count() )
		{
			$array->errors($field,'username_available');
		}
	}

	public function save()
	{
		if(array_key_exists( $this->_columns['password'] ,$this->_changed))
		{
			$this->_object[ $this->_columns['password'] ] = A1::instance($this->_config)->hash_password($this->_object[ $this->_columns['password'] ]);
		}

		return parent::save();
	}
} // End User Model