<?php

namespace Application\Provider;

use Application\Model\Users;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UsersControllerProvider implements UserProviderInterface
{
	private $_db;

	public function __construct($_db)
	{
		$this->_db = $_db;
	}

	public function supportsClass($class)
	{
		return $class === 'Application\Model\Users';
	}


	public function refreshUser(UserInterface $user)
	{
		if(!$user instanceof Users)
		{
			throw new UnsupportedUserException(sprintf('Les instances de <b>%s</b> ne sont pas autorisées.', get_class($user)));
		}
		
		return $this->loadUserByUsername($user->getUsername());
	}


	public function loadUserByUsername($user_mail)
	{
		$user = $this->_db->for_table('users')
		->where('mail', $user_mail)
		->find_one();

		if(empty($user))
		{
			throw new UsernameNotFoundException(sprintf('%s n\'existe pas', $user_mail));
		}
		
		return new Users($user->ID_user, $user->mail, $user->password, $user->type);
	}
}

?>