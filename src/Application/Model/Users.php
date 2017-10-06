<?php 

namespace Application\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class Users implements UserInterface
{
	// VARIABLES CLASS
	private $ID_user;
	private $mail;
	private $password;
	private $type;
	// ----------------------------------
	public function __construct($ID_user, $mail, $password, $type)
	{
		$this->ID_user = $ID_user;
		$this->mail = $mail;
		$this->password = $password;
		$this->type[] = $type;
	}
	// ----------------------------------


	// ----------------------------------

	// FONCTIONS D'IMPLEMENTATION
	public function getRoles()
	{
		return $this->getType();
	}


	public function getPassword()
	{
		return $this->getPass();
	}


	public function getSalt()
	{
		return null;
	}


	public function getUsername()
	{
		return $this->getMail();
	}


	public function eraseCredentials()
	{}
	// ----------------------------------

	// GETTERS
	public function getID_user()
	{
		return $this->ID_user;
	}


	public function getMail()
	{
		return $this->mail;
	}


	public function getType()
	{
		return $this->type;
	}

	public function getPass()
	{
		return $this->password;
	}
	// ----------------------------------
}

?>