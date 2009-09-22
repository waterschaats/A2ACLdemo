<?php 

class Controller_AclDemo extends Controller {
	
	public function before() {
		$this->acl1 = new Zend_Acl;
		$this->acl1->addRole(new Zend_Acl_Role('owner'));
		$this->acl1->addRole(new Zend_Acl_Role('user'));
		$this->acl1->addRole(new Zend_Acl_Role('guest'));
		$this->acl1->add(new Zend_Acl_Resource('blog'));

		$this->acl2 = new Acl;

		$this->acl2->add_role('owner');
		$this->acl2->add_role('user');
		$this->acl2->add_role('guest');
		$this->acl2->add_resource('blog');

		echo '<b>See source for usage, use links to verify output</b><br>';

		for($i = 1; $i < 13; $i++)
		{
			echo html::anchor('acldemo/demo'.$i,'demo ' . $i),'<br>';
		}
	}

	public function action_index()
	{
	}
	
	public function action_demo1()
	{
		// Check on resource while 1 denying privilege is set
		
		//zend
		$this->acl1->allow('guest','blog');
		$this->acl1->deny('guest','blog','read');
		echo ($this->acl1->isAllowed('guest','blog','read') ? 'yes' : 'no') . '<br>';
		
		//new
		$this->acl2->allow('guest','blog');
		$this->acl2->deny('guest','blog','read');
		echo ($this->acl2->is_allowed('guest','blog','read') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo2()
	{
		//zend
		// Resource is allowed to all, except owner
		$this->acl1->allow(NULL,'blog');
		$this->acl1->deny('owner','blog');
		
		// check for owner
		echo ($this->acl1->isAllowed('owner','blog') ? 'yes' : 'no') . '<br>';
		// check for all
		echo ($this->acl1->isAllowed(NULL,'blog') ? 'yes' : 'no') . '<br>';
		
		//new
		// Resource is allowed to all, except owner
		$this->acl2->allow(NULL,'blog');
		$this->acl2->deny('owner','blog');

		// check for owner
		echo ($this->acl2->is_allowed('owner','blog') ? 'yes' : 'no') . '<br>';
		// check for all
		echo ($this->acl2->is_allowed(NULL,'blog') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo3()
	{
		//zend
		// Owner can do everything but reading
		$this->acl1->allow('owner');
		$this->acl1->deny('owner',NULL,'read');
		echo ($this->acl1->isAllowed(NULL,NULL,'read') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('owner') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('owner',NULL,'read') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('owner','blog','read') ? 'yes' : 'no') . '<br>';
		
		echo '<hr>';
		
		//new
		$this->acl2->allow('owner');
		$this->acl2->deny('owner',NULL,'read');
		echo ($this->acl2->is_allowed(NULL,NULL,'read') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('owner') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('owner',NULL,'read') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('owner','blog','read') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo4()
	{
		//zend
		$this->acl1->allow('owner','blog');
		$this->acl1->deny(NULL,'blog');
		echo ($this->acl1->isAllowed('owner') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('owner','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('guest','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('guest') ? 'yes' : 'no') . '<br>';
		
		//new
		$this->acl2->allow('owner','blog');
		$this->acl2->deny(NULL,'blog');
		echo ($this->acl2->is_allowed('owner') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('owner','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('guest','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('guest') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo5()
	{
		//zend
		$this->acl1->allow(NULL,'blog');
		$this->acl1->deny('owner','blog','delete');
		echo ($this->acl1->isAllowed('owner','blog') ? 'yes' : 'no') . '<br>';
		
		//new
		$this->acl2->allow(NULL,'blog');
		$this->acl2->deny('owner','blog','delete');
		echo ($this->acl2->is_allowed('owner','blog') ? 'yes' : 'no') . '<br>';
	}
		

	public function action_demo6()
	{
		//zend
		$this->acl1->allow(NULL,'blog');
		$this->acl1->deny('owner','blog');
		echo ($this->acl1->isAllowed(NULL,'blog') ? 'yes' : 'no') . '<br>';
		
		//new
		$this->acl2->allow(NULL,'blog');
		$this->acl2->deny('owner','blog');
		echo ($this->acl2->is_allowed(NULL,'blog') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo7()
	{
		//zend
		$this->acl1->allow('user','blog');
		echo ($this->acl1->isAllowed('user','blog') ? 'yes' : 'no') . '<br>';
		$this->acl1->deny('user','blog','read');
		echo ($this->acl1->isAllowed('user','blog') ? 'yes' : 'no') . '<br>';
		
		//new
		$this->acl2->allow('user','blog');
		echo ($this->acl2->is_allowed('user','blog') ? 'yes' : 'no') . '<br>';
		$this->acl2->deny('user','blog','read');
		echo ($this->acl2->is_allowed('user','blog') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo8()
	{
		// resource inheritance
		
		//zend
		$this->acl1->add(new Zend_Acl_Resource('article'),'blog');
		$this->acl1->allow('user','blog');
		$this->acl1->deny('user','article','read');
		echo ($this->acl1->isAllowed('user','article') ? 'yes' : 'no') . '<br>';		
		
		//new
		$this->acl2->add_resource('article','blog');
		$this->acl2->allow('user','blog');
		$this->acl2->deny('user','article','read');
		echo ($this->acl2->is_allowed('user','article') ? 'yes' : 'no') . '<br>';
		
	}

	public function action_demo9()
	{
		// role inheritance
		
		//zend
		$this->acl1->addRole(new Zend_Acl_Role('super_user'),'user'); 
		$this->acl1->allow('user','blog');
		echo ($this->acl1->isAllowed('super_user','blog') ? 'yes' : 'no') . '<br>';		
		echo ($this->acl1->isAllowed('user','blog') ? 'yes' : 'no') . '<br>';		
		
		//new
		$this->acl2->add_role('super_user','user');
		$this->acl2->allow('user','blog');
		echo ($this->acl2->is_allowed('super_user','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('user','blog') ? 'yes' : 'no') . '<br>';
	}
	
	public function action_demo10()
	{
		// role inheritance, multiple parents
		
		//zend
		$this->acl1->addRole(new Zend_Acl_Role('super_user'),array('user','guest'));
		$this->acl1->allow('user','blog');
		echo ($this->acl1->isAllowed('super_user','blog') ? 'yes' : 'no') . '<br>';		
		echo ($this->acl1->isAllowed('user','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('guest','blog') ? 'yes' : 'no') . '<br>';
		
		// super user inherits from both user and guest,
		// now add conflicting rules to these parents
		$this->acl1->allow('user','blog','edit');
		$this->acl1->deny('guest','blog','edit');

		echo ($this->acl1->isAllowed('super_user','blog','edit') ? 'yes' : 'no') . '<br>';		
		echo ($this->acl1->isAllowed('user','blog','edit') ? 'yes' : 'no') . '<br>';
		echo ($this->acl1->isAllowed('guest','blog','edit') ? 'yes' : 'no') . '<br>';
		
		echo '<hr>';
		
		//new
		$this->acl2->add_role('super_user',array('user','guest'));
		$this->acl2->allow('user','blog');
		echo ($this->acl2->is_allowed('super_user','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('user','blog') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('guest','blog') ? 'yes' : 'no') . '<br>';
		
		// conflicting rules to parents
		$this->acl2->allow('user','blog','edit');
		$this->acl2->deny('guest','blog','edit');

		echo ($this->acl2->is_allowed('super_user','blog','edit') ? 'yes' : 'no') . '<br>';		
		echo ($this->acl2->is_allowed('user','blog','edit') ? 'yes' : 'no') . '<br>';
		echo ($this->acl2->is_allowed('guest','blog','edit') ? 'yes' : 'no') . '<br>';
	}		
	
	public function action_demo11()
	{
		// Serialization
		
		$s1 = serialize($this->acl1);
		$s2 = serialize($this->acl2);
		
		echo 'Zend: ',$s1,'<br>New: ',$s2,'<hr>';
		
		$this->acl1 = unserialize($s1);
		$this->acl2 = unserialize($s2);
		
		// just run some random other demo to see if the ACLs still function correct
		$this->action_demo10();

	}			

	public function action_demo12()
	{
		// Multiple roles - this isn't supported by Zend_ACL, so no comparison to Zend here
		
		$this->acl2 = new Acl;
		
		$this->acl2->add_resource('forum');
		$this->acl2->add_resource('poll');
		
		$this->acl2->add_role('forum_manager');
		$this->acl2->add_role('poll_manager');
		
		$this->acl2->allow('forum_manager','forum','edit');
		$this->acl2->allow('poll_manager','poll','edit');
		
		// supply multiple roles in the is_allowed method
		echo ($this->acl2->is_allowed(array('forum_manager','poll_manager'),'poll','edit') ? 'yes' : 'no') . '<br>';	

	}
} 