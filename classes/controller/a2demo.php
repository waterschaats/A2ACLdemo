<?php

class Controller_A2demo extends Controller {

	public function before()
	{
		$this->a2 = A2::instance('a2-demo');
		$this->a1 = $this->a2->a1;

		$this->user = $this->a2->get_user();

		echo '<div style="position:absolute;top:0px;left:400px;background-color:#f0f0f0;font-weight:bold;padding:5px;">',html::anchor('a2demo/','index'),'-',html::anchor('a2demo/db','DB'),'</div>';
	}

	public function action_index()
	{
		$blogs = ORM::factory('blog')->find_all();

		// show user info
		echo $this->user_info();

		// show blogs
		echo '<hr>';
		if(count($blogs) === 0)
		{
			echo 'No blogs yet<br>';
		}
		else
		{
			foreach($blogs as $blog)
			{
				echo $blog->text,'<br>';
				echo html::anchor('a2demo/edit/'.$blog->id,'Edit'),'-',html::anchor('a2demo/delete/'.$blog->id,'Delete'),'<hr>';
			}
		}
		echo html::anchor('a2demo/add','Add');
	}

	private function user_info()
	{
		if( $this->user )
		{
			$s =  '<b>'.$this->user->username.' <i>('.$this->user->role . ')</i></b> ' . html::anchor('a2demo/logout','Logout');
		}
		else
		{
			$s = '<b>Guest</b> ' . html::anchor('a2demo/login','Login') . ' - ' . html::anchor('a2demo/create','Create account');
		}
		
		return '<div style="width:95%;padding:5px;background-color:#AFB6FF;">' . $s . '</div>';
	}
	
	public function action_create()
	{
		if($this->user) //cannot create new accounts when a user is logged in
			$this->action_index();
		
		// Create a new user
		$user = ORM::factory('user');

		$post = $_POST;

		if ($user->validate($post,TRUE,$errors))
		{
			// user  created, show login form
			$this->action_login();
		}
		else
		{
			//show form
			echo form::open();
			echo Kohana::debug($errors);
			echo 'username:' . form::input('username') . '<br>';
			echo 'password:' . form::password('password') . '<br>';
			echo 'password confirm:' . form::password('password_confirm') . '<br>';
			echo 'role:' . form::select('role',array('user'=>'user','admin'=>'admin')) . '<br>';
			echo form::submit('create','create');
			echo form::close();
		}

		echo Kohana::debug($post->as_array(),$user->as_array());
	}

	public function action_login()
	{
		if($this->user) //cannot create new accounts when a user is logged in
			return $this->action_index();

		$post = Validate::factory($_POST)
			->filter(TRUE,'trim')
			->rule('username', 'required')
			->rule('username', 'length', array(4,127))
			->rule('password', 'required');

		if($post->check($errors))
		{
			if($this->a1->login($post['username'],$post['password'], isset($_POST['remember']) ? (bool) $_POST['remember'] : FALSE))
			{
				$this->request->redirect( 'a2demo/index' );
			}
		}

		//show form
		echo form::open();
		echo Kohana::debug($errors);
		echo 'username:' . form::input('username') . '<br>';
		echo 'password:' . form::password('password') . '<br>';
		echo 'remember me:' . form::checkbox('remember',TRUE) . '<br>';
		echo form::submit('login','login');
		echo form::close();
	}

	public function action_logout()
	{
		$this->a1->logout();
		$this->user = NULL;
		return $this->action_index();
	}

	public function action_add()
	{
		if(!$this->a2->allowed('blog','add'))
		{
			echo '<b>You are not allowed to add blogs</b><br>';
			return $this->action_index();
		}

		$blog = ORM::factory('blog');

		$this->editor($blog);
	}

	public function action_edit($blog_id)
	{
		$blog = ORM::factory('blog',$blog_id);

		// NOTE the use of the actual blog object in the allowed method call!
		if(!$this->a2->allowed($blog,'edit')) 
		{
			echo '<b>You are not allowed to edit this blog</b><br>';
			return $this->action_index();
		}

		$this->editor($blog);
	}
	
	private function editor($blog)
	{
		if($blog->validate($_POST,FALSE,$errors))
		{
			$blog->user_id = $this->a2->get_user()->id;
			$blog->save();
			return $this->action_index();
		}

		//show form
		echo form::open();
		echo 'text:' . form::textarea('text',$blog->text) . '<br>';
		echo form::submit('post','post');
		echo form::close();
	}

	public function action_delete($blog_id)
	{
		$blog = ORM::factory('blog',$blog_id);

		// NOTE the use of the actual blog object in the allowed method call!
		if(!$this->a2->allowed($blog,'delete')) 
		{
			echo '<b>You are not allowed to delete this blog</b><br>';
		}
		else
		{
			$blog->delete();
		}

		$this->action_index();
	}

	public function action_db()
	{
		echo '<b>Mysql DB structure</b><hr>';

		echo '<b>Please note there are different ways to store your role(s). In this example, only one role per user is supported, and the roles are saved using an ENUM column. You might want to choose a different table schema depending on your requirements</b><hr>';

		echo "<pre>
		CREATE TABLE IF NOT EXISTS `users` (
		  `id` int(12) unsigned NOT NULL auto_increment,
		  `username` varchar(32) NOT NULL default '',
		  `password` char(50) NOT NULL,
		  `token` varchar(32) default NULL,
		  `logins` int(10) unsigned NOT NULL default '0',
		  `last_login` int(10) unsigned default NULL,
		  `role` enum('user','admin') NOT NULL,
		  PRIMARY KEY  (`id`),
		  UNIQUE KEY `uniq_username` (`username`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

		CREATE TABLE IF NOT EXISTS `blogs` (
		  `id` int(12) unsigned NOT NULL auto_increment,
		  `user_id` int(12) unsigned NOT NULL,
		  `text` text NOT NULL,
		  PRIMARY KEY  (`id`),
		  KEY `user_id` (`user_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		</pre>";
	}

} 