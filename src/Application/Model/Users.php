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
	private $name;
	private $surname;
	private $pseudo;
	private $street;
	private $zip_code;
	private $city;
	private $phone;
	private $society_name;
	private $creation_date;
	private $connexion_date;
	private $last_date;
	private $avatar;
    private $newsletter;

	// ----------------------------------
	public function __construct($ID_user, $mail, $password, $type, $name, $surname, $pseudo, $street, $zip_code, $city, $phone, $society_name, $creation_date, $connexion_date, $last_date, $avatar, $newsletter)
	{
		$this->ID_user = $ID_user;
		$this->mail = $mail;
		$this->password = $password;
		$this->type[] = $type;
		$this->name = $name;
		$this->surname = $surname;
		$this->pseudo = $pseudo;
		$this->street = $street;
		$this->zip_code = $zip_code;
		$this->city = $city;
		$this->phone = $phone;
		$this->society_name = $society_name;
		$this->creation_date = $creation_date;
		$this->connexion_date = $connexion_date;
		$this->last_date = $last_date;
		$this->avatar = $avatar;
        $this->newsletter = $newsletter;
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
	
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getSocietyName()
    {
        return $this->society_name;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @return mixed
     */
    public function getConnexionDate()
    {
        return $this->connexion_date;
    }

    /**
     * @return mixed
     */
    public function getLastDate()
    {
        return $this->last_date;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
}

?>